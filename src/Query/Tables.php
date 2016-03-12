<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 23/02/16
 * Time: 06:34
 */

namespace Drupal\moodle\Query;

use Drupal\Core\Entity\Query\Sql\Tables as CoreTables;

/**
 * Class Tables
 *
 *   This class's only resposibility is to make sure that the coreTables
 *   functionality exists in the moodle namespace.
 *   In the future methods could be overiden specifically for the moodle
 *   external database.
 *   @see Drupal\moodle\Query\QueryFactory where i set the query namespace to
 *   be that of this module.
 *
 * @package Drupal\moodle\Query
 */
class Tables extends CoreTables {

}
