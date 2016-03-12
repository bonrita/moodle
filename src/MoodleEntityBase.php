<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 12/03/16
 * Time: 19:56
 */

namespace Drupal\moodle;

use Drupal\Core\Entity\ContentEntityBase;

/**
 * Class MoodleEntityBase
 *   Provides common functionalities common to moodle entities.
 *
 * @package Drupal\moodle
 */
class MoodleEntityBase extends ContentEntityBase {

  /**
   * @inheritDoc
   */
  public function delete() {
    // This is overiden because i prevent drupal from deleting courses
    // from the moodle database.
    // This may occur for example when uninstalling a module that provides
    // entites and in this case i have moodle entities.
    // @see : Drupal\Core\Extension\ModuleInstaller::uninstall
  }

}
