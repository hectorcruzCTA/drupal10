<?php
use Drupal\node\Entity\Node;
use Drupal\Core\Database\Database;

// 1) Conexión a la base D7 (clave ‘drupal7’ en settings.php)
Database::addConnectionInfo('drupal7', 'default', [
  'database' => 'drupal7_copy',
  'username' => 'migrator',
  'password' => 'MIgrac10n*',
  'host'     => 'localhost',
  'driver'   => 'mysql',
  'prefix'   => '',
  'port'     => '3606',
]);

// 2) Abre el CSV y lee encabezados
$csv_path = '/tmp/noticias.csv';
if (! file_exists($csv_path)) {
  echo "ERROR: no existe $csv_path\n";
  exit(1);
}
$handle  = fopen($csv_path, 'r');
$headers = fgetcsv($handle);
$numH    = count($headers);
$imported = [];

// 3) Itera cada fila
while ($row = fgetcsv($handle)) {
  // 3.1) Normaliza columnas
  $count = count($row);
  if ($count < $numH) {
    $row = array_pad($row, $numH, NULL);
  } elseif ($count > $numH) {
    $row = array_slice($row, 0, $numH);
  }

  // 3.2) Combina y limpia “NULL”
  $data = array_combine($headers, $row);
  foreach ($data as &$v) {
    if ($v === 'NULL') {
      $v = NULL;
    }
  }
  unset($v);

  // 3.3) Evita duplicados por nid de D7
  $nid7 = $data['nid'] ?? NULL;
  if ($nid7 && isset($imported[$nid7])) {
    continue;
  }
  $imported[$nid7] = TRUE;

  // 4) Trae body completo desde D7
  $d7 = Database::getConnection('default', 'drupal7');
  $body_row = $d7->select('field_data_body', 'b')
    ->fields('b', ['body_value','body_format','body_summary'])
    ->condition('b.entity_id', $nid7)
    ->execute()
    ->fetchAssoc();

  $full_body   = $body_row['body_value']   ?? $data['sinopsis'] ?? '';
  $body_format = $body_row['body_format']  ?? 'basic_html';
  $summary     = $body_row['body_summary'] ?? '';

  // 5) Prepara valores con los campos correctos
  $values = [
    'type'             => 'noticia',
    'title'            => $data['title'] ?: 'Sin título',
    'status'           => 1,
    // mapea tu campo sinopsis (observa el underscore final):
    'field_sinopsis_'  => $data['sinopsis'] ?: '',
    // mapea tu campo cuerpo:
    'field_cuerpo'     => [
      'value'   => $full_body,
      'format'  => $body_format,
      'summary' => $summary,
    ],
  ];

  // 6) Crea y guarda el nodo
  $node = Node::create($values);
  $node->save();
  echo "Importado D7 nid={$nid7} → D10 nid={$node->id()}\n";
}

fclose($handle);
echo "¡Import completo con cuerpo y sinopsis!\n";
