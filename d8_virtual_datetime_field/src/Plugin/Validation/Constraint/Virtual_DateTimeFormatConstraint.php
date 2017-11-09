<?php

namespace Drupal\virtual_datetime\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Validation constraint for Virtual_DateTime items to ensure the format is correct.
 *
 * @Constraint(
 *   id = "Virtual_DateTimeFormat",
 *   label = @Translation("Virtual_Datetime format valid for virtual_datetime type.", context = "Validation"),
 * )
 */
class Virtual_DateTimeFormatConstraint extends Constraint {

  /**
   * Message for when the value isn't a string.
   *
   * @var string
   */
  public $badType = "The virtual_datetime value must be a string.";

  /**
   * Message for when the value isn't in the proper format.
   *
   * @var string
   */
  public $badFormat = "The virtual_datetime value '@value' is invalid for the format '@format'";

  /**
   * Message for when the value did not parse properly.
   *
   * @var string
   */
  public $badValue = "The virtual_datetime value '@value' did not parse properly for the format '@format'";

}
