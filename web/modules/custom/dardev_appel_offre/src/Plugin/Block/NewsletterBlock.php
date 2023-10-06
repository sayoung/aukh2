<?php

namespace Drupal\dardev_appel_offre\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'NewsletterBlock' block.
 *
 * @Block(
 *  id = "dardev_appel_offre_block",
 *  admin_label = @Translation("JEB Newsletter block"),
 * )
 */
class NewsletterBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\dardev_appel_offre\Form\NewsletterForm');
    return array(
        '#theme' => 'newsletter',
        '#form' =>  (object)array('form' => $form)
    );
  }

}
