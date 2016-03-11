<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 21/02/16
 * Time: 13:59
 */

namespace Drupal\moodle;


use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Language\LanguageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a class to build a listing of course entities.
 *
 * Class CourseListBuilder
 * @package Drupal\moodle
 */
class CourseListBuilder extends EntityListBuilder {

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * @inheritDoc
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatterInterface $date_formatter) {
    parent::__construct($entity_type, $storage);

    $this->dateFormatter = $date_formatter;
  }

  /**
   * @inheritDoc
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager')->getStorage($entity_type->id()),
      $container->get('date.formatter')
    );
  }

  /**
   * @inheritDoc
   */
  public function buildHeader() {
    // Enable language column and filter if multiple languages are added.
    $header = array(
      'fullname'     => $this->t('Full name'),
      'shortname'    => array(
        'data'  => $this->t('Short name'),
        'class' => array(RESPONSIVE_PRIORITY_LOW),
      ),
      'category'     => array(
        'data'  => $this->t('Category'),
        'class' => array(RESPONSIVE_PRIORITY_LOW),
      ),
      'timemodified' => array(
        'data'  => $this->t('Updated'),
        'class' => array(RESPONSIVE_PRIORITY_LOW),
      ),
    );
    if (\Drupal::languageManager()->isMultilingual()) {
      $header['lang'] = array(
        'data'  => $this->t('Language'),
        'class' => array(RESPONSIVE_PRIORITY_LOW),
      );
    }
    return $header;
  }

  /**
   * @inheritDoc
   */
  public function buildRow(EntityInterface $entity) {
    $langcode = $entity->language()->getId();
    $uri = $entity->urlInfo();
    $options = $uri->getOptions();
    $options += ($langcode != LanguageInterface::LANGCODE_NOT_SPECIFIED && isset($languages[$langcode]) ? array('language' => $languages[$langcode]) : array());
    $uri->setOptions($options);
    $row['fullname']['data'] = array(
      '#type'  => 'link',
      '#title' => $entity->label(),
      '#url'   => $uri,
    );

    $row['shortname']['data'] = array(
      '#markup' => $entity->get('shortname')->value,
    );

    $row['category']['data'] = array(
      '#markup' => $entity->getCategoryInstance()? $entity->getCategoryInstance()->get('name')->value: '',
    );

    $row['timemodified']['data'] = array(
      '#markup' => $this->formatDate($entity, 'timemodified', 'short'),
    );

    return $row;
  }

  /**
   * Format date.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $field_name
   * @param string $format
   */
  protected function formatDate(EntityInterface $entity, $field_name, $format) {
    $timestamp = $entity->get($field_name)->value;
    return $this->dateFormatter->format($timestamp, $format);
  }

}
