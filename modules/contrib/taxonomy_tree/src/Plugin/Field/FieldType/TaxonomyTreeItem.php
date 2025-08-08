<?php

namespace Drupal\taxonomy_tree\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'taxonomy_tree' field type.
 *
 * @FieldType(
 *   id = "taxonomy_tree",
 *   no_ui = TRUE,
 *   list_class = "\Drupal\taxonomy_tree\TaxonomyTreeFieldItemList"
 * )
 */
class TaxonomyTreeItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $columns = [
      'value' => [
        'type' => 'varbinary',
        // This is FALSE only for install purposes.
        'not null' => FALSE,
        'description' => 'Materialized path.',
        // This has been the prefix length since MySQL 5.7 unless using a
        // deprecated format which was dropped in MySQL 8.0.
        'length' => 3072,
      ],
    ];

    $schema = [
      'columns' => $columns,
      'indexes' => [
        'value' => ['value'],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return FALSE;
  }

}
