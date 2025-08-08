<?php

// Cargar el API de Drupal.
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\Core\Link;
use Drupal\Core\Url;

// Nombre del menú en Drupal 10.
$menu_name = 'main'; // ESTE es el menú "Main navigation" en tu Drupal 10 (AJUSTADO)

// Ruta del CSV exportado.
$csv_file = '/tmp/menu_principal.csv';

// Mapear mlid → UUID temporal para referenciar padres.
$mlid_map = [];

// Primera pasada: insertar los padres (plid == 0)
if (($handle = fopen($csv_file, "r")) !== FALSE) {
    // Leer cabecera.
    fgetcsv($handle, 1000, ",");

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        list($mlid, $link_title, $link_path, $menu_name_src, $weight, $plid) = $data;

        if ($plid == '0') {
            $url = strpos($link_path, 'http') === 0 ? Url::fromUri($link_path) : Url::fromUserInput('/' . $link_path);

            $menu_link = MenuLinkContent::create([
                'title' => $link_title,
                'link' => ['uri' => $url->toUriString()],
                'menu_name' => $menu_name,
                'weight' => (int) $weight,
                'parent' => '',
                'enabled' => 1,
            ]);
            $menu_link->save();

            $mlid_map[$mlid] = $menu_link->getPluginId();

            echo "✅ Padre insertado: {$link_title} → " . $url->toUriString() . "\n";
        }
    }
    fclose($handle);
}

// Segunda pasada: insertar los hijos.
if (($handle = fopen($csv_file, "r")) !== FALSE) {
    // Leer cabecera.
    fgetcsv($handle, 1000, ",");

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        list($mlid, $link_title, $link_path, $menu_name_src, $weight, $plid) = $data;

        if ($plid != '0') {
            if (!isset($mlid_map[$plid])) {
                echo "⚠️ No se encontró padre para plid {$plid} → {$link_title}, se insertará como raíz.\n";
                $parent = '';
            } else {
                $parent = $mlid_map[$plid];
            }

            $url = strpos($link_path, 'http') === 0 ? Url::fromUri($link_path) : Url::fromUserInput('/' . $link_path);

            $menu_link = MenuLinkContent::create([
                'title' => $link_title,
                'link' => ['uri' => $url->toUriString()],
                'menu_name' => $menu_name,
                'weight' => (int) $weight,
                'parent' => $parent,
                'enabled' => 1,
            ]);
            $menu_link->save();

            echo "🔗 Hijo insertado: {$link_title} → parent: {$parent}\n";
        }
    }
    fclose($handle);
}

echo "✅ Proceso completo con jerarquía OK.\n";
