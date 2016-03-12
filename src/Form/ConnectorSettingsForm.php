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

/**
 * Class ConnectorSettingsForm
 *   This class generates a form that administrators will use to add moodle
 *   specific setttings e.g the moodle url
 *
 *   NB:
 *   In future some settings maybe completely removed e.g the database settings
 *   in favour of adding them directly to the settings.php file.
 *   This is because we are using views for all the rendering and views expects
 *   that the external database settings should be in the settings.php
 *
 * @package Drupal\moodle\Form
 */
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

    $form['database_connection_key'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('The database connection key'),
      '#default_value' => $config->get('database_connection_key'),
      '#description'   => $this->t('The moodle database connection key. Defaults to Moodle which means the database key which was set in the settings.php for moodle database.'),
      '#size'          => 20,
      '#required'      => TRUE,
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

    $config->set('url', $form_state->getValue('url'));
    $config->set('database_connection_key', $form_state->getValue('database_connection_key'));

    $config->save(TRUE);

    parent::submitForm($form, $form_state);
  }

}
