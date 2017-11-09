<?php

namespace Drupal\virtual_datetime;

use Drupal\virtual_datetime\Plugin\Field\FieldType\Virtual_DateTimeItem;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\Core\TypedData\TypedData;

/**
 * A computed property for dates of date time field items.
 *
 * Required settings (below the definition's 'settings' key) are:
 *  - date source: The date property containing the to be computed date.
 */
class Virtual_DateTimeComputed extends TypedData {

  /**
   * Cached computed date.
   *
   * @var \Virtual_DateTime|null
   */
  protected $date = NULL;

  /**
   * {@inheritdoc}
   */
  public function __construct(DataDefinitionInterface $definition, $name = NULL, TypedDataInterface $parent = NULL) {
    parent::__construct($definition, $name, $parent);
    if (!$definition->getSetting('date source')) {
      throw new \InvalidArgumentException("The definition's 'date source' key has to specify the name of the date property to be computed.");
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getValue($langcode = NULL) {
    if ($this->date !== NULL) {
      return $this->date;
    }

    /** @var \Drupal\Core\Field\FieldItemInterface $item */
    $item = $this->getParent();
    $value = $item->{($this->definition->getSetting('date source'))};

    $virtual_datetime_type = $item->getFieldDefinition()->getSetting('virtual_datetime_type');
    $storage_format = $virtual_datetime_type === Virtual_DateTimeItem::VIRTUAL_DATETIME_TYPE_DATE ? VIRTUAL_DATETIME_DATE_STORAGE_FORMAT : VIRTUAL_DATETIME_VIRTUAL_DATETIME_STORAGE_FORMAT;
    try {
      $date = DrupalDateTime::createFromFormat($storage_format, $value, VIRTUAL_DATETIME_STORAGE_TIMEZONE);
      if ($date instanceof DrupalDateTime && !$date->hasErrors()) {
        $this->date = $date;
        // If the format did not include an explicit time portion, then the
        // time will be set from the current time instead. For consistency, we
        // set the time to 12:00:00 UTC for date-only fields. This is used so
        // that the local date portion is the same, across nearly all time
        // zones.
        // @see virtual_datetime_date_default_time()
        // @see http://php.net/manual/en/virtual_datetime.createfromformat.php
        // @todo Update comment and/or code per the chosen solution in
        //   https://www.drupal.org/node/2830094
        if ($virtual_datetime_type === Virtual_DateTimeItem::VIRTUAL_DATETIME_TYPE_DATE) {
          $this->date->setTime(12, 0, 0);
        }
      }
    }
    catch (\Exception $e) {
      // @todo Handle this.
    }
    return $this->date;
  }

  /**
   * {@inheritdoc}
   */
  public function setValue($value, $notify = TRUE) {
    $this->date = $value;
    // Notify the parent of any changes.
    if ($notify && isset($this->parent)) {
      $this->parent->onChange($this->name);
    }
  }

}
