<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 30/01/16
 * Time: 16:22
 */

namespace Drupal\moodle\Form;


use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ConnectorSettingsForm extends ConfigFormBase {

  /**
   * Connector settings.
   */
  const SETTINGS = 'moodle.connector_settings';

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'moodle_connector_settings';
  }

  /**
   * @inheritDoc
   */
  protected function getEditableConfigNames() {
    return [self::SETTINGS];
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get form variable values.
    $config = $this->config(self::SETTINGS);

    // Moodle Database Settings.
    $form['db_type'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Database Type'),
      '#default_value' => $config->get('db_type'),
      '#description'   => $this->t("The type of the Moodle database. Common values for this field are 'mysql' or 'pgsql'."),
      '#size'          => 20,
      '#required'      => TRUE,
    );
    $form['db_server'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Database Server'),
      '#default_value' => $config->get('db_server'),
      '#description'   => t('The database host where Moodle is installed.'),
      '#required'      => TRUE,
    );
    $form['port'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Database TCP Port'),
      '#default_value' => $config->get('port'),
      '#description'   => $this->t('The TCP port number of the database server. Default for MySQL is 3306.'),
      '#size'          => 20,
      '#required'      => TRUE,
    );
    $form['database'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Database Name'),
      '#default_value' => $config->get('database'),
      '#description'   => $this->t('The database name where Moodle is installed.'),
      '#size'          => 20,
      '#required'      => TRUE,
    );
    $form['db_prefix'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Database Prefix'),
      '#default_value' => $config->get('db_prefix'),
      '#description'   => $this->t("The prefix for the Moodle database tables. Default is 'mdl_'."),
      '#size'          => 20,
      '#required'      => FALSE,
    );
    $form['username'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Database User'),
      '#default_value' => $config->get('username'),
      '#description'   => $this->t('User to access to the Moodle database.'),
      '#size'          => 20,
      '#required'      => TRUE,
    );
    $form['password'] = array(
      '#type'          => 'password',
      '#title'         => $this->t('Database Password'),
      '#default_value' => $config->get('password'),
      '#description'   => $this->t('Password for the database user.'),
      '#size'          => 20,
    );

    // Moodle URL.
    $form['url'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Moodle URL'),
      '#default_value' => $config->get('url'),
      '#description'   => $this->t("The full URL of the Moodle instance. Example: 'http://moodle.example.com'. Don't put the trailing '/'."),
      '#required'      => FALSE,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get form variable values.
    $config = $this->config(self::SETTINGS);

    $config->set('db_type', $form_state->getValue('db_type'));
    $config->set('db_server', $form_state->getValue('db_server'));
    $config->set('port', $form_state->getValue('port'));
    $config->set('database', $form_state->getValue('database'));
    $config->set('db_prefix', $form_state->getValue('db_prefix'));
    $config->set('url', $form_state->getValue('url'));
    $config->set('username', $form_state->getValue('username'));
    $config->set('password', $form_state->getValue('password'));

    $config->save(TRUE);

    parent::submitForm($form, $form_state);
  }

}
