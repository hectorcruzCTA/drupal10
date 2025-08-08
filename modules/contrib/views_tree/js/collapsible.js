/**
 * @file
 */

(function ($, Drupal) {
  Drupal.behaviors.viewsTree = {
    attach(context, settings) {
      const viewsTreeSettings = settings.views_tree_settings;

      Object.keys(viewsTreeSettings).forEach((settingId) => {
        const [viewsTreeName, collapsedState] = viewsTreeSettings[settingId];

        $(
          `.view-id-${viewsTreeName} .view-content li:not(.views-tree-processed)`,
          context,
        ).each(function () {
          $(this).addClass('views-tree-processed');

          const count = $(this).find('li').length;
          if (count > 0) {
            $(this).addClass('views_tree_parent');
            $(this).children('ul').addClass('item-list');

            const isCollapsed = collapsedState === 'collapsed';
            const linkClass = isCollapsed
              ? 'views_tree_link_collapsed'
              : 'views_tree_link_expanded';
            const parentClass = isCollapsed
              ? 'views_tree_collapsed'
              : 'views_tree_expanded';

            $(this).addClass(parentClass);
            $(this).prepend(
              `<div class="views_tree_link ${linkClass}"><a href="#">${Drupal.t(
                'Operation',
              )}</a></div>`,
            );

            if (isCollapsed) {
              $(this).children('.item-list').hide();
            }
          }
        });
      });

      $('.views_tree_link a:not(.view-tree-processed)', context)
        .addClass('view-tree-processed')
        .on('click', function (e) {
          e.preventDefault();
          const $parent = $(this).parent();
          const $itemList = $parent.parent().children('.item-list');

          const isExpanded = $parent.hasClass('views_tree_link_expanded');

          $itemList.slideToggle();
          $parent.toggleClass(
            'views_tree_link_expanded views_tree_link_collapsed',
          );
        });
    },
  };
})(jQuery, Drupal);
