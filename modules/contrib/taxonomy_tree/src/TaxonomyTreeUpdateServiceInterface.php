<?php

namespace Drupal\taxonomy_tree;


use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Updates the taxonomy_tree field values in the database.
 *
 * It is not efficient at all to re-save all children when moving their
 * parent. Alternatives entity storages can easily replace this service.
 */
interface TaxonomyTreeUpdateServiceInterface {

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storage_definition
   *   The field storage definition.
   * @param int $tid
   *   The term id.
   * @param string $new
   *   The new material path.
   *
   * @return void
   */
  public function setInitialValue(FieldStorageDefinitionInterface $storage_definition, int $tid, string $new): void;

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storage_definition
   *   The field storage definition.
   * @param string $old
   *   The old material path.
   * @param string $new
   *   The new material path.
   *
   * @return void
   */
  public function updateValue(FieldStorageDefinitionInterface $storage_definition, string $old, string $new): void;

}
