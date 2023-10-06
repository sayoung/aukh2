<?php

namespace Drupal\dardev_appel_offre\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class NewsletterController.
 *
 * @package Drupal\dardev_appel_offre\Controller
 */
class AppelOffreController extends ControllerBase {

  /**
   * Display.
   *
   * @return string
   *   Return string.
   */
  public function display() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('This page contain all inforamtion about Newsletter ')
    ];
  }

}
