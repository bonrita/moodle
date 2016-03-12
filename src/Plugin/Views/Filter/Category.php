<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 27/02/16
 * Time: 08:08
 */

namespace Drupal\moodle\Plugin\Views\Filter;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\filter\InOperator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Filter handler for categories.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("moodle_category")
 */
class Category extends InOperator {


  /**
   * Disable the possibility to force a single value.
   * @var bool
   */
  protected $alwaysMultiple = TRUE;

  /**
   * The term storage.
   *
   * @var \Drupal\moodle\MoodleStorage
   */
  protected $categoryStorage;

  /**
   * @inheritDoc
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SqlContentEntityStorage $category_storage) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->categoryStorage = $category_storage;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')->getStorage('moodle_category')
    );
  }

  /**
   * @inheritDoc
   */
  public function getValueOptions() {

    foreach ($this->categoryStorage->loadByProperties() as $id => $category) {
      $this->valueOptions[$id] = $category->getName();
    }

    return parent::getValueOptions();
  }

  /**
   * Add this filter to the query.
   *
   * Due to the nature of fapi, the value and the operator have an unintended
   * level of indirection. You will find them in $this->operator
   * and $this->value respectively.
   */
  public function query() {

    // Because this handler thinks it's an argument for a field on the {course}
    // table, we need to make sure {course_categories} is JOINed and use its
    // alias for the WHERE clause.
    $course_categories_alias = $this->query->ensureTable('course_categories');

    // Cast scalars to array so we can consistently use an IN condition.
    $this->query->addWhere($this->options['group'], "$course_categories_alias.id", (array) $this->value, 'IN');
  }

}
