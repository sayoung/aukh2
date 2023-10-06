<?php

namespace Drupal\dardev_appel_offre\Form;

use Drupal\dardev_appel_offre\Helper\Helper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
 
/**
 * Flatchr settings form.
 */
class AppelOffreConfigForm extends ConfigFormBase {
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'appel_offre_conf_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      Helper::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(Helper::SETTINGS);
    

    
    $form['subject'] = array(
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Subject')),
      '#default_value' => $config->get('subject'),
 
    );
    $form['intro'] = array(
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Header')),
      '#default_value' => $config->get('intro')['value'],
    );

    $form['mail']['footer1'] = array(
      '#title' => $this->t('Footer 1'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Footer 1')),
      '#default_value' => $config->get('footer1'),

    );
    $form['mail']['footer2'] = array(
      '#title' => $this->t('Footer 2'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Footer 2')),
      '#default_value' => $config->get('footer2'),

    );
    
    $form['open_modal'] = [
      '#type' => 'link',
      '#title' => $this->t('Send'),
      '#url' => Url::fromRoute('modal_form_send.open_modal_form'),
      '#attributes' => [
        'class' => [
          'use-ajax',
          'button',
        ],
      ],
    ];
    
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
       $this->configFactory->getEditable(Helper::SETTINGS)
      ->set('subject', $form_state->getValue('subject'))
      ->set('intro', $form_state->getValue('intro'))
      ->set('footer1', $form_state->getValue('footer1'))
      ->set('footer2', $form_state->getValue('footer2'))
      ->save();

    parent::submitForm($form, $form_state);
  }
  

}
