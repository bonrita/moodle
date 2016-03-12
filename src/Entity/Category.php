<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 23/02/16
 * Time: 22:13
 */

namespace Drupal\moodle\Entity;


use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\moodle\MoodleEntityBase;

/**
 * Defines the moodle_category entity class.
 *
 * @ContentEntityType(
 *   id = "moodle_category",
 *   label = @Translation("Category"),
 *   handlers = {
 *     "storage" = "Drupal\moodle\MoodleStorage",
 *     "storage_schema" = "Drupal\moodle\MoodleStorageSchema",
 *     "access" = "Drupal\user\UserAccessControlHandler",
 *   },
 *   admin_permission = "administer users",
 *   base_table = "course_categories",
 *   translatable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *   },
 *   links = {
 *   },
 *   common_reference_target = TRUE
 * )
 */
class Category extends MoodleEntityBase {

  public function getName(){
    return $this->get('name')->value;
  }

  /**
   * @inheritDoc
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Category ID'))
      ->setDescription(t('The category ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setRevisionable(FALSE)
      ->setDefaultValue('')
      ->setSetting('max_length', 255);

    return $fields;
  }

}
