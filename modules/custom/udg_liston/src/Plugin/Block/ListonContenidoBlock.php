<?php

namespace Drupal\udg_liston\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "liston_contenido",
 *   admin_label = @Translation("Liston de contenido")
 * )
 */
class ListonContenidoBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
      $markup = '<div class="accesibilityTools container">
                    <a href="#" id="accessibility-sepia" >Sepia</a> | <a href="#" id="accessibility-contrast" alt="js-accessibility" >Grises</a> | <a href="#" id="accessibility-invert" alt="js-accessibility" >Invertir de color</a> | <a href="#" class="aumentarFont">+A</a> | <a href="#" class="disminuirFont">-A</a> | <a href="#" class="resetearFont">Normal</a>
                </div>';
	    /*$query = \Drupal::entityQuery('node');
        $query->condition('status', 1);
        //$query->condition('field_url_liston', "vision");
        $query->condition('type', 'titulo');
        $entity_ids = $query->execute();
	    //$nodes = \Drupal\node\Entity\Node::loadMultiple();
	    dsm($entity_ids);	*/

		//URL ACTIVA
		//$URL = $_GET["q"];
		$current_path = \Drupal::service('path.current')->getPath();
		/* D8 OLD $URL = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);*/
		$URL = \Drupal::service('path_alias.manager')->getAliasByPath($current_path);
		//kint($result);
		if (\Drupal::service('path.matcher')->isFrontPage()){
			//kint("front");
			$URL = "/";
		}
		//kint($result);
		$nids = \Drupal::entityQuery('node')->accessCheck(TRUE)->condition('status', 1)->condition('type','liston_de_contenido')->execute();
		$nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
		//kint($nodes);
		foreach ($nodes as $node){
			//Imagen
			foreach($node->get('field_liston_c')->getValue() as $file){
				$fid = $file['target_id']; // get file fid;
				//dsm($fid);
				$photo = \Drupal::service('file_url_generator')->generateAbsoluteString(\Drupal\file\Entity\File::load($fid)->getFileUri());
				//dsm(file_create_url($photo));
  			}

      if(isset($node->get('field_liston_c')->getValue()['0']['alt'])){
          $alt = $node->get('field_liston_c')->getValue()['0']['alt'];
      }else{
        $alt = '';
      }

			//kint($node);
			$values = $node->get('field_url_lc')->getValue();
			foreach($values as $value){
				$campo = str_replace("internal:","",$value['uri']);

				//dpm("Campo es ".$campo);
				//dpm("URL es ".$URL);
				if($campo === $URL){
					$markup .= '<div class="campo"><img alt="'.$alt.'" src="'.$photo.'"  typeof="foaf:Image" class="img-responsive"></div>';
				}
				//Comodin
				//dpm($campo);
			    $menosu = strlen($campo);
			    //dpm($menosu);
			    $menos = $menosu -1;
			    //dpm($menos);
			    $ulti = substr($campo, $menos);
			    //dpm($ulti);
			    if ($ulti == "*"){
				    //dpm("si");
					$comodin = substr($campo, 0, $menos);
					$URI = substr($URL, 0, $menos);
					if ($URI == $comodin){
						$markup .= '<div class="campo"><img alt="'.$alt.'" src="'.$photo.'"  typeof="foaf:Image" class="img-responsive"></div>';
					}
				}

			}

		}

		/*$output = array();
        $output[]['#cache']['max-age'] = 0; // No cache
        $output[] = [
	        'type' => 'markup',
	        '#markup' => $markup,
        //'Time : ' . date("H:i:s"),
        ];
		return $output;*/

        /*return [
            '#type' => 'markup',
            '#markup' => $markup,
        ];*/
        if($markup == '<div class="accesibilityTools container">
                    <a href="#" id="accessibility-sepia" >Sepia</a> | <a href="#" id="accessibility-contrast" alt="js-accessibility" >Grises</a> | <a href="#" id="accessibility-invert" alt="js-accessibility" >Invertir de color</a> | <a href="#" class="aumentarFont">+A</a> | <a href="#" class="disminuirFont">-A</a> | <a href="#" class="resetearFont">Normal</a>
                </div>'){
          $markup = '<div class="container empty">
          <div class="accesibilityTools">
                    <a href="#" id="accessibility-sepia" >Sepia</a> | <a href="#" id="accessibility-contrast" alt="js-accessibility" >Grises</a> | <a href="#" id="accessibility-invert" alt="js-accessibility" >Invertir de color</a> | <a href="#" class="aumentarFont">+A</a> | <a href="#" class="disminuirFont">-A</a> | <a href="#" class="resetearFont">Normal</a>
                </div></div>';
        }
    	return array(
	    	//'#theme' => 'udg_liston',
	    	'#type' => 'markup',
			'#markup' => $markup,
			'#cache' => array(
				'max-age' => 0,
				),
			'#attached' => array(
				'library' => array(
					'udg_liston/udg_liston',
          'udg_liston/accesibilidadUdg'
					),
			),
		);


    }



}

?>
