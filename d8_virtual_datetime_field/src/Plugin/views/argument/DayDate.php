<?php

namespace Drupal\virtual_datetime\Plugin\views\argument;

/**
 * Argument handler for a day.
 *
 * @ViewsArgument("virtual_datetime_day")
 */
class DayDate extends Date {

  /**
   * {@inheritdoc}
   */
  protected $argFormat = 'd';

}
