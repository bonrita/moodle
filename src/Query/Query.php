<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 21/02/16
 * Time: 20:04
 */

namespace Drupal\moodle\Query;

use Drupal\Core\Entity\Query\Sql\Query as CoreQuery;

/**
 * Class Query
 *
 *   This class's only resposibility is to make sure that the coreQuery
 *   functionality exists in the moodle namespace.
 *   In the future methods could be overiden specifically for the moodle
 *   external database.
 *   @see Drupal\moodle\Query\QueryFactory where i set the query namespace to
 *   be that of this module.
 *
 * @package Drupal\moodle\Query
 */
class Query extends CoreQuery {

}
