<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 02/02/16
 * Time: 08:35
 */

namespace Drupal\moodle\Traits;

use Drupal\Core\Database\Database;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\moodle\Form\ConnectorSettingsForm;

/**
 * Class Connector
 * @package Drupal\moodle\Traits
 *
 * Any class using this trait should inject the appropriate members via the DI.
 */
trait Connector {

  use StringTranslationTrait;

  /**
   * The corresponding database connection object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected static $connection;

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Connect to the Moodle database.
   *
   * @return bool false on failure / mysqli MySQLi object instance on success
   */
  public function connect() {
    // Try and connect to the database
    if (NULL === self::$connection) {
      try {
        // Add connection to the Moodle database.
        $database_settings = $this->getDatabaseSettings();
        Database::addConnectionInfo('moodle', 'default', $database_settings);

        // Connect to the database.
        self::$connection = Database::getConnection('default', 'moodle');

        // Return the database object.
        return self::$connection;
      }
      catch (Exception $e) {
        $this->loggerFactory->get('Moodle connection', $this->t('Error connecting to the database: "%error".', array('%error' => $e->getMessage())));
        drupal_set_message($this->t('Error connecting to the database: "%error".', array('%error' => $e->getMessage())));

        return FALSE;
      }
    }

    return self::$connection;
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

}
