<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 21/02/16
 * Time: 20:09
 */

namespace Drupal\moodle\Query;

use Drupal\Core\Entity\Query\Sql\QueryAggregate as CoreQueryAggregate;

/**
 * Class QueryAggregate
 *
 *   This class's only resposibility is to make sure that the
 *   coreQueryAggreagate
 *   functionality exists in the moodle namespace.
 *   In the future methods could be overiden specifically for the moodle
 *   external database.
 *   @see Drupal\moodle\Query\QueryFactory where i set the query namespace to
 *   be that of this module.
 *
 * @package Drupal\moodle\Query
 */
class QueryAggregate extends CoreQueryAggregate {

}
