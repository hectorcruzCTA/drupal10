<?php

declare(strict_types=1);

namespace Drupal\animated_gif\Plugin\Field\FieldFormatter;

use Drupal\animated_gif\AnimatedGifUtility;
use Drupal\Core\Field\Attribute\FieldFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\image\Plugin\Field\FieldFormatter\ImageUrlFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'image_url' formatter for animated_gif.
 */
#[FieldFormatter(
  id: 'animated_gif_image_url',
  label: new TranslatableMarkup('Animated GIF URL to image'),
  field_types: [
    'image',
  ],
)]
class AnimatedGifImageUrlFormatter extends ImageUrlFormatter implements ContainerFactoryPluginInterface {

  /**
   * The file URL generator.
   *
   * @var \Drupal\Core\File\FileUrlGeneratorInterface
   */
  protected $fileUrlGenerator;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->fileUrlGenerator = $container->get('file_url_generator');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);

    /** @var \Drupal\Core\Field\EntityReferenceFieldItemListInterface $items */
    $images = $this->getEntitiesToView($items, $langcode);
    if (empty($images)) {
      // Early opt-out if the field is empty.
      return $elements;
    }

    /** @var \Drupal\file\FileInterface[] $images */
    foreach ($images as $delta => $image) {
      if (AnimatedGifUtility::isFileAnAnimatedGif($image)) {
        $image_uri = $image->getFileUri();
        if ($image_uri != NULL) {
          // No image style is wanted for animated gifs.
          $url = $this->fileUrlGenerator->transformRelative($this->fileUrlGenerator->generateString($image_uri));
          $elements[$delta]['#markup'] = $url;
        }
      }
    }
    return $elements;
  }

}
