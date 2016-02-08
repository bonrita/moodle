<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 06/02/16
 * Time: 00:22
 */

namespace Drupal\moodle\Sql\Model;

/**
 * Class Course
 *
 * @see http://php.net/manual/en/pdostatement.setfetchmode.php#62048
 *
 * @package Drupal\moodle\Sql\Model
 */
class Course {

  /**
   * Storage of table fields.
   * @var array
   */
  protected $cols;

  /**
   * Dynamically retrieve data of the specified field.
   *
   * @param $name
   *   Name of a table field.
   *
   * @return mixed
   */
  public function __get($name) {
    return $this->cols[$name];
  }

}