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
use Drupal\Core\Session\AccountInterface;
use Drupal\moodle\Sql\Model\User;

/**
 * Class CurrentUser
 *   The class retrieves the moodle user object related to the current
 *   logged in drupal user.
 *
 * @package Drupal\moodle\Sql
 */
class CurrentUser extends Base {

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $user;

  /**
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheBackend;

  /**
   * @inheritDoc
   */
  public function __construct(ConfigFactoryInterface $config, LoggerChannelFactoryInterface $logger_factory, AccountInterface $current_user, CacheBackendInterface $cache_backend) {
    parent::__construct($config, $logger_factory);
    $this->user = $current_user;
    $this->cacheBackend = $cache_backend;
  }

  /**
   * @inheritDoc
   */
  public function query() {
    $this->query = $this->getDatabase()
      ->select('user', 'u', array('fetch' => \PDO::FETCH_ASSOC));
    $this->query->condition('u.username', $this->user->getAccountName());
    return $this->query;
  }

  /**
   * User object.
   *
   * @return \Drupal\moodle\Sql\User
   */
  public function user() {
    // Static cache of already retrieved user data.
    $data = &drupal_static(__METHOD__, array());
    $user_cid = "moodle-user:{$this->user->id()}";

    // If we do not have this user id in the static cache, check {cache_data}.
    if (!isset($data[$user_cid])) {
      $cache = $this->cacheBackend->get($user_cid);
      if ($cache && $cache->data && isset($cache->data[$user_cid])) {
        $data[$user_cid] = $cache->data[$user_cid];
      }
    }

    // If nothing in the cache then retrieve it from the database.
    if (!isset($data[$user_cid])) {
      $user = new User();

      $this->query();
      $this->addFields();
      $statement = $this->query->execute();
      $statement->setFetchMode(\PDO::FETCH_INTO, $user);

      $data[$user_cid] = $statement->fetch();

      // Store the results for a day.
      $this->cacheBackend->set($user_cid, $data, (REQUEST_TIME+86400));
    }

    return $data[$user_cid];
  }

  /**
   * Add the fields you want to retrieve from the database.
   */
  protected function addFields() {
    $this->query->addField('u', 'id');
    $this->query->addField('u', 'auth');
    $this->query->addField('u', 'username');
    $this->query->addField('u', 'firstname');
    $this->query->addField('u', 'lastname');
    $this->query->addField('u', 'email');
    $this->query->addField('u', 'city');
    $this->query->addField('u', 'country');
    $this->query->addField('u', 'lang');
  }

}
