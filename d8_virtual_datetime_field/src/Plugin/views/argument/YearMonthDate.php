<?php

namespace Drupal\virtual_datetime\Plugin\views\argument;

/**
 * Argument handler for a year plus month (CCYYMM).
 *
 * @ViewsArgument("virtual_datetime_year_month")
 */
class YearMonthDate extends Date {

  /**
   * {@inheritdoc}
   */
  protected $argFormat = 'Ym';

}
