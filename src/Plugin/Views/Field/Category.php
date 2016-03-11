<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 26/02/16
 * Time: 15:33
 */

namespace Drupal\moodle\Plugin\Views\Field;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Field handler to provide a category.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("moodle_category")
 */
class Category extends FieldPluginBase {

  /**
   * The moodle category entity storage.
   * @var \Drupal\Core\Entity\Sql\SqlContentEntityStorage
   */
  protected $entityStorage;

  /**
   * @inheritDoc
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SqlContentEntityStorage $entity_storage) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityStorage = $entity_storage;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id, $plugin_definition,
      $container->get('entity.manager')->getStorage('moodle_category')
    );
  }

  /**
   * @inheritDoc
   */
  public function getValue(ResultRow $values, $field = NULL) {
    $value = NULL;
    $cat_id = parent::getValue($values, $field);

    if ($cat_id) {
      $value = $this->entityStorage->load($cat_id)->getName();
    }

    return $value;
  }

}
