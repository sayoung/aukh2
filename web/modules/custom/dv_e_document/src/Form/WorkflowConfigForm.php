<?php

namespace Drupal\dv_e_document\Form;

use Drupal\dv_e_document\Helper\WorkflowEmail;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
/**
 * Flatchr settings form.
 */
class WorkflowConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'workflow_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      WorkflowEmail::MAILSETTINS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(WorkflowEmail::MAILSETTINS);



    $form['mail']['sendMailWorflow'] = array(
      '#title' => $this->t('Email Standart'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => 'Les variables : [email_client], [nom_complet] , [code],[province], [n_command],[etape] ',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('send Mail Worflow')),
      '#default_value' => $config->get('sendMailWorflow')['value'],
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


	$form['mail']['emailcci'] = array(
      '#title' => $this->t('Mail en cci'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('mail en cci')),
      '#default_value' => $config->get('emailcci'),

    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

       $this->configFactory->getEditable(WorkflowEmail::MAILSETTINS)
      ->set('sendMailWorflow', $form_state->getValue('sendMailWorflow'))
      ->set('footer1', $form_state->getValue('footer1'))
      ->set('footer2', $form_state->getValue('footer2'))
	    ->set('emailcci', $form_state->getValue('emailcci'))
      ->save();

    parent::submitForm($form, $form_state);
  }


}
