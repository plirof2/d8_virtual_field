<?php

namespace Drupal\virtual_datetime\Plugin\Validation\Constraint;

use Drupal\Component\Datetime\DateTimePlus;
use Drupal\virtual_datetime\Plugin\Field\FieldType\Virtual_DateTimeItem;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Constraint validator for Virtual_DateTime items to ensure the format is correct.
 */
class Virtual_DateTimeFormatConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($item, Constraint $constraint) {
    /* @var $item \Drupal\virtual_datetime\Plugin\Field\FieldType\Virtual_DateTimeItem */
    /*
    if (isset($item)) {
      $value = $item->getValue()['value'];
      if (!is_string($value)) {
        $this->context->addViolation($constraint->badType);
      }
      else {
        $virtual_datetime_type = $item->getFieldDefinition()->getSetting('virtual_datetime_type');
        $format = $virtual_datetime_type === Virtual_DateTimeItem::VIRTUAL_DATETIME_TYPE_DATE ? VIRTUAL_DATETIME_DATE_STORAGE_FORMAT : VIRTUAL_DATETIME_VIRTUAL_DATETIME_STORAGE_FORMAT;
        $date = NULL;
        try {
          $date = Virtual_DateTimePlus::createFromFormat($format, $value, new \Virtual_DateTimeZone(VIRTUAL_DATETIME_STORAGE_TIMEZONE));
        }
        catch (\InvalidArgumentException $e) {
          $this->context->addViolation($constraint->badFormat, [
            '@value' => $value,
            '@format' => $format,
          ]);
          return;
        }
        catch (\UnexpectedValueException $e) {
          $this->context->addViolation($constraint->badValue, [
            '@value' => $value,
            '@format' => $format,
          ]);
          return;
        }
        if ($date === NULL || $date->hasErrors()) {
          $this->context->addViolation($constraint->badFormat, [
            '@value' => $value,
            '@format' => $format,
          ]);
        }
      }
    }
    */
  }

}
