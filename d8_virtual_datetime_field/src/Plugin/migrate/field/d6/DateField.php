<?php

namespace Drupal\virtual_datetime\Plugin\migrate\field\d6;

@trigger_error('DateField is deprecated in Drupal 8.4.x and will be removed before Drupal 9.0.x. Use \Drupal\virtual_datetime\Plugin\migrate\field\DateField instead.', E_USER_DEPRECATED);

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate_drupal\Plugin\migrate\field\FieldPluginBase;

/**
 * @MigrateField(
 *   id = "date",
 *   type_map = {
 *     "date" = "virtual_datetime",
 *     "datestamp" =  "timestamp",
 *     "virtual_datetime" =  "virtual_datetime",
 *   },
 *   core = {6}
 * )
 *
 * @deprecated in Drupal 8.4.x, to be removed before Drupal 9.0.x. Use
 * \Drupal\virtual_datetime\Plugin\migrate\field\DateField instead.
 */
class DateField extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getFieldWidgetMap() {
    return [
      'date' => 'virtual_datetime_default',
      'virtual_datetime' => 'virtual_datetime_default',
      'datestamp' => 'virtual_datetime_timestamp',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function processFieldValues(MigrationInterface $migration, $field_name, $data) {
    switch ($data['type']) {
      case 'date':
        $from_format = 'Y-m-d\TH:i:s';
        $to_format = 'Y-m-d\TH:i:s';
        break;
      case 'datestamp':
        $from_format = 'U';
        $to_format = 'U';
        break;
      case 'virtual_datetime':
        $from_format = 'Y-m-d H:i:s';
        $to_format = 'Y-m-d\TH:i:s';
        break;
      default:
        throw new MigrateException(sprintf('Field %s of type %s is an unknown date field type.', $field_name, var_export($data['type'], TRUE)));
    }
    $process = [
      'value' => [
        'plugin' => 'format_date',
        'from_format' => $from_format,
        'to_format' => $to_format,
        'source' => 'value',
      ],
    ];

    $process = [
      'plugin' => 'iterator',
      'source' => $field_name,
      'process' => $process,
    ];
    $migration->mergeProcessOfProperty($field_name, $process);
  }

}
