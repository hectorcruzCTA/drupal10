<?php

namespace Drupal\udg_liston\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "Banner_udg",
 *   admin_label = @Translation("Banner UDG")
 * )
 */
class BannerBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
      $markup = "";
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
		$nids = \Drupal::entityQuery('node')->accessCheck(TRUE)->condition('status', 1)->condition('type','enlaces_de_interes')->execute();
		$nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
		//kint($nodes);
		foreach ($nodes as $node){
      $urlgo = '';
			//Imagen
			foreach($node->get('field_liston')->getValue() as $file){
				$fid = $file['target_id']; // get file fid;
				//dsm($fid);
				/*Esta funcion obsoleta se cambio por la siguiente que no esta comentada
					$photo = file_create_url(\Drupal\file\Entity\File::load($fid)->getFileUri());
				*/
				$photo = \Drupal::service('file_url_generator')->generateAbsoluteString(\Drupal\file\Entity\File::load($fid)->getFileUri());

				//dsm(file_create_url($photo));
  			}
      if(isset($node->get('field_liston')->getValue()['0']['alt'])){
        $alt = $node->get('field_liston')->getValue()['0']['alt'];
      }else{
        $alt = '';
      }
      //kint($node->get('field_liston')->getValue()['0']['alt']);

			//kint($node);
			$values = $node->get('field_url_liston')->getValue();
      if(isset($node->get('field_link')->getValue()['0']['uri'])){
                    $urlgo = $node->get('field_link')->getValue()['0']['uri'];
                    $urlgo = str_replace("internal:","",$urlgo);
                    //kint($urlgo);
              }
			foreach($values as $value){
				$campo = str_replace("internal:","",$value['uri']);

				//dpm("Campo es ".$campo);
				//dpm("URL es ".$URL);
				if($campo === $URL){
					$markup .= '<div class="campo">';
          if($urlgo != ''){
             $markup .= '<a href="'.$urlgo.'" tabindex="-1">';
          }
          $markup .= '<img alt="'.$alt.'" src="'.$photo.'" typeof="foaf:Image" class="img-responsive">';
          if($urlgo != ''){
            $markup .='</a>';
          }
          $markup .= '</div>';
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
						//if($campo === $URL){
			              $markup .= '<div class="campo">';
			              if($urlgo != ''){
			                 $markup .= '<a href="'.$urlgo.'" tabindex="-1">';
			              }
			              $markup .= '<img alt="'.$alt.'" src="'.$photo.'" typeof="foaf:Image" class="img-responsive">';
			              if($urlgo != ''){
			                $markup .='</a>';
			              }
			              $markup .= '</div>';
			           // }
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

    	return array(
	    	//'#theme' => 'udg_liston',
	    	'#type' => 'markup',
			  '#markup' => $markup,
			  '#cache' => array(
				  'max-age' => 4,
				 ),
			   '#attached' => array(
				 'library' => array(
					'udg_liston/udg_banner',
				 ),
			),
		);


    }



}

?>
