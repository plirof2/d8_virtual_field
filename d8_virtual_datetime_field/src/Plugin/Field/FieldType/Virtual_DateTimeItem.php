<?php

namespace Drupal\virtual_datetime\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldItemBase;
 //*   default_widget = "virtual_datetime_default",
 //*   default_formatter = "virtual_datetime_default",
/**
 * Plugin implementation of the 'virtual_datetime' field type.
 *
 * @FieldType(
 *   id = "virtual_datetime",
 *   label = @Translation("Date"),
 *   description = @Translation("Create and store date values."),
 *   default_widget = "datetime_default",
 *   default_formatter = "datetime_default",
 *   list_class = "\Drupal\virtual_datetime\Plugin\Field\FieldType\Virtual_DateTimeFieldItemList",
 *   constraints = {"Virtual_DateTimeFormat" = {}}
 * )
 */
class Virtual_DateTimeItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'virtual_datetime_type' => 'virtual_datetime',
    ] + parent::defaultStorageSettings();
  }

  /**
   * Value for the 'virtual_datetime_type' setting: store only a date.
   */
  const VIRTUAL_DATETIME_TYPE_DATE = 'date';

  /**
   * Value for the 'virtual_datetime_type' setting: store a date and time.
   */
  const VIRTUAL_DATETIME_TYPE_VIRTUAL_DATETIME = 'virtual_datetime';

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('datetime_iso8601')
      ->setLabel(t('Date value'))
      ->setRequired(TRUE);

    $properties['date'] = DataDefinition::create('any')
      ->setLabel(t('Computed date'))
      ->setDescription(t('The computed Virtual_DateTime object.'))
      ->setComputed(TRUE)
      ->setClass('\Drupal\virtual_datetime\Virtual_DateTimeComputed')
      ->setSetting('date source', 'value');

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'description' => 'The date value.',
          'type' => 'varchar',
          'length' => 20,
        ],
      ],
      'indexes' => [
        'value' => ['value'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = [];

    $element['virtual_datetime_type'] = [
      '#type' => 'select',
      '#title' => t('Date type'),
      '#description' => t('Choose the type of date to create.'),
      '#default_value' => $this->getSetting('virtual_datetime_type'),
      '#options' => [
        static::VIRTUAL_DATETIME_TYPE_VIRTUAL_DATETIME => t('Date and time'),
        static::VIRTUAL_DATETIME_TYPE_DATE => t('Date only'),
      ],
      '#disabled' => $has_data,
    ];
    $element=null; //ADDED By JON
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $type = $field_definition->getSetting('virtual_datetime_type');

    // Just pick a date in the past year. No guidance is provided by this Field
    // type.
    $timestamp = REQUEST_TIME - mt_rand(0, 86400 * 365);
    if ($type == Virtual_DateTimeItem::VIRTUAL_DATETIME_TYPE_DATE) {
      $values['value'] = gmdate(VIRTUAL_DATETIME_DATE_STORAGE_FORMAT, $timestamp);
    }
    else {
      $values['value'] = gmdate(VIRTUAL_DATETIME_VIRTUAL_DATETIME_STORAGE_FORMAT, $timestamp);
    }
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    //always return YES to avoid writing to DB
    //$value = $this->get('value')->getValue();
    //return $value === NULL || $value === '';
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function onChange($property_name, $notify = TRUE) {
    // Enforce that the computed date is recalculated.
    if ($property_name == 'value') {
      $this->date = NULL;
    }
    parent::onChange($property_name, $notify);
  }

}
