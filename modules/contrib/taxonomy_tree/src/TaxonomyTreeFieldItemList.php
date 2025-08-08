<?php

namespace Drupal\taxonomy_tree;

use Drupal\Core\Field\FieldItemList;
use Drupal\taxonomy\TermInterface;

/**
 * Recalculate the material path.
 *
 * This must be done here and not in the field item class because it is
 * possible the field item does not exist and then of course its preSave()
 * method will not be called.
 */
class TaxonomyTreeFieldItemList extends FieldItemList {

  protected bool $termIsNew = FALSE;

  /**
   * {@inheritdoc}
   */
  public function preSave() {
    $term = $this->getEntity();
    if (!$term instanceof TermInterface) {
      return;
    }
    $new = $this->recalculate();
    $this->set(0, $new);
    if (isset($term->original)) {
      /** @var static $old_item_list */
      $old = $term->original->get($this->getName())->value;
      if ($old && $old !== $new) {
        $storage_definition = $this->getFieldDefinition()->getFieldStorageDefinition();
        $this->getTreeUpdateService()->updateValue($storage_definition, $old, $new);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function postSave($update) {
    if ($this->termIsNew) {
      $this->termIsNew = FALSE;
      $storage_definition = $this->getFieldDefinition()->getFieldStorageDefinition();
      $tid = $this->getEntity()->id();
      $new = $this->recalculate();
      $this->getTreeUpdateService()->setInitialValue($storage_definition, $tid, $new);
    }
    return parent::postSave($update);
  }

  /**
   * @return string
   *   The new materialized path.
   */
  protected function recalculate(): string {
    $term = $this->getEntity();
    if ($term->isNew()) {
      // There is no term ID yet. Punt.
      $this->termIsNew = TRUE;
      return '';
    }
    $parents = $term->get('parent')->referencedEntities();
    $prefix = match(count($parents)) {
      // A unique value anchoring the path so updates can be done without
      // regular expressions
      0 => $this->encoder(0xFFFFFFFF),
      // The materialized path of the parent
      1 => reset($parents)->get($this->getName())->value,
      // Multiple parents are not supported at the moment.
      default => '',
    };
    if ($prefix) {
      return
        $prefix .
        $this->encoder($term->getWeight()) .
        $this->encoder($term->id());
    }
    return '';
  }

  /**
   * Encodes a number to a string.
   *
   * The encoding is chosen so the encoded strings sort the same order as the
   * original integers. Consider for example sorting 12 and 2. The string "12"
   * will sort before "2" which is not desirable. By encoding them at a fixed
   * length, this can be fixed: "12" and " 2" sorts correctly. This method does
   * the same but uses base 256 instead of base 10 for more efficient storage
   * and pads with zero bytes.
   *
   * @param int $integer
   *   A number.
   *
   * @return string
   *   Four byte long representation of the integer, big endian order.
   */
  protected function encoder(int $integer): string {
    return pack('N', $integer);
  }

  /**
   * Get the tree update service.
   *
   * @return \Drupal\taxonomy_tree\TaxonomyTreeUpdateServiceInterface
   *   The tree update service.
   */
  public function getTreeUpdateService(): TaxonomyTreeUpdateServiceInterface {
    return \Drupal::service('taxonomy_tree.update');
  }

  public function getDepth() {
    // Every level adds two 4 byte integers so divide the length by eight.
    // The root adds 4, which when divided by 8 and rounded down is zero
    // so it doesn't need to be taken into account.
    return strlen($this->value) >> 3;
  }

}
