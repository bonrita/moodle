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

class MoodleStorage extends SqlContentEntityStorage {

  public function __construct(EntityTypeInterface $entity_type, Connector $moodle_connector, EntityManagerInterface $entity_manager, CacheBackendInterface $cache, LanguageManagerInterface $language_manager) {
    $database = $moodle_connector->connect();
    parent::__construct($entity_type, $database, $entity_manager, $cache, $language_manager);
//    parent::__construct($entity_type, $entity_manager, $cache);
//Database::getConnection('moodle', 'default');
//    var_dump($this->database->getConnectionOptions());
//    var_dump($this->getQueryServiceName());
//    exit;
//    $this->database =  $moodle_connector->connect();
//    $this->languageManager = $language_manager;
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
  }

  /**
   * @inheritDoc
   */
  protected function doLoadRevisionFieldItems($revision_id) {
  }

  /**
   * @inheritDoc
   */
  protected function doDeleteFieldItems($entities) {
  }

  /**
   * @inheritDoc
   */
  protected function doDeleteRevisionFieldItems(ContentEntityInterface $revision) {
  }

  /**
   * @inheritDoc
   */
  protected function doSaveFieldItems(ContentEntityInterface $entity, array $names = []) {
  }

  /**
   * @inheritDoc
   */
//  protected function doLoadMultiple(array $ids = NULL) {
//    // TODO: Implement doLoadMultiple() method.
//    $entities_from_storage = $this->getFromStorage($ids);
//    $entities = array();
//  }

  /**
   * @inheritDoc
   */
  protected function has($id, EntityInterface $entity) {
    // TODO: Implement has() method.
    $bb = [];
  }

  /**
   * @inheritDoc
   */
  public function countFieldData($storage_definition, $as_bool = FALSE) {
    // TODO: Implement countFieldData() method.
    $bb = [];
  }

  /**
   * @inheritDoc
   */
  public function loadMultiple(array $ids = NULL) {
    return parent::loadMultiple($ids); // TODO: Change the autogenerated stub
  }

  /**
   * Gets entities from the storage.
   *
   * @param array|null $ids
   *   If not empty, return entities that match these IDs. Return all entities
   *   when NULL.
   *
   * @return \Drupal\Core\Entity\ContentEntityInterface[]
   *   Array of entities from the storage.
   */
//  protected function getFromStorage(array $ids = NULL) {
//    $entities = array();
//
//    if (!empty($ids)) {
//      // Sanitize IDs. Before feeding ID array into buildQuery, check whether
//      // it is empty as this would load all entities.
//      $ids = $this->cleanIds($ids);
//    }
//
//    if ($ids === NULL || $ids) {
//      // Build and execute the query.
//      $query_result = $this->buildQuery($ids)->execute();
//      $records = $query_result->fetchAllAssoc($this->idKey);
//
//      // Map the loaded records into entity objects and according fields.
//      if ($records) {
//       // $entities = $this->mapFromStorageRecords($records);
//      }
//    }
//
//    return $entities;
//  }

  /**
   * Builds the query to load the entity.
   *
   * This has full revision support. For entities requiring special queries,
   * the class can be extended, and the default query can be constructed by
   * calling parent::buildQuery(). This is usually necessary when the object
   * being loaded needs to be augmented with additional data from another
   * table, such as loading node type into comments or vocabulary machine name
   * into terms, however it can also support $conditions on different tables.
   * See Drupal\comment\CommentStorage::buildQuery() for an example.
   *
   * @param array|null $ids
   *   An array of entity IDs, or NULL to load all entities.
   * @param $revision_id
   *   The ID of the revision to load, or FALSE if this query is asking for the
   *   most current revision(s).
   *
   * @return \Drupal\Core\Database\Query\Select
   *   A SelectQuery object for loading the entity.
   */
//  protected function buildQuery($ids, $revision_id = FALSE) {
//    $query = $this->database->select($this->entityType->getBaseTable(), 'base');
//
//    $query->addTag($this->entityTypeId . '_load_multiple');
//
//    if ($revision_id) {
//      $query->join($this->revisionTable, 'revision', "revision.{$this->idKey} = base.{$this->idKey} AND revision.{$this->revisionKey} = :revisionId", array(':revisionId' => $revision_id));
//    }
//    elseif ($this->revisionTable) {
//      $query->join($this->revisionTable, 'revision', "revision.{$this->revisionKey} = base.{$this->revisionKey}");
//    }
//
//    // Add fields from the {entity} table.
//    $table_mapping = $this->getTableMapping();
//    $entity_fields = $table_mapping->getAllColumns($this->baseTable);
//
//    if ($this->revisionTable) {
//      // Add all fields from the {entity_revision} table.
//      $entity_revision_fields = $table_mapping->getAllColumns($this->revisionTable);
//      $entity_revision_fields = array_combine($entity_revision_fields, $entity_revision_fields);
//      // The ID field is provided by entity, so remove it.
//      unset($entity_revision_fields[$this->idKey]);
//
//      // Remove all fields from the base table that are also fields by the same
//      // name in the revision table.
//      $entity_field_keys = array_flip($entity_fields);
//      foreach ($entity_revision_fields as $name) {
//        if (isset($entity_field_keys[$name])) {
//          unset($entity_fields[$entity_field_keys[$name]]);
//        }
//      }
//      $query->fields('revision', $entity_revision_fields);
//
//      // Compare revision ID of the base and revision table, if equal then this
//      // is the default revision.
//      $query->addExpression('CASE base.' . $this->revisionKey . ' WHEN revision.' . $this->revisionKey . ' THEN 1 ELSE 0 END', 'isDefaultRevision');
//    }
//
//    $query->fields('base', $entity_fields);
//
//    if ($ids) {
//      $query->condition("base.{$this->idKey}", $ids, 'IN');
//    }
//
//    return $query;
//  }


}
