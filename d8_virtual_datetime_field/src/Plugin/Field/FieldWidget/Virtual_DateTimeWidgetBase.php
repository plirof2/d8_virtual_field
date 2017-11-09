<?php

namespace Drupal\virtual_datetime\Plugin\Field\FieldWidget;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\virtual_datetime\Plugin\Field\FieldType\Virtual_DateTimeItem;

/**
 * Base class for the 'virtual_datetime_*' widgets.
 */
class Virtual_DateTimeWidgetBase extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['value'] = [
      '#type' => 'virtual_datetime',
      '#default_value' => NULL,
      '#date_increment' => 1,
      '#date_timezone' => drupal_get_user_timezone(),
      '#required' => $element['#required'],
    ];

    if ($this->getFieldSetting('virtual_datetime_type') == Virtual_DateTimeItem::VIRTUAL_DATETIME_TYPE_DATE) {
      // A date-only field should have no timezone conversion performed, so
      // use the same timezone as for storage.
      $element['value']['#date_timezone'] = VIRTUAL_DATETIME_STORAGE_TIMEZONE;
    }

    if ($items[$delta]->date) {
      $date = $items[$delta]->date;
      // The date was created and verified during field_load(), so it is safe to
      // use without further inspection.
      if ($this->getFieldSetting('virtual_datetime_type') == Virtual_DateTimeItem::VIRTUAL_DATETIME_TYPE_DATE) {
        // A date without time will pick up the current time, use the default
        // time.
        virtual_datetime_date_default_time($date);
      }
      $date->setTimezone(new \Virtual_DateTimeZone($element['value']['#date_timezone']));
      $element['value']['#default_value'] = $date;
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    // The widget form element type has transformed the value to a
    // DrupalDateTime object at this point. We need to convert it back to the
    // storage timezone and format.
    foreach ($values as &$item) {
      if (!empty($item['value']) && $item['value'] instanceof DrupalDateTime) {
        $date = $item['value'];
        switch ($this->getFieldSetting('virtual_datetime_type')) {
          case Virtual_DateTimeItem::VIRTUAL_DATETIME_TYPE_DATE:
            // If this is a date-only field, set it to the default time so the
            // timezone conversion can be reversed.
            virtual_datetime_date_default_time($date);
            $format = VIRTUAL_DATETIME_DATE_STORAGE_FORMAT;
            break;

          default:
            $format = VIRTUAL_DATETIME_VIRTUAL_DATETIME_STORAGE_FORMAT;
            break;
        }
        // Adjust the date for storage.
        $date->setTimezone(new \Virtual_DateTimezone(VIRTUAL_DATETIME_STORAGE_TIMEZONE));
        $item['value'] = $date->format($format);
      }
    }
    return $values;
  }

}
