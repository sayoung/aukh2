<?php

namespace Drupal\md_new_prestation\Form;

use Drupal\md_new_prestation\Helper\Helper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
/**
 * Flatchr settings form.
 */
class InstructionConfigIorm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'i_instruction_conf_settings_form_2';
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

    $form['imail'] = array(
      '#type' => 'fieldset',
      '#title' => $this
        ->t('Mail'),
    );
    $form['isms'] = array(
      '#type' => 'fieldset',
      '#title' => $this
        ->t('SMS'),
    );
    $form['imail']['isubject'] = array(
      '#title' => $this->t('Subject'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Subject')),
      '#default_value' => $config->get('isubject'),

    );
	/*
	$form['imail']['imessage_mail_veri'] = array(
      '#title' => $this->t('Message De validation de demande (E-mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail')),
      '#default_value' => $config->get('imessage_mail_veri')['value'],
    );
	*/
	$form['imail']['imessage_mail_first'] = array(
      '#title' => $this->t('Message De validation de demande (E-mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail')),
      '#default_value' => $config->get('imessage_mail_first')['value'],
    );
	$form['imail']['imessage_mail_comptable'] = array(
      '#title' => $this->t('Message De validation de demande (Comptable)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail')),
      '#default_value' => $config->get('imessage_mail_comptable')['value'],
    );
	$form['imail']['imessage_mail_annulation'] = array(
      '#title' => $this->t('Message D annulation de demande (E-mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code d instruction | [commenatire] est une variable sera remplacer par le commenatire d administration',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail')),
      '#default_value' => $config->get('imessage_mail_annulation')['value'],
    );
    $form['imail']['imessage_mail'] = array(
      '#title' => $this->t('Message De confirmation Facture (E-mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail')),
      '#default_value' => $config->get('imessage_mail')['value'],
    );
	$form['imail']['toadministration'] = array(
      '#title' => $this->t('Message De confirmation a l administration'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message De confirmation a l administration')),
      '#default_value' => $config->get('toadministration')['value'],
    );
	$form['imail']['iemailcci'] = array(
      '#title' => $this->t('Mail en cci vers administration'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('mail en cci')),
      '#default_value' => $config->get('iemailcci'),

    );
    $form['imail']['ifooter1'] = array(
      '#title' => $this->t('Footer 1'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Footer 1')),
      '#default_value' => $config->get('ifooter1'),

    );

    $form['imail']['ifooter2'] = array(
      '#title' => $this->t('Footer 2'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Footer 2')),
      '#default_value' => $config->get('ifooter2'),

    );
	$form['isms']['isms_phone_ver'] = array(
      '#title' => $this->t('Message De confirmation demande de Calcul (sms)'),
      '#type' => 'textarea',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message SMS')),
      '#default_value' => $config->get('isms_phone_ver'),
    );
    $form['isms']['isms_phone'] = array(
      '#title' => $this->t('Message De confirmation demande de Calcul (sms)'),
      '#type' => 'textarea',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message SMS')),
      '#default_value' => $config->get('isms_phone'),
    );
	$form['isms']['isms_phone_final'] = array(
      '#title' => $this->t('Message SMS Final'),
      '#type' => 'textarea',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message SMS')),
      '#default_value' => $config->get('isms_phone_final'),
    );
	$form['isms']['isms_phone_annulation'] = array(
      '#title' => $this->t('Message SMS Final'),
      '#type' => 'textarea',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message SMS')),
      '#default_value' => $config->get('isms_phone_annulation'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

       $this->configFactory->getEditable(Helper::SETTINGS)
      ->set('isubject', $form_state->getValue('isubject'))
	  ->set('imessage_mail_first', $form_state->getValue('imessage_mail_first'))
	  ->set('imessage_mail_comptable', $form_state->getValue('imessage_mail_comptable'))
	  ->set('imessage_mail_annulation', $form_state->getValue('imessage_mail_annulation'))
      ->set('imessage_mail', $form_state->getValue('imessage_mail'))
	//  ->set('imessage_mail_veri', $form_state->getValue('imessage_mail_veri'))
      ->set('ifooter1', $form_state->getValue('ifooter1'))
	  ->set('toadministration', $form_state->getValue('toadministration'))
      ->set('ifooter2', $form_state->getValue('ifooter2'))
      ->set('isms_phone', $form_state->getValue('isms_phone'))
	  ->set('isms_phone_final', $form_state->getValue('isms_phone_final'))
	  ->set('isms_phone_ver', $form_state->getValue('isms_phone_ver'))
	  ->set('isms_phone_annulation', $form_state->getValue('isms_phone_annulation'))
	  ->set('iemailcci', $form_state->getValue('iemailcci'))
	  ->save();

    parent::submitForm($form, $form_state);
  }


}
