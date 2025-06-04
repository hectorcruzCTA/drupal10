<?php

declare(strict_types=1);

namespace Drupal\animated_gif;

use Drupal\Component\Utility\Bytes;
use Drupal\file\FileInterface;

/**
 * Utilities for Animated Gif module.
 */
class AnimatedGifUtility {

  /**
   * The minimum number of animated frames required.
   */
  public const MINIMUM_NUMBER_OF_ANIMATED_FRAMES = 2;

  /**
   * Check if a file entity is an animated Gif.
   *
   * @param mixed $file
   *   The file variable to check.
   *
   * @return bool
   *   Return true if file is animated.
   */
  public static function isFileAnAnimatedGif(mixed $file): bool {
    if (!$file instanceof FileInterface) {
      return FALSE;
    }

    if ($file->getMimeType() != 'image/gif') {
      return FALSE;
    }

    $file_uri = $file->getFileUri();
    if (!\is_string($file_uri)) {
      return FALSE;
    }

    return AnimatedGifUtility::isAnAnimatedGif($file_uri);
  }

  /**
   * Check if a gif image is animated.
   *
   * @param string $fileUri
   *   The uri file.
   *
   * @return bool
   *   Return true if file contains multiple "frames".
   *
   * @SuppressWarnings(PHPMD.ErrorControlOperator)
   */
  public static function isAnAnimatedGif(string $fileUri): bool {
    if (!\file_exists($fileUri)) {
      \Drupal::logger('animated_gif')->error('File @file_uri not found.', [
        '@file_uri' => $fileUri,
      ]);
      return FALSE;
    }

    $fopen = \fopen($fileUri, 'rb');
    if (!$fopen) {
      return FALSE;
    }
    $count = 0;
    // An animated gif contains multiple "frames", with each frame having a
    // header made up of:
    // * a static 4-byte sequence (\x00\x21\xF9\x04)
    // * 4 variable bytes
    // * a static 2-byte sequence (\x00\x2C)
    // We read through the file til we reach the end of the file, or we've found
    // at least 2 frame headers.
    while (!\feof($fopen) && $count < self::MINIMUM_NUMBER_OF_ANIMATED_FRAMES) {
      // Read 100kb at a time.
      $chunk = \fread($fopen, Bytes::KILOBYTE * (int) 100);
      if (!$chunk) {
        break;
      }
      $count += \preg_match_all('#\x00\x21\xF9\x04.{4}\x00[\x2C\x21]#s', (string) $chunk);
    }

    \fclose($fopen);
    return $count > 1;
  }

}
