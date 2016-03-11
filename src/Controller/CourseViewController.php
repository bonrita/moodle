<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 23/02/16
 * Time: 20:18
 */

namespace Drupal\moodle\Controller;

use Drupal\Core\Entity\Controller\EntityViewController;
use Drupal\Core\Entity\EntityInterface;

/**
 * Defines a controller to render a single course.
 *
 * Class CourseViewController
 * @package Drupal\moodle\Controller
 */
class CourseViewController extends EntityViewController {

  /**
   * @inheritDoc
   */
  public function view(EntityInterface $_entity, $view_mode = 'full') {
    return parent::view($_entity, $view_mode);
  }

  /**
   * The _title_callback for the page that renders a single course.
   *
   * @param \Drupal\Core\Entity\EntityInterface $course
   *   The current course.
   *
   * @return string
   *   The page title.
   */
  public function title(EntityInterface $course) {
    return $this->entityManager->getTranslationFromContext($course)->label();
  }

}
