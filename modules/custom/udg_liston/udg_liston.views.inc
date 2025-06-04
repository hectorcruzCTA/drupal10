<?php

/**
 * @file
 * Contains udg_liston\udg_liston.views.inc..
 * Provide a custom views field data that isn't tied to any other module. */


/**
* Implements hook_views_data().
*/
function udg_liston_views_data() {
    $data['views']['table']['group'] = t('Custom Global');
    $data['views']['table']['join'] = [
      // #global is a special flag which allows a table to appear all the time.
      '#global' => [],
    ];

    $data['views']['udg_liston_views_field'] = [
        'title' => t('Campo para agenda'),
        'help' => t('Campo para ejecutar las descargas de eventos a dispositivos moviles'),
        'field' => [
            'id' => 'udg_liston_views_field',
        ],
    ];
    return $data;
}
