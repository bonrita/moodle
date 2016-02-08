<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 04/02/16
 * Time: 10:42
 */

namespace Drupal\moodle;


use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\moodle\Form\ConnectorSettingsForm;

class Connector {
  use StringTranslationTrait;

  /**
   * The corresponding database connection object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected static $connection;

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
   * Connector constructor.
   */
  public function __construct(ConfigFactoryInterface $config, LoggerChannelFactoryInterface $logger_factory) {
    $this->config = $config;
    $this->loggerFactory = $logger_factory;
  }

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