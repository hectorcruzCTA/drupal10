<?php

namespace Drupal\udg_liston\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
/**
 * Definición de nuestro bloque
 *
 * @Block(
 *   id = "Slide_udg",
 *   admin_label = @Translation("Slide UDG Video")
 * )
 */
class SliderBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */

    public function build() { 

		//kint($result);
		$nids = \Drupal::entityQuery('node')->accessCheck(TRUE)->condition('status', 1)->condition('type','slideshow')->execute();
		$nids2 =  \Drupal::entityQuery('node')->accessCheck(TRUE)->condition('status', 1)->condition('type','video')->execute();
		$nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
		$nodesVideo =  \Drupal\node\Entity\Node::loadMultiple($nids2);
		$markup = "";
		$content = array();
		$body= '';
		//kint($nodes);
		//kint($nodesVideo);

		foreach ($nodes as $node){
			$url = '';
               //Imagen
			//kint($node->id());
               //kint($node->get('body')->value);
			//$markup .= ' '.$node->getChangedTime();
			$dateT = $node->getChangedTime();
			$body = $node->get('body')->value;
               if(isset($node->get('field_link')->getValue()['0']['uri'])){
                    $url = $node->get('field_link')->getValue()['0']['uri'];
                    $url = str_replace("internal:","",$url);
                    //dsm($url);
               }
               $nod= $node->id();
               if(isset($node->get('field_mostrar_en_video_slideshow')->getValue()['0']['value'])){
                  //kint($node->get('field_mostrar_en_video_slideshow')->getValue());
                  if($node->get('field_mostrar_en_video_slideshow')->getValue()['0']['value'] == 1){
                         foreach($node->get('field_imagen_slideshow')->getValue() as $file){
                         //kint($file);
                              $fid = $file['target_id']; // get file fid;
                              //kint($fid);
                              $photo = \Drupal::service('file_url_generator')->generateAbsoluteString(\Drupal\file\Entity\File::load($fid)->getFileUri());
                              //kint($file['alt']);
                              $alt=$file['alt'];
                              $title = $file['title'];
                              //kint($photo);
                              //dsm(file_create_url($photo));
                              if($url != ''){
                              array_push($content, array('Date'=>$dateT,'Content'=>'
                              <div class="item node-'.$nod.'">
                                   <div class="align-center">
                                        <a href="'.$url.'" tabindex="-1">
                                            <img alt="'.$alt.'" title="'.$title.'" src="'.$photo.'" typeof="foaf:Image" class="img-responsive"/>
                                        </a>
                                   </div>
                                   <div class="carousel-caption">
                                        '.$body.'
                                   </div>
                              </div>'));
                              }else{
                                   array_push($content, array('Date'=>$dateT,'Content'=>'
                              <div class="item node-'.$nod.'">
                                   <div class="align-center">
                                        <img alt="'.$alt.'" title="'.$title.'" src="'.$photo.'" typeof="foaf:Image" class="img-responsive"/>
                                   </div>
                                   <div class="carousel-caption">
                                        '.$body.'
                                   </div>
                              </div>'));
                              }
                         }
                    }
               }


		}

		foreach ($nodesVideo as $node){
			//kint($node);
			//$markup .= ' '.$node->getChangedTime();
			//kint($node->get('body')->value);
			$dateV = $node->getChangedTime();
			$body = $node->get('body')->value;
               $nod= $node->id();
			foreach($node->get('field_video')->getValue() as $file){
				//kint($file);
				$fid = $file['target_id'];
				$video = \Drupal::service('file_url_generator')->generateAbsoluteString(\Drupal\file\Entity\File::load($fid)->getFileUri());
				//kint($video);
				array_push($content, array('Date'=>$dateV,'Content'=>'
				<div class="item node-'.$nod.'">
					<div class="align-center">
						<video class="videoSlide" autoplay="autoplay" muted="muted" loop="loop">
							<source src="'.$video.'" type="video/mp4">
							Su navegador no soporta video
						</video>
					</div>
					<div class="carousel-caption">
		              '.$body.'
		            </div>
				</div>'));
			}

		}


		rsort($content);
		//kint($content);
		//kint(str_replace('class="item','class="item active',$content[0]['Content']));
		//$content[0] = str_replace('class="item','class="item active',$content[0]['Content']);

		//kint($content);
		//$markup .= '<div id="udgCarousel" class="carousel slide" data-ride="carousel">';
		//$markup .= '<div class="carousel-inner">';

		$markup .= '<div id="carousel-udg" class="carousel slide" data-ride="carousel">';

		/*$markup .= '<ol class="carousel-indicators">';
                         		$N = 0;
                         		foreach ($content as $contentT){
                         			if ($N == 0) {
                         				$markup.= '<li data-target="#carousel-udg" data-slide-to="'.$N.'" class="active"></li>';
                         			}else{
                         				$markup.= '<li data-target="#carousel-udg" data-slide-to="'.$N.'"></li>';
                         			}


                         			$N++;
                         		}

		                  $markup .=	'</ol> ';*/
          $markup .= '<!-- Wrapper for slides -->
                         <div class="carousel-inner" role="listbox"> ';

		$i = 0;
		foreach ($content as $contenti){
			if ($i == 0) {
				$contenti['Content'] = str_replace('class="item','class="item active',$contenti['Content']);
    		}
			//kint($contenti['Content']);
			$markup .= $contenti['Content'];
			$i++;
		}
		//$markup .= '</div>';
        //$markup .= '</div>';
        $markup .= '<a class="left carousel-control" href="#carousel-udg" role="button" data-slide="prev">
				        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				        <span class="sr-only">Previous</span>
				      </a>
				      <a class="right carousel-control" href="#carousel-udg" role="button" data-slide="next">
				        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				        <span class="sr-only">Next</span>
				      </a>
                          <div id="carouselButtons-Slideshow-Video">
                               <button id="playButtonVSlide" type="button" class="btn btn-default btn-xs">
                                   <span class="glyphicon glyphicon-play"> Reproducir</span>
                                </button>
                               <button id="pauseButtonVSlide" type="button" class="btn btn-default btn-xs">
                                   <span class="glyphicon glyphicon-pause"> Detener</span>
                               </button>
                           </div>

				   </div>
                    </div>';
		//kint( $markup);
    	return array(
	    	'#type' => 'markup',
				'#markup' => $markup,
				'#cache' => array(
					'max-age' => 3,
					),
				'#attached' => array(
					'library' => array(
						'udg_liston/udg_slideshow',
						),
				),
				'#allowed_tags' => [
          'a',
          'article',
          'aside',
          'body',
          'br',
          'details',
          'div',
          'h1',
          'h2',
          'h3',
          'h4',
          'h5',
          'h6',
          'head',
          'header',
          'hgroup',
          'hr',
          'html',
          'footer',
          'nav',
          'p',
          'section',
          'span',
          'sumary',
          'base',
          'basefont',
          'meta',
          'style',
          'title',
          'button',
          'datalist',
          'fieldset',
          'form',
          'input',
          'keygen',
          'label',
          'legend',
          'meter',
          'optgroup',
          'option',
          'select',
          'textarea',
          'abbr',
          'acronym',
          'address',
          'bdi',
          'bdo',
          'big',
          'blockquote',
          'center',
          'cite',
          'code',
          'del',
          'dfn',
          'em',
          'front',
          'em',
          'font',
          'i',
          'ins',
          'kbd',
          'mark',
          'output',
          'pre',
          'progress',
          'q',
          'rp',
          'rt',
          'ruby',
          's',
          'samp',
          'small',
          'strike',
          'sub',
          'tt',
          'u',
          'var',
          'wbr',
          'dd',
          'dir',
          'dl',
          'dt',
          'li',
          'ol',
          'menu',
          'ul',
          'caption',
          'col',
          'colgroup',
          'table',
          'tbody',
          'td',
          'tfoot',
          'thead',
          'th',
          'tr',
          'noscript',
          'script',
          'applet',
          'area',
          'audio',
          'canvas',
          'embed',
          'figcaption',
          'figure',
          'frame',
          'frameset',
          'iframe',
          'img',
          'map',
          'noframes',
          'object',
          'param',
          'source',
          'video',
					],
			);
    }



}

?>
