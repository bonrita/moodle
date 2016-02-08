<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 04/02/16
 * Time: 23:06
 */

namespace Drupal\moodle\Sql;


use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\moodle\Sql\Model\Course;
use Drupal\moodle\Sql\Model\User;

class Courses extends Base {

  /**
   * @var \Drupal\moodle\Sql\CurrentUser
   */
  protected $user;

  protected  $courses;

  /**
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheBackend;

  /**
   * Connector constructor.
   */
  public function __construct(ConfigFactoryInterface $config, LoggerChannelFactoryInterface $logger_factory, CurrentUser $user, CacheBackendInterface $cache_backend) {
    parent::__construct($config, $logger_factory);
    $this->user = $user;
    $this->cacheBackend = $cache_backend;
  }

  /**
   * @inheritDoc
   */
  public function query() {
    $this->query = $this->getDatabase()
      ->select('course', 'cos', array('fetch' => \PDO::FETCH_ASSOC));
    $this->query->innerJoin('context', 'ctxt', 'cos.id = ctxt.instanceid');
    $this->query->innerJoin('role_assignments', 'ra', 'ra.contextid = ctxt.id');
    $this->query->innerJoin('role', 'r', 'r.id = ra.roleid');
    $this->query->innerJoin('user', 'u', 'u.id = ra.userid');

    $this->query->condition('u.id', $this->user->user()->id);
    $this->query->condition('ctxt.contextlevel', 50);
    return $this;
  }

  /**
   * @return array
   */
  public function getCourses() {
    $this->query();
    $this->query->fields('cos');

    $statement = $this->query->execute();

//    $course = new Course();
    $statement->setFetchMode(\PDO::FETCH_INTO, new Course());
//    $statement->fetchAll();
//    dsm($statement->fetchAllAssoc('id', \PDO::FETCH_INTO,new Course() ));

    foreach ($statement as $object) {
      $this->courses[$object->id] = clone $object;
    }
    dsm($this->courses);
    dsm($this->courses[2]->id);
    return $this->courses;
  }

}