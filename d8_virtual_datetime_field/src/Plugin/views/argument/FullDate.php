<?php

namespace Drupal\virtual_datetime\Plugin\views\argument;

/**
 * Argument handler for a full date (CCYYMMDD).
 *
 * @ViewsArgument("virtual_datetime_full_date")
 */
class FullDate extends Date {

  /**
   * {@inheritdoc}
   */
  protected $argFormat = 'Ymd';

}
