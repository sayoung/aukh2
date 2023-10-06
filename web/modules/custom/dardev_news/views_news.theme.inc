<?php

/**
 * @file
 * Preprocessors and helper functions to make theming easier.
 */

use Drupal\dardev_news\ViewsNews;
use Drupal\Core\Template\Attribute;


/**
 * Prepares variables for views latest news templates.
 *
 * Default template: views-news.html.twig.
 *
 * @param array $vars
 *   An associative array containing:
 *   - view: A ViewExecutable object.
 *   - rows: The raw row data.
 */
 
function template_preprocess_views_news(array &$vars) {
    $view = $vars['view'];
    $vars['id'] = ViewsNews::getUniqueId($view);
  
    // card rows.
    $image = $view->style_plugin->options['image_field'];
    $title = $view->style_plugin->options['title_field'];
    $url = $view->style_plugin->options['url_field'];
    $body = $view->style_plugin->options['body_field'];
    $date_created = $view->style_plugin->options['date_created_field'];
    $ar_created_field = $view->style_plugin->options['ar_created_field'];
  
    foreach ($vars['rows'] as $id => $row) {
      $vars['rows'][$id] = [];
      $vars['rows'][$id]['image'] = $view->style_plugin->getField($id, $image);
      $vars['rows'][$id]['title'] = $view->style_plugin->getField($id, $title);
      $vars['rows'][$id]['url'] = $view->style_plugin->getField($id, $url);
      $vars['rows'][$id]['body'] = $view->style_plugin->getField($id, $body);
      $vars['rows'][$id]['date_created'] = $view->style_plugin->getField($id, $date_created);
      $vars['rows'][$id]['ar_created_field'] = $view->style_plugin->getField($id, $ar_created_field);
    }
}

function template_preprocess_views_news_page(array &$vars) {
  $view = $vars['view'];
  $vars['id'] = ViewsNews::getUniqueId($view);

  // card rows.
  $image = $view->style_plugin->options['image_field'];
  $title = $view->style_plugin->options['title_field'];
  $url = $view->style_plugin->options['url_field'];
  $body = $view->style_plugin->options['body_field'];
  $date_created = $view->style_plugin->options['date_created_field'];
  

  foreach ($vars['rows'] as $id => $row) {
    $vars['rows'][$id] = [];
    $vars['rows'][$id]['image'] = $view->style_plugin->getField($id, $image);
    $vars['rows'][$id]['title'] = $view->style_plugin->getField($id, $title);
    $vars['rows'][$id]['url'] = $view->style_plugin->getField($id, $url);
    $vars['rows'][$id]['body'] = $view->style_plugin->getField($id, $body);
    $vars['rows'][$id]['date_created'] = $view->style_plugin->getField($id, $date_created);
    
    
  }
}



function template_preprocess_views_webinar_series(array &$vars) {
    $view = $vars['view'];
    $vars['id'] = ViewsNews::getUniqueId($view);
  
    // card rows.
    $image = $view->style_plugin->options['image_field'];
    $title = $view->style_plugin->options['title_field'];
    $url = $view->style_plugin->options['url_field'];
    $body = $view->style_plugin->options['body_field'];
    $date_created = $view->style_plugin->options['date_created_field'];
    $animateur = $view->style_plugin->options['animateur_field'];
  
    foreach ($vars['rows'] as $id => $row) {
      $vars['rows'][$id] = [];
      $vars['rows'][$id]['image'] = $view->style_plugin->getField($id, $image);
      $vars['rows'][$id]['title'] = $view->style_plugin->getField($id, $title);
      $vars['rows'][$id]['url'] = $view->style_plugin->getField($id, $url);
      $vars['rows'][$id]['body'] = $view->style_plugin->getField($id, $body);
      $vars['rows'][$id]['date_created'] = $view->style_plugin->getField($id, $date_created);
      $vars['rows'][$id]['animateur'] = $view->style_plugin->getField($id, $animateur);
    }
}
function template_preprocess_views_vient_paraitre(array &$vars) {
  $view = $vars['view'];
  $vars['id'] = ViewsNews::getUniqueId($view);

  // card rows.
  $image = $view->style_plugin->options['image_field'];
  $title = $view->style_plugin->options['title_field'];
  $url = $view->style_plugin->options['url_field'];
  $body = $view->style_plugin->options['body_field'];
  $date_created = $view->style_plugin->options['date_created_field'];

  foreach ($vars['rows'] as $id => $row) {
    $vars['rows'][$id] = [];
    $vars['rows'][$id]['image'] = $view->style_plugin->getField($id, $image);
    $vars['rows'][$id]['title'] = $view->style_plugin->getField($id, $title);
    $vars['rows'][$id]['url'] = $view->style_plugin->getField($id, $url);
    $vars['rows'][$id]['body'] = $view->style_plugin->getField($id, $body);
    $vars['rows'][$id]['date_created'] = $view->style_plugin->getField($id, $date_created);
  }
}

function template_preprocess_views_activite(array &$vars) {
    $view = $vars['view'];
    $vars['id'] = ViewsNews::getUniqueId($view);
  
    // card rows.
    $image = $view->style_plugin->options['image_field'];
    $title = $view->style_plugin->options['title_field'];
    $url = $view->style_plugin->options['url_field'];
    $body = $view->style_plugin->options['body_field'];
    $date_created = $view->style_plugin->options['date_created_field'];
    $ar_created_field = $view->style_plugin->options['ar_created_field'];
  
    foreach ($vars['rows'] as $id => $row) {
      $vars['rows'][$id] = [];
      $vars['rows'][$id]['image'] = $view->style_plugin->getField($id, $image);
      $vars['rows'][$id]['title'] = $view->style_plugin->getField($id, $title);
      $vars['rows'][$id]['url'] = $view->style_plugin->getField($id, $url);
      $vars['rows'][$id]['body'] = $view->style_plugin->getField($id, $body);
      $vars['rows'][$id]['date_created'] = $view->style_plugin->getField($id, $date_created);
      $vars['rows'][$id]['ar_created_field'] = $view->style_plugin->getField($id, $ar_created_field);
    }
}