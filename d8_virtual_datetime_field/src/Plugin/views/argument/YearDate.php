<?php

namespace Drupal\virtual_datetime\Plugin\views\argument;

/**
 * Argument handler for a year.
 *
 * @ViewsArgument("virtual_datetime_year")
 */
class YearDate extends Date {

  /**
   * {@inheritdoc}
   */
  protected $argFormat = 'Y';

}
