<?php

namespace Drupal\dardev_news\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\Component\Utility\Html;

/**
 * Style plugin to render each item in an ordered or unordered list.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "views_news_page",
 *   title = @Translation("INRA Latest News Page"),
 *   help = @Translation("Displays rows in a views Latest News Page"),
 *   theme = "views_news_page",
 *   theme_file = "/../views_news.theme.inc",
 *   display_types = {"normal"}
 * )
 */
class NewsPage extends StylePluginBase {
  /**
   * Does the style plugin for itself support to add fields to it's output.
   *
   * @var bool
   */
  protected $usesFields = TRUE;

  /**
   * Does the style plugin allows to use style plugins.
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Definition.
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['title_field'] = ['default' => NULL];
    $options['body_field'] = ['default' => NULL];
    $options['image_field'] = ['default' => NULL];
    $options['url_field'] = ['default' => NULL];
    $options['date_created_field'] = ['default' => NULL];
    
    return $options;
  }

  /**
   * Render the given style.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    if (isset($form['grouping'])) {
      unset($form['grouping']);
      $form['title_field'] = [
        '#type' => 'select',
        '#title' => $this->t('title field'),
        '#options' => $this->displayHandler->getFieldLabels(TRUE),
        '#required' => TRUE,
        '#default_value' => $this->options['title_field'],
        '#description' => $this->t('Select the field that will be used for the title.'),
      ];
      $form['body_field'] = [
        '#type' => 'select',
        '#title' => $this->t('body field'),
        '#options' => $this->displayHandler->getFieldLabels(TRUE),
        '#required' => TRUE,
        '#default_value' => $this->options['body_field'],
        '#description' => $this->t('Select the field that will be used for the body.'),
      ];
      $form['image_field'] = [
        '#type' => 'select',
        '#title' => $this->t('image field'),
        '#options' => $this->displayHandler->getFieldLabels(TRUE),
        '#required' => TRUE,
        '#default_value' => $this->options['image_field'],
        '#description' => $this->t('Select the field that will be used for the image.'),
      ];
      $form['url_field'] = [
        '#type' => 'select',
        '#title' => $this->t('Url field'),
        '#options' => $this->displayHandler->getFieldLabels(TRUE),
        '#required' => TRUE,
        '#default_value' => $this->options['url_field'],
        '#description' => $this->t('Select the field that will be used for the content.'),
      ];
      
      $form['date_created_field'] = [
        '#type' => 'select',
        '#title' => $this->t('date de creation field'),
        '#options' => $this->displayHandler->getFieldLabels(TRUE),
        '#required' => TRUE,
        '#default_value' => $this->options['date_created_field'],
        '#description' => $this->t('Select the field that will be used for the created date.'),
      ];
    }
  }

}
