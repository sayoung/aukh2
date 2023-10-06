<?php

namespace Drupal\twig_truncate\TwigExtension;

use Drupal\Component\Utility\Unicode;

/**
 * Class DefaultService.
 *
 * @package Drupal\truncate
 */
class Truncate extends \Twig_Extension {

    /**
     *
     */
    public function getName() {
        return 'twig_truncate.twig_extension';
    }

    /**
     * {@inheritdoc}
     * This function must return the name of the extension. It must be unique.
     */
    public function getFilters() {
        return [
            new \Twig_SimpleFilter('truncate', array($this, 'truncate'))
        ];
   }

    /**
     * Wrapper for Drupal\Component\Utility\Unicode::truncate()
     */
    public function truncate($string, $max_length, $wordsafe = TRUE, $add_ellipsis = TRUE, $min_word_safe_length = 1) {
        return Unicode::truncate($string, $max_length, $wordsafe, $add_ellipsis, $min_word_safe_length);
    }
}
