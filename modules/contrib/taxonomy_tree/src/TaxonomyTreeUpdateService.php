<?php

namespace Drupal\taxonomy_tree;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Updates the taxonomy_tree field values in the database.
 *
 * It is not efficient at all to re-save all children when moving their
 * parent. Alternative entity storages can easily replace this service.
 */
class TaxonomyTreeUpdateService implements TaxonomyTreeUpdateServiceInterface {

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   * @param \Drupal\Core\Routing\RouteMatchInterface $routeMatch
   *   The current route match.
   */
  public function __construct(protected EntityTypeManagerInterface $entityTypeManager, protected Connection $connection, protected RouteMatchInterface $routeMatch) {
  }

  /**
   * {@inheritdoc}
   */
  public function setInitialValue(FieldStorageDefinitionInterface $storage_definition, int $tid, string $new): void {
    $this->doUpdateValue($storage_definition, $tid, $new, 'initialQuery');
  }

  /**
   * {@inheritdoc}
   */
  public function updateValue(FieldStorageDefinitionInterface $storage_definition, string $old, string $new): void {
    $this->doUpdateValue($storage_definition, $old, $new, 'updateQuery');
  }

  /**
   * Updates the taxonomy_tree values of children of a parent.
   *
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storage_definition
   *   The field storage definition.
   * @param $old
   *   The old value.
   * @param $new
   *   The new value.
   *
   * @return void
   */
  protected function doUpdateValue(FieldStorageDefinitionInterface $storage_definition, int|string $old, string $new, $method): void {
    /** @var \Drupal\Core\Entity\Sql\SqlContentEntityStorage $storage */
    $storage = $this->entityTypeManager->getStorage($storage_definition->getTargetEntityTypeId());
    /** @var \Drupal\Core\Entity\Sql\DefaultTableMapping $mapping */
    $mapping = $storage->getTableMapping();
    $column_name = $mapping->getFieldColumnName($storage_definition, $storage_definition->getMainPropertyName());
    foreach ($mapping->getAllFieldTableNames($storage_definition->getName()) as $table_name) {
      $this->$method($table_name, $column_name, $old, $new, $mapping->allowsSharedTableStorage($storage_definition));
    }

  }

  /**
   * @param string $table_name
   *   The table name. Typically taxonomy_term_field_data.
   * @param string $column_name
   *   The column name. Typically taoxnomy_tree.
   * @param int $tid
   *   The old value.
   * @param string $new
   *   The initial value.
   * @param bool $shared
   *   Whether this is a shared table.
   *
   * @return void
   */
  protected function initialQuery(string $table_name, string $column_name, int $tid, string $new, bool $shared): void {
    $this->connection
      ->update($table_name)
      ->fields([$column_name => $new])
      ->condition($shared ? 'tid' : 'entity_id', $tid)
      ->execute();
  }

  /**
   * @param string $table_name
   *   The table name. Typically taxonomy_term_field_data.
   * @param string $column_name
   *   The column name. Typically taoxnomy_tree.
   * @param $old
   *   The old value.
   * @param $new
   *   The new value.
   *
   * @return void
   */
  protected function updateQuery(string $table_name, string $column_name, string $old, string $new): void {
    // Since every path is starting with 0xFFFFFFFF and children do not use
    // this value, a regular expression is not necessary.
    $this->connection
      ->update($table_name)
      ->expression($column_name, "REPLACE([$column_name], :old, :new)", [
        ':old' => $old,
        ':new' => $new
      ])
      ->condition($column_name, $this->connection->escapeLike($old) . '%', 'LIKE')
      ->execute();
  }

}
