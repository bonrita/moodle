<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 25/02/16
 * Time: 06:57
 */

namespace Drupal\moodle;


use Drupal\views\EntityViewsData;

/**
 * Provides the views data for the moodle entity types.
 */
class MoodleViewsData extends EntityViewsData {

  /**
   * @inheritDoc
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $base_table = $this->entityType->getBaseTable() ?: $this->entityType->id();
    $data[$base_table]['table']['base']['database'] = 'moodle';

    $data[$base_table]['category']['help'] = t('The moodle course category in which the course belongs. If you need more fields than the category ID add the Moodle: category relationship');
    $data[$base_table]['category']['filter']['id'] = 'moodle_category';

    $data['course_categories']['table']['group']  = t('Moodle catogories');

    $data['course_categories']['table']['join'] = array(
      'course' => array(
        'left_field' => 'category',
        'field' => 'id',
      ),
    );

    $data['course_categories']['id'] = array(
      'title' => t('Categories'),
      'help' => t('Category in which a course belongs to.'),
      'field' => array(
        'id' => 'moodle_category',
      ),
    );

    $data[$base_table]['course_uid'] = array(
      'help' => t('Display courses of a user.'),
      'real field' => 'id',
      'argument' => array(
        'title' => t('Course user ID'),
        'id' => 'moodle_user_uid',
      ),
    );

    return $data;
  }

}
