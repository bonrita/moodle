<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 02/03/16
 * Time: 20:29
 */

namespace Drupal\moodle\Plugin\Views\Argument;


use Drupal\Core\Form\FormStateInterface;
use Drupal\user\UserDataInterface;
use Drupal\views\Plugin\views\argument\ArgumentPluginBase;
use Drupal\views\Plugin\views\argument\NumericArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Argument handler to accept a user id.
 *
 * @ingroup views_argument_handlers
 *
 * @ViewsArgument("moodle_user_uid")
 */
class Uid extends ArgumentPluginBase {

  /**
   * The extra user data.
   *
   * @var \Drupal\user\UserDataInterface
   */
  protected $userData;

  /**
   * @inheritDoc
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UserDataInterface $user_data) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->userData = $user_data;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('user.data')
    );
  }

  /**
   * Information about options for all kinds of purposes will be held here.
   * @code
   * 'option_name' => array(
   *  - 'default' => default value,
   *  - 'contains' => (optional) array of items this contains, with its own
   *      defaults, etc. If contains is set, the default will be ignored and
   *      assumed to be array().
   *  ),
   * @endcode
   *
   * @return array
   *   Returns the options of this handler/plugin.
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['contextlevel'] = array('default' => 50);

    return $options;
  }

  /**
   * Provide a form to edit options for this plugin.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {

    $form['contextlevel'] = array(
      '#type'          => 'textfield',
      '#title'         => t('The context level'),
      '#default_value' => $this->options['contextlevel'],
      '#size'          => 5,
      '#maxlength'     => 10,
    );

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * Add this filter to the query.
   *
   * Due to the nature of fapi, the value and the operator have an unintended
   * level of indirection. You will find them in $this->operator
   * and $this->value respectively.
   */
  public function query($group_by = FALSE) {
    $this->ensureMyTable();

    // Get the moodle - drupal uid mapping.
    $uid = $this->getDrupalMoodleUidMapping();

    // Build the subquery to add extra data so as to retrieve courses for a user.
    $subquery = db_select('context', 'ctxt');
    $subquery->addField('ctxt', 'instanceid');

    $subquery->innerJoin('role_assignments', 'ra', 'ra.contextid = ctxt.id');
    $subquery->innerJoin('role', 'r', 'r.id = ra.roleid');
    $subquery->innerJoin('user', 'u', 'u.id = ra.userid');

    $where = db_and()->condition('ctxt.contextlevel', $this->options['contextlevel'], '=');
    $where->condition("u.id", $uid, '=');

    $subquery->condition($where);

    $this->query->addWhere(0, "$this->tableAlias.$this->realField", $subquery, 'IN');

  }

  /**
   * Get the moodle corresponding uid.
   *
   * That is the moodle UID related to the drupal UID.
   *
   * @return array|mixed|null
   *   The moodle uid.
   */
  protected function getDrupalMoodleUidMapping(){
    $uid = $this->userData->get('moodle', $this->argument, 'uid');

    if(empty($uid)) {
      $uid = $this->argument;
    }

    return $uid;
  }

}
