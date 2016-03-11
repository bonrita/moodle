<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 23/02/16
 * Time: 20:07
 */

namespace Drupal\moodle\Route;


use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Provides routes for courses.
 *
 * Class CourseProvider
 * @package Drupal\moodle\Route
 */
class CourseProvider implements EntityRouteProviderInterface {

  /**
   * @inheritDoc
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $route_collection = new RouteCollection();
    $route = (new Route('/course/{moodle_course}'))
      ->addDefaults([
        '_controller' => '\Drupal\moodle\Controller\CourseViewController::view',
        '_title_callback' => '\Drupal\node\Controller\CourseViewController::title',
      ])
      ->setRequirement('moodle_course', '\d+');
//      ->setRequirement('_entity_access', 'course.view');
    $route_collection->add('entity.moodle_course.canonical', $route);

    return $route_collection;
  }

}
