<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 21/02/16
 * Time: 12:58
 */

namespace Drupal\moodle;


use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Database;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\ContentEntityStorageBase;
use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MoodleStorage
 *   This is the storage engine for the moodle courses.
 *   It is resposible for querying the moodle database.
 *
 * @package Drupal\moodle
 */
class MoodleStorage extends SqlContentEntityStorage {

  public function __construct(EntityTypeInterface $entity_type, Connector $moodle_connector, EntityManagerInterface $entity_manager, CacheBackendInterface $cache, LanguageManagerInterface $language_manager) {
    $database = $moodle_connector->connect();
    parent::__construct($entity_type, $database, $entity_manager, $cache, $language_manager);
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('moodle.connector'),
      $container->get('entity.manager'),
      $container->get('cache.entity'),
      $container->get('language_manager')
    );
  }

  /**
   * @inheritDoc
   */
  protected function getQueryServiceName() {
    return 'entity.query.moodle';
  }

  /**
   * @inheritDoc
   */
  protected function doDelete($entities) {
    return [];
  }

  /**
   * @inheritDoc
   */
  protected function readFieldItemsToPurge(FieldDefinitionInterface $field_definition, $batch_size) {
    return [];
  }

  /**
   * @inheritDoc
   */
  protected function purgeFieldItems(ContentEntityInterface $entity, FieldDefinitionInterface $field_definition) {
    // This is overiden because it is not applicable to
    // the moodle courses in the external database.
  }

  /**
   * @inheritDoc
   */
  protected function doLoadRevisionFieldItems($revision_id) {
    // This is overiden because it is not applicable to
    // the moodle courses in the external database.
  }

  /**
   * @inheritDoc
   */
  protected function doDeleteFieldItems($entities) {
    // This is overiden because it is not applicable to
    // the moodle courses in the external database.
  }

  /**
   * @inheritDoc
   */
  protected function doDeleteRevisionFieldItems(ContentEntityInterface $revision) {
    // This is overiden because it is not applicable to
    // the moodle courses in the external database.
  }

  /**
   * @inheritDoc
   */
  protected function doSaveFieldItems(ContentEntityInterface $entity, array $names = []) {
    // This is overiden because it is not applicable to
    // the moodle courses in the external database.
  }

  /**
   * @inheritDoc
   */
  protected function has($id, EntityInterface $entity) {
    // TODO: Implement has() method.
  }

  /**
   * @inheritDoc
   */
  public function countFieldData($storage_definition, $as_bool = FALSE) {
    // This is overiden because it is not applicable to
    // the moodle courses in the external database.
  }

}
