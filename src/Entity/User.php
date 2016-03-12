<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 02/03/16
 * Time: 22:26
 */

namespace Drupal\moodle\Entity;


use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\moodle\MoodleEntityBase;

/**
 * Defines the moodle_user entity class.
 *
 * @ContentEntityType(
 *   id = "moodle_user",
 *   label = @Translation("User"),
 *   handlers = {
 *     "storage" = "Drupal\moodle\MoodleStorage",
 *     "storage_schema" = "Drupal\moodle\MoodleStorageSchema",
 *     "access" = "Drupal\user\UserAccessControlHandler",
 *   },
 *   admin_permission = "administer users",
 *   base_table = "user",
 *   translatable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "username",
 *   },
 *   links = {
 *   },
 *   common_reference_target = TRUE
 * )
 */
class User extends MoodleEntityBase {

  public function getName(){
    return $this->get('username')->value;
  }

  /**
   * @inheritDoc
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('User ID'))
      ->setDescription(t('The user ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['username'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Username'))
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setRevisionable(FALSE)
      ->setDefaultValue('')
      ->setSetting('max_length', 255);

    return $fields;
  }

}
