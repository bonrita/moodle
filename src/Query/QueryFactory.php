<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 21/02/16
 * Time: 19:44
 */

namespace Drupal\moodle\Query;


use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Query\QueryBase;
use Drupal\Core\Entity\Query\QueryFactoryInterface;
use Drupal\moodle\Connector;

/**
 * Provides a factory for creating the moodle entity queries.
 */
class QueryFactory implements QueryFactoryInterface {

  /**
   * The database connection to use.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The namespace of this class, the parent class etc.
   *
   * @var array
   */
  protected $namespaces;

  /**
   * Constructs a QueryFactory object.
   *
   */
  public function __construct(Connector $connector) {
    $this->namespaces = QueryBase::getNamespaces($this);
    $this->connection = $connector->connect();
  }

  /**
   * @inheritDoc
   */
  public function get(EntityTypeInterface $entity_type, $conjunction) {
    $class = QueryBase::getClass($this->namespaces, 'Query');
    return new $class($entity_type, $conjunction, $this->connection, $this->namespaces);
  }

  /**
   * @inheritDoc
   */
  public function getAggregate(EntityTypeInterface $entity_type, $conjunction) {
    $class = QueryBase::getClass($this->namespaces, 'QueryAggregate');
    return new $class($entity_type, $conjunction, $this->connection, $this->namespaces);
  }

}
