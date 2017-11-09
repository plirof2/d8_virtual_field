<?php

namespace Drupal\virtual_datetime\Plugin\views\argument;

/**
 * Argument handler for a week.
 *
 * @ViewsArgument("virtual_datetime_week")
 */
class WeekDate extends Date {

  /**
   * {@inheritdoc}
   */
  protected $argFormat = 'W';

}
