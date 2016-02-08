<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 02/02/16
 * Time: 22:37
 */

namespace Drupal\moodle\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
//use Drupal\moodle\Traits\Connector;
use Drupal\moodle\Connector;
use Drupal\moodle\Sql\Courses;
use Drupal\moodle\Sql\CurrentUser;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Course list' block.
 *
 * @Block(
 *   id = "moodle_course_list_block",
 *   admin_label = @Translation("Moodle Courses for current user."),
 *   category = @Translation("Moodle")
 * )
 */
class CourseListBlock extends BlockBase implements ContainerFactoryPluginInterface {
//  use Connector;

  /**
   * The Current User object.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * @var \Drupal\moodle\Sql\CurrentUser
   */
  protected $moodleCurrentUser;

  protected $courses;

  /**
   * The corresponding database connection object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connector;

  /**
   * @inheritDoc
   */
//  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $login_factory, ConfigFactoryInterface $config_factory, AccountInterface $current_user) {
  // , Connector $connector, AccountInterface $current_user
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Courses $courses) {
parent::__construct($configuration, $plugin_id, $plugin_definition);
//    $this->loggerFactory = $login_factory;
//    $this->config = $config_factory;
//    $this->currentUser = $current_user;
//    $this->connector = $connector->connect();
//    $this->moodleCurrentUser = $moodle_current_user;
    $this->courses = $courses;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
//      $container->get('moodle.connector'),
//      $container->get('logger.factory'),
//      $container->get('config.factory'),
//      $container->get('current_user')
//      $container->get('moodle.current_user')
      $container->get('moodle.user_courses')
    );
  }

  /**
   * @inheritDoc
   */
  public function build() {
//dsm(get_class_methods($this->connect()));
//dsm(get_class_methods($this->currentUser));
//    dsm($this->currentUser->getAccountName());
//    dsm(get_class($this->connect()));
    // Get Moodle user id.
//    $moodle_user_id = $this->connect()->query("SELECT id FROM {user} WHERE username = :username", array(
//      ':username' => $this->currentUser->getAccountName(),
//    ))->fetchField();
//
//    dsm($moodle_user_id);

//    $query = $this->connector->select('user', 'u', array('fetch' => \PDO::FETCH_ASSOC));
//    $query->addField('u', 'id');
//    $moodle_user_id =  $query->condition('u.username', $this->currentUser->getAccountName())
//      ->execute()->fetchField();
//
//    dsm($moodle_user_id);
//    dsm($this->moodleCurrentUser->id());
//dsm($this->moodleCurrentUser->user()->id);

    $this->courses->query();

    $query = "SELECT {course}.id, {course}.fullname
            FROM {course}
              INNER JOIN {context} ON {course}.id = {context}.instanceid
                AND {context}.contextlevel = :contextlevel
              INNER JOIN {role_assignments} ON {context}.id = {role_assignments}.contextid
              INNER JOIN {role} ON {role_assignments}.roleid = {role}.id
              INNER JOIN {user} ON {role_assignments}.userid = {user}.id
            WHERE {user}.id = :userid";

    return array(
      '#markup' => 'bona moodle course coming @todo',

    );
  }

  /**
   * @inheritDoc
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'view moodle course list');
  }

}
