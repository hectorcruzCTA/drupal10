<?php

namespace Drupal\views_tree\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\Attribute\EntityReferenceSelection;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\views\Plugin\EntityReferenceSelection\ViewsSelection;
use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;
use Drupal\views_tree\TreeHelper;
use Drupal\views_tree\ViewsResultTreeValues;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'selection' entity_reference.
 */
#[EntityReferenceSelection(
  id: "views_tree",
  label: new TranslatableMarkup("TreeHelper (Adjacency model)"),
  group: 'views_tree',
  weight: 0,
)]
class TreeViewsSelection extends ViewsSelection {

  /**
   * Constructs the ER views selection plugin.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity manager service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   * @param \Drupal\views_tree\TreeHelper $tree
   *   The tree helper.
   * @param \Drupal\views_tree\ViewsResultTreeValues $viewsResultTreeValues
   *   The views result tree values service.
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entityRepository
   *   The entity repository service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    ModuleHandlerInterface $module_handler,
    AccountInterface $current_user,
    RendererInterface $renderer,
    protected TreeHelper $tree,
    protected ViewsResultTreeValues $viewsResultTreeValues,
    protected EntityRepositoryInterface $entityRepository,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $module_handler, $current_user, $renderer);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('module_handler'),
      $container->get('current_user'),
      $container->get('renderer'),
      $container->get('views_tree.tree'),
      $container->get('views_tree.views_tree_values'),
      $container->get('entity.repository'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getReferenceableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0): array {
    $handler_settings = $this->configuration['handler_settings'];
    $display_name = $handler_settings['view']['display_name'];
    $arguments = $handler_settings['view']['arguments'];
    $result = [];
    if ($this->initializeView($match, $match_operator, $limit)) {
      // Get the results.
      $result = $this->view->executeDisplay($display_name, $arguments);
    }

    $this->applyTreeOnResult($this->view, $this->view->result);
    $tree = $this->tree->getTreeFromResult($this->view->result);

    $return = [];
    if ($result) {
      $this->tree->applyFunctionToTree($tree, function (ResultRow $row) use (&$return) {
        $entity = $row->_entity;
        $entity_translated = $this->entityRepository->getTranslationFromContext($entity)->label();
        $return[$entity->bundle()][$entity->id()] = str_repeat('-', $row->views_tree_depth) . $entity_translated;
        return NULL;
      });
    }

    return $return;
  }

  /**
   * Applies a tree to a result set.
   *
   * @param \Drupal\views\ViewExecutable $view
   *   The view.
   * @param \Drupal\views\ResultRow[] $result
   *   The result set.
   */
  protected function applyTreeOnResult(ViewExecutable $view, array $result): void {
    $this->viewsResultTreeValues->setTreeValues($view, $result);
  }

}
