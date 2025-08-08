<?php

namespace Drupal\Tests\taxonomy_tree\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\taxonomy\TermInterface;

/**
 * @group taxonomy_tree
 */
class TaxonomyTreeKernelTest extends KernelTestBase {

  protected static $modules = [
    'user',
    'taxonomy',
    'text',
  ];

  public function testTaxonomyTree() {
    $names = [];
    $this->installEntitySchema('user');
    $this->installEntitySchema('taxonomy_term');
    $vocabulary = Vocabulary::create(['vid' => $this->randomMachineName()]);
    $vocabulary->save();
    $weights = [0, 2, 1];
    for ($i = 0; $i < 3; $i++) {
      $name = "root " . count($names);
      $names[] = $name;
      $root = Term::create([
        'vid' => $vocabulary->id(),
        'name' => $name,
        'weight' => $weights[$i],
      ]);
      $root->save();
      $roots[] = $root;
      for ($j = 0; $j < 3; $j++) {
        $name = "leaf " . count($names);
        $names[] = $name;
        $term = Term::create([
          'vid' => $vocabulary->id(),
          'name' => $name,
          'weight' => $weights[$j],
          'parent' => $root->id(),
        ]);
        $terms[] = $term;
        $term->save();
      }
    }
    // Test taxonomy_tree_install().
    \Drupal::service('module_installer')->install(['taxonomy_tree']);
    $this->runBatch();
    // Since the weight is [0, 2, 1] we expect them in this order: roots
    // are 0, 8, 4 and their children have the second and the third swapped.
    $order = [0, 1, 3, 2, 8, 9, 11, 10,  4, 5, 7, 6];
    $this->assertDepthFirstOrder($order, $names);
    // Now add a new term.
    $name = 'extra term';
    $names[] = $name;
    Term::create([
      'vid' => $vocabulary->id(),
      'name' => $name,
      'weight' => 3,
      'parent' => $root,
    ])->save();
    $order = [0, 1, 3, 2, 8, 9, 11, 10, 12, 4, 5, 7, 6];
    $this->assertDepthFirstOrder($order, $names);
    // Now move a root to be the child of a child.
    // First reload the term because when it was created the taxonomy_tree
    // field didn't exist yet. This will not be a problem outside of this test.
    $root = Term::load($roots[0]->id());
    $root->parent = $terms[5];
    $root->save();
    $order = [8, 9, 11, 10, 12, 4, 5, 7, 0, 1, 3, 2, 6];
    $this->assertDepthFirstOrder($order, $names);
  }

  /**
   * @param array $order
   *   Keys in the names array, this is the expected order.
   * @param array $names
   *   The list of names.
   *
   * @return void
   */
  public function assertDepthFirstOrder(array $order, array $names): void {
    foreach ($order as $key) {
      $expected[] = $names[$key];
    }
    $tids = \Drupal::entityQuery('taxonomy_term')
      ->sort('taxonomy_tree')
      ->accessCheck(FALSE)
      ->execute();
    $actual_labels = array_values(array_map(fn(TermInterface $term) => $term->label(), Term::loadMultiple($tids)));
    $this->assertSame($expected, $actual_labels);
  }

  protected function runBatch() {
    $batch = batch_get();
    foreach ($batch['sets'] as $current_set) {
      unset($finished);
      do {
        foreach ($current_set['operations'] as $operation) {
          [$function, $args] = $operation;

          // Build the 'context' array and execute the function call.
          $batch_context = [
            'sandbox' => &$current_set['sandbox'],
            'finished' => &$finished,
          ];
          call_user_func_array($function, array_merge($args, [&$batch_context]));
        }
      } while ($finished !== 1);
    }
  }

}
