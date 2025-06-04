<?php

namespace Drupal\udg_liston\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\Entity\EntityViewDisplay;

/**
 * Definición de nuestro bloque.
 *
 * @Block(
 *   id = "liston_udg",
 *   admin_label = @Translation("Liston UDG")
 * )
 */
class ListonBlock extends BlockBase {
  
  /**
   * {@inheritdoc}
   */
  public function build() {
  $markup = '';

    // Inicializa $markup con contenido base
   // $markup = '<div class="accesibilityTools container">
     //             <a href="#" id="accessibility-sepia">Sepia</a> |
       //           <a href="#" id="accessibility-contrast" class="js-accessibility">Grises</a> |
         //         <a href="#" id="accessibility-invert" class="js-accessibility">Invertir de color</a> |
           //       <a href="#" class="aumentarFont">+A</a> |
             //     <a href="#" class="disminuirFont">-A</a> |
               //   <a href="#" class="resetearFont">Normal</a>
              // </div>';

    // Obtener la URL actual
    $current_path = \Drupal::service('path.current')->getPath();
    $URL = \Drupal::service('path_alias.manager')->getAliasByPath($current_path);

    // Verificar si es la página de inicio
    if (\Drupal::service('path.matcher')->isFrontPage()) {
      $URL = "/";
    }

    // Obtener nodos del tipo 'titulo'
    $nids = \Drupal::entityQuery('node')
      ->accessCheck(TRUE)
      ->condition('status', 1)
      ->condition('type', 'titulo')
      ->execute();

    if (!empty($nids)) {
      $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);

      foreach ($nodes as $node) {
        // Obtener la imagen del listón
        $field_liston = $node->get('field_liston')->getValue();
        if (!empty($field_liston[0]['target_id'])) {
          $fid = $field_liston[0]['target_id'];
          $photo = \Drupal::service('file_url_generator')
            ->generateAbsoluteString(\Drupal\file\Entity\File::load($fid)->getFileUri());
          $alt = !empty($field_liston[0]['alt']) ? $field_liston[0]['alt'] : '';
        }
        else {
          $photo = '';
          $alt = '';
        }

        // Recorrer el campo field_url_liston para comparar la URL
        $values = $node->get('field_url_liston')->getValue();
        foreach ($values as $value) {
          $campo = str_replace("internal:", "", $value['uri']);

          // 1) Coincidencia exacta
          if ($campo === $URL) {
            $markup .= '<div class="campo"><img alt="' . $alt . '" src="' . $photo . '" class="img-responsive"></div>';
          }

          // 2) Lógica del comodín (asterisco al final)
          $campo_length = strlen($campo);
          if ($campo_length > 0) {
            $last_char = substr($campo, -1); // último caracter
            if ($last_char === '*') {
              // Quitamos el asterisco
              $comodin = substr($campo, 0, $campo_length - 1);
              // Tomamos la misma porción de la URL actual
              $uri_substr = substr($URL, 0, $campo_length - 1);

              // Si coinciden, mostramos el listón
              if ($uri_substr === $comodin) {
                $markup .= '<div class="campo"><img alt="' . $alt . '" src="' . $photo . '" class="img-responsive"></div>';
              }
            }
          }
        }
      }
    }

    // Si sigue igual al markup base, agregamos clase "empty"
    if (trim($markup) === '<div class="accesibilityTools container">
                  <a href="#" id="accessibility-sepia">Sepia</a> |
                  <a href="#" id="accessibility-contrast" class="js-accessibility">Grises</a> |
                  <a href="#" id="accessibility-invert" class="js-accessibility">Invertir de color</a> |
                  <a href="#" class="aumentarFont">+A</a> |
                  <a href="#" class="disminuirFont">-A</a> |
                  <a href="#" class="resetearFont">Normal</a>
               </div>') {
      $markup = '<div class="container empty">
                  <div class="accesibilityTools">
                    <a href="#" id="accessibility-sepia">Sepia</a> |
                    <a href="#" id="accessibility-contrast" class="js-accessibility">Grises</a> |
                    <a href="#" id="accessibility-invert" class="js-accessibility">Invertir de color</a> |
                    <a href="#" class="aumentarFont">+A</a> |
                    <a href="#" class="disminuirFont">-A</a> |
                    <a href="#" class="resetearFont">Normal</a>
                  </div>
                </div>';
    }

    // Log para verificar si el bloque se ejecuta
    \Drupal::logger('udg_liston')->notice('El bloque Listón UDG se está ejecutando.');

    // Devolver el bloque con configuración de caché
    return [
      '#type' => 'markup',
      '#markup' => $markup,
      '#cache' => [
        'max-age' => 0, // Evitar cacheo para pruebas
      ],
      '#attached' => [
        'library' => [
         'udg_liston/udg_liston',
         // Quita 'udg_liston/accesibilityUdg.js' y usa la librería:
         'udg_liston/accesibilityUdg', // sin .js al final
        ],
      ],
    ];
  }
}

