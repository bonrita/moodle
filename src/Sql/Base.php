<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 04/02/16
 * Time: 23:05
 */

namespace Drupal\moodle\Sql;


use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\moodle\Form\ConnectorSettingsForm;

abstract class Base {
// see : drupal8/core/modules/migrate/src/Plugin/migrate/source/SqlBase.php
// Drupal\migrate\Plugin\migrate\source\SqlBase

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * @var \Drupal\Core\Database\Query\SelectInterface
   */
  protected $query;

  /**
   * Connector constructor.
   */
  public function __construct(ConfigFactoryInterface $config, LoggerChannelFactoryInterface $logger_factory) {
    $this->config = $config;
    $this->loggerFactory = $logger_factory;
  }

  /**
   * Get the moodle database connection object.
   *
   * @return \Drupal\Core\Database\Connection
   *   The database connection.
   */
  public function getDatabase() {
    // Try and connect to the database
    if (NULL === $this->database) {
      try {
        // Add connection to the Moodle database.
        $database_settings = $this->getDatabaseSettings();
        Database::addConnectionInfo('moodle', 'default', $database_settings);

        // Connect to the database.
        $this->database = Database::getConnection('default', 'moodle');

      }
      catch (Exception $e) {
        $this->loggerFactory->get('Moodle connection', $this->t('Error connecting to the database: "%error".', array('%error' => $e->getMessage())));
        drupal_set_message($this->t('Error connecting to the database: "%error".', array('%error' => $e->getMessage())));

        return FALSE;
      }
    }

    return $this->database;
  }

  /**
   * Get database settings.
   *
   * @return array
   *   A list of database settings.
   */
  protected function getDatabaseSettings() {
    $settings = [
      'driver'   => $this->getConnectorSettings()->get('db_type'),
      'host'     => $this->getConnectorSettings()->get('db_server'),
      'port'     => $this->getConnectorSettings()->get('port'),
      'username' => $this->getConnectorSettings()->get('username'),
      'password' => $this->getConnectorSettings()->get('password'),
      'database' => $this->getConnectorSettings()->get('database'),
      'prefix'   => $this->getConnectorSettings()->get('db_prefix'),
    ];

    return $settings;
  }

  /**
   * Returns an immutable configuration object for moodle connection settings.
   *
   * @return \Drupal\Core\Config\ImmutableConfig
   *   A configuration object.
   */
  protected function getConnectorSettings() {
    return $this->config->get(ConnectorSettingsForm::SETTINGS);
  }

  /**
   * Print the query string when the object is used a string.
   *
   * @return string
   *   The query string.
   */
  public function __toString() {
    return (string) $this->query;
  }

  /**
   * Wrapper for the database select.
   *
   * @param string  $table
   *   The base table for this query, that is, the first table in the
   *   FROM clause. This table will also be used as the "base"
   *   table for query_alter hook implementations.
   *
   * @param string $alias
   *   (optional) The alias of the base table of this query.
   *
   * @param array $options
   *   An array of options on the query.
   *
   * @return \Drupal\Core\Database\Query\SelectInterface
   *   An appropriate SelectQuery object for this database connection.
   *  Note that it may be a driver-specific subclass of SelectQuery,
   *  depending on the driver.
   */
  protected function select($table, $alias = NULL, array $options = array()) {
    $options['fetch'] = \PDO::FETCH_ASSOC;
    return $this->getDatabase()->select($table, $alias, $options);
  }

  /**
   * @return \Drupal\Core\Database\Query\SelectInterface
   */
  abstract public function query();

  /**
   * Return a count of available records.
   */
  public function count() {
    return $this->query()->countQuery()->execute()->fetchField();
  }

}
