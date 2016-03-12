<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 04/03/16
 * Time: 20:12
 */

namespace Drupal\moodle\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\moodle\MoodleStorage;
use Drupal\user\UserDataInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserMapping
 *   This class generates a form that is shown on the user's profile.
 *   Administrators can use this form to map drupal users with a correspoding
 *   moodle user.
 *
 * @package Drupal\moodle\Form
 */
class UserMapping extends FormBase {

  /**
   * @var \Drupal\moodle\MoodleStorage
   */
  protected $storage;

  /**
   * @var \Drupal\user\UserDataInterface
   */
  protected $userData;

  /**
   * @inheritDoc
   */
  public function __construct(MoodleStorage $storage, UserDataInterface $user_data) {
    $this->storage = $storage;
    $this->userData = $user_data;
    $this->getRouteMatch();
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorage('moodle_user'),
      $container->get('user.data')
    );
  }

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'moodle_user_mapping';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['moodle_uid'] = array(
      '#type' => 'select',
      '#title' => $this->t('User'),
      '#options' => $this->getUsers(),
      '#default_value' => $this->getDefaultValue(),
      '#description' => $this->t('Set this to the corresponding user in the moodle application.'),
    );

    $form['drupal_uid'] = array(
      '#type' => 'value',
      '#value' => $this->getDrupalUid(),
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save the mapping'),
      '#button_type' => 'primary',
    );

    // By default, render the form using theme_system_config_form().
    $form['#theme'] = 'system_config_form';

    return $form;
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $moodle_uid = $form_state->getValue('moodle_uid');
    $drupal_uid = $form_state->getValue('drupal_uid');

    if ($moodle_uid > 0) {
      $this->userData->set('moodle', $drupal_uid, 'uid', $moodle_uid);
      drupal_set_message($this->t('The moodle - drupal user mapping has been saved.'));
    } else {
      $this->userData->set('moodle', $drupal_uid, 'uid', '');
      drupal_set_message($this->t('You have not selected anything.'));
    }
  }

  /**
   * Retrieve a list of users.
   *
   * @return array
   *   A list of moodle users.
   */
  protected function getUsers(){
    $options = array('' => $this->t('Select'));

    foreach ($this->storage->loadMultiple() as $user ) {
      $options[$user->id()] = $user->getName();
    }

    return $options;
  }

  /**
   * Get the deafult user data value of the user in question.
   *
   * @return array|mixed
   *   The default user data.
   */
  protected function getDefaultValue(){
    $uid = $this->getDrupalUid();
    $value = $this->userData->get('moodle', $uid, 'uid');
    return $value;
  }

  /**
   * Get the user ID.
   *
   * @return int
   *   The drupal user id in the path.
   */
  protected function getDrupalUid(){
    return $this->routeMatch->getParameter('user');
  }

}
