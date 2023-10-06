<?php

/**
 * @file
 * Contains \Drupal\gavias_view\Plugin\views\style\Careers.
 */

namespace Drupal\gavias_view\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
/**
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "careers",
 *   title = @Translation("DARDEV Careers"),
 *   help = @Translation("Displays items as Careers."),
 *   theme = "views_view_careers",
 *   display_types = {"normal"}
 * )
 */
class careers extends StylePluginBase {

  /**
   * Does the style plugin allows to use style plugins.
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Does the style plugin support custom css class for the rows.
   *
   * @var bool
   */
  protected $usesRowClass = TRUE;

  /**
   * Set default options
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $settings = gavias_view_careers_default_settings();
    foreach ($settings as $k => $v) {
      $options[$k] = array('default' => $v);
    }
    return $options;
  }

  /**
   * Render the given style.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['items'] = array(
      '#type' => 'select',
      '#title' => $this->t('Items'),
      '#description' => $this->t('Number Items Show.'),
      '#default_value' => $this->options['items'],
      '#options' => array('1'=> 1, '2'=> 2, '3'=>3, '4'=> 4),
    );
    $form['items_lg'] = array(
      '#type' => 'select',
      '#title' => $this->t('Nubmer Items for Desktop'),
      '#options' => array('1'=> 1, '2'=> 2, '3'=>3, '4'=> 4),
      '#default_value' => $this->options['items_lg'],
    );
    $form['items_md'] = array(
      '#type' => 'select',
      '#title' => $this->t('Number Items for Desktop Small'),
      '#options' => array('1'=> 1, '2'=> 2, '3'=>3, '4'=> 4),
      '#default_value' => $this->options['items_md'],
    );
    $form['items_sm'] = array(
      '#type' => 'select',
      '#title' => $this->t('Number Items for Tablet'),
      '#options' => array('1'=> 1, '2'=> 2, '3'=>3, '4'=> 4),
      '#default_value' => $this->options['items_sm'],
    );
    $form['items_xs'] = array(
      '#type' => 'select',
      '#title' => $this->t('Number Items for Mobile'),
      '#options' => array('1'=> 1, '2'=> 2, '3'=>3, '4'=> 4),
      '#default_value' => $this->options['items_xs'],
    );
    
    $form['el_class'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Extra class name'),
      '#default_value' => $this->options['el_class'],
    );
    $form['el_id'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Extra id name'),
      '#default_value' => $this->options['el_id'],
    );
  }
}
