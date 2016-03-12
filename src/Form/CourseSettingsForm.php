<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 02/02/16
 * Time: 16:13
 */

namespace Drupal\moodle\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CourseSettingsForm
 *   This class generates a form that is used by administrators to set up
 *   configurations that will be used on courses.
 *
 *   NB:
 *   Some functionality maybe ported in the near future from this form to
 *   configurations in the views fields.
 *
 * @package Drupal\moodle\Form
 */
class CourseSettingsForm extends ConfigFormBase{

  /**
   * Connector settings.
   */
  const SETTINGS = 'moodle.course_settings';

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'moodle_course_settings';
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
    $options = array(
      '_self' => $this->t('Open the course in the same window as it was clicked'),
      '_blank' => $this->t('Open the course in a new window or tab'),
    );

    // Moodle Course List default link target.
    $form['window_target'] = array(
      '#title'         => t('Link target attribute'),
      '#default_value' => $config->get('window_target'),
      '#description'   => $this->t("Open the course in a new window or not."),
      '#required'      => FALSE,
      '#type' => 'select',
      '#options' => $options,
      '#multiple' => FALSE,
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get form variable values.
    $config = $this->config(self::SETTINGS);
    $config->set('window_target', $form_state->getValue('window_target'));
    parent::submitForm($form, $form_state);
  }

}
