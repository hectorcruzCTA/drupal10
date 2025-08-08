<?php

use Drupal\menu_link_content\Entity\MenuLinkContent;

// Configuración
$menu_name = 'main'; // Cambia aquí si tu menú destino se llama distinto
$csv = '/tmp/menu_principal_correcto.csv';

// Leer CSV
$handle = fopen($csv, 'r');
if (!$handle) {
  echo "Error al abrir CSV: $csv\n";
  exit(1);
}

// Leer encabezado
$header = fgetcsv($handle);

// Mapeo mlid -> uuid
$mlid_to_uuid = [];

// Primera pasada - insertar todos los elementos
rewind($handle);
$header = fgetcsv($handle);

while (($row = fgetcsv($handle)) !== FALSE) {
  $data = array_combine(['mlid','link_title','link_path','weight','plid'], $row);

  $parent = NULL;
  if ($data['plid'] != 0 && isset($mlid_to_uuid[$data['plid']])) {
    $parent = 'menu_link_content:' . $mlid_to_uuid[$data['plid']];
  } elseif ($data['plid'] != 0) {
    // Si no se encuentra el padre en esta pasada (mala orden en CSV)
    echo "⚠️ Padre NO encontrado para mlid={$data['mlid']} ({$data['link_title']}), lo insertamos sin parent.\n";
  }

  $link = MenuLinkContent::create([
    'title' => $data['link_title'],
    'link' => ['uri' => _transform_link($data['link_path'])],
    'menu_name' => $menu_name,
    'weight' => (int) $data['weight'],
    'parent' => $parent,
    'expanded' => TRUE,
    'enabled' => TRUE,
  ]);
  $link->save();

  // Guardamos el UUID para futuras referencias
  $uuid = $link->uuid();
  $mlid_to_uuid[$data['mlid']] = $uuid;

  if ($parent) {
    echo "✅ Hijo insertado: {$data['link_title']} → {$data['link_path']} (parent: {$parent})\n";
  } else {
    echo "✅ Padre insertado: {$data['link_title']} → {$data['link_path']}\n";
  }
}

echo "\n✅ Proceso completo con jerarquía OK.\n";

function _transform_link($link_path) {
  if (strpos($link_path, 'node/') === 0) {
    return 'internal:/' . $link_path;
  } elseif (strpos($link_path, '<front>') === 0) {
    return 'internal:/';
  } elseif (strpos($link_path, 'http') === 0) {
    return $link_path;
  } else {
    return 'internal:/' . $link_path;
  }
}
