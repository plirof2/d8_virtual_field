<?php

namespace Drupal\virtual_datetime\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\virtual_datetime\Plugin\Field\FieldType\Virtual_DateTimeItem;

/**
 * Plugin implementation of the 'Plain' formatter for 'virtual_datetime' fields.
 *
 * @FieldFormatter(
 *   id = "virtual_datetime_plain",
 *   label = @Translation("Plain"),
 *   field_types = {
 *     "virtual_datetime"
 *   }
 *)
 */
class Virtual_DateTimePlainFormatter extends Virtual_DateTimeFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      if (!empty($item->date)) {
        /** @var \Drupal\Core\Datetime\DrupalDateTime $date */
        $date = $item->date;

        $elements[$delta] = $this->buildDate($date);
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  protected function formatDate($date) {
    $format = $this->getFieldSetting('virtual_datetime_type') == Virtual_DateTimeItem::VIRTUAL_DATETIME_TYPE_DATE ? VIRTUAL_DATETIME_DATE_STORAGE_FORMAT : VIRTUAL_DATETIME_VIRTUAL_DATETIME_STORAGE_FORMAT;
    $timezone = $this->getSetting('timezone_override');
    return $this->dateFormatter->format($date->getTimestamp(), 'custom', $format, $timezone != '' ? $timezone : NULL);
  }

}
