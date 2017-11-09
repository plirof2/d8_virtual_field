<?php

namespace Drupal\virtual_datetime\Tests\Views;

@trigger_error('\Drupal\virtual_datetime\Tests\Views\Virtual_DateTimeHandlerTestBase is deprecated in Drupal 8.4.0 and will be removed before Drupal 9.0.0. Instead, use \Drupal\Tests\BrowserTestBase', E_USER_DEPRECATED);

use Drupal\virtual_datetime\Plugin\Field\FieldType\Virtual_DateTimeItem;
use Drupal\field\Entity\FieldConfig;
use Drupal\node\Entity\NodeType;
use Drupal\views\Tests\Handler\HandlerTestBase;
use Drupal\views\Tests\ViewTestData;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Base class for testing virtual_datetime handlers.
 *
 * @deprecated in Drupal 8.4.0 and will be removed before Drupal 9.0.0.
 *   Use \Drupal\Tests\BrowserTestBase.
 */
abstract class Virtual_DateTimeHandlerTestBase extends HandlerTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['virtual_datetime_test', 'node', 'virtual_datetime'];

  /**
   * Name of the field.
   *
   * Note, this is used in the default test view.
   *
   * @var string
   */
  protected static $field_name = 'field_date';

  /**
   * Nodes to test.
   *
   * @var \Drupal\node\NodeInterface[]
   */
  protected $nodes = [];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Add a date field to page nodes.
    $node_type = NodeType::create([
      'type' => 'page',
      'name' => 'page'
    ]);
    $node_type->save();
    $fieldStorage = FieldStorageConfig::create([
      'field_name' => static::$field_name,
      'entity_type' => 'node',
      'type' => 'virtual_datetime',
      'settings' => ['virtual_datetime_type' => Virtual_DateTimeItem::VIRTUAL_DATETIME_TYPE_VIRTUAL_DATETIME],
    ]);
    $fieldStorage->save();
    $field = FieldConfig::create([
      'field_storage' => $fieldStorage,
      'bundle' => 'page',
      'required' => TRUE,
    ]);
    $field->save();

    // Views needs to be aware of the new field.
    $this->container->get('views.views_data')->clear();

    // Set column map.
    $this->map = [
      'nid' => 'nid',
    ];

    // Load test views.
    ViewTestData::createTestViews(get_class($this), ['virtual_datetime_test']);
  }

}
