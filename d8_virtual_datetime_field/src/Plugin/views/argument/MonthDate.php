<?php

namespace Drupal\virtual_datetime\Plugin\views\argument;

/**
 * Argument handler for a month.
 *
 * @ViewsArgument("virtual_datetime_month")
 */
class MonthDate extends Date {

  /**
   * {@inheritdoc}
   */
  protected $argFormat = 'm';

}
