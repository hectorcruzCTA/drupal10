<?php
 
namespace Drupal\udg_liston\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\Entity\EntityViewDisplay;

/**
 * Definición de nuestro bloque
 *
 * @Block( 
 *   id = "socialmedia_udg",
 *   admin_label = @Translation("Social Media UDG")
 * )
 */

class SocialMediaBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */

    public function build() {
      	$markup ='<div id="social-stream"></div>';

    	return array(
	    	'#type' => 'markup',
			'#markup' => $markup,
			'#cache' => array(
							'max-age' => 0,
							 ),
      '#attached' => array(
          'library' => array(
            'udg_liston/udg_media',
            ),
        )
    );



    }



}

?>
