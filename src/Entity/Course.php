<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 21/02/16
 * Time: 12:38
 */

namespace Drupal\moodle\Entity;


use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\moodle\MoodleEntityBase;

/**
 * Defines the user entity class.
 *
 * The base table name here is plural, despite Drupal table naming standards,
 * because "user" is a reserved word in many databases.
 *
 * @ContentEntityType(
 *   id = "moodle_course",
 *   label = @Translation("Moodle Course"),
 *   handlers = {
 *     "storage" = "Drupal\moodle\MoodleStorage",
 *     "storage_schema" = "Drupal\moodle\MoodleStorageSchema",
 *     "access" = "Drupal\user\UserAccessControlHandler",
 *     "list_builder" = "Drupal\moodle\CourseListBuilder",
 *     "views_data" = "Drupal\moodle\MoodleViewsData",
 *     "route_provider" = {
 *       "html" = "Drupal\moodle\Route\CourseProvider",
 *     },
 *     "form" = {
 *       "default" = "Drupal\moodle\Form\CourseForm",
 *     },
 *   },
 *   admin_permission = "administer users",
 *   base_table = "course",
 *   translatable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "fullname",
 *   },
 *   links = {
 *     "canonical" = "/course/{moodle_course}",
 *     "collection" = "/admin/courses",
 *   },
 *   common_reference_target = TRUE
 * )
 */
class Course extends MoodleEntityBase {

  /**
   * @return mixed
   */
  public function getCategoryInstance() {
    $category = NULL;
    $cat_id = $this->get('category')->value;

    if ($cat_id) {
      $category = $this->entityTypeManager()->getStorage('moodle_category');
      $category = $category->load($cat_id);
    }
    return $category;
  }

  /**
   * @inheritDoc
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Course ID'))
      ->setDescription(t('The course ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['fullname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Full name'))
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setRevisionable(FALSE)
      ->setDefaultValue('')
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -5,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['shortname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Short name'))
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setRevisionable(FALSE)
      ->setDefaultValue('')
      ->setSetting('max_length', 255);

    $fields['timemodified'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the course was last edited.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE);

    $fields['category'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Category'))
      ->setDescription(t('The category ID in which the course belongs.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE);

    return $fields;
  }

}
