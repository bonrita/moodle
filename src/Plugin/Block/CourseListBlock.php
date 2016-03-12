<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 02/02/16
 * Time: 22:37
 *
 * This functionality has been suspended for further development.
 * This is because i have now intergrated views and one can use views to
 * create a block display.
 */

namespace Drupal\moodle\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
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

  /**
   * @var \Drupal\moodle\Sql\Courses
   */
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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Courses $courses) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
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
      $container->get('moodle.user_courses')
    );
  }

  /**
   * @inheritDoc
   */
  public function build() {
    return array(
      '#markup' => '@todo: Moodle courses coming soon.',

    );
  }

  /**
   * @inheritDoc
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'view moodle course list');
  }

}
