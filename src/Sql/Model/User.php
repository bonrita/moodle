<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 05/02/16
 * Time: 15:56
 */

namespace Drupal\moodle\Sql\Model;

/**
 * Class User
 *   This class maps to the moodle user object 'table'.
 *
 * @package Drupal\moodle\Sql
 */
class User {

  public $id;
  public $auth;
  public $username;
  public $firstname;
  public $lastname;
  public $email;
  public $city;
  public $country;
  public $lang;

}
