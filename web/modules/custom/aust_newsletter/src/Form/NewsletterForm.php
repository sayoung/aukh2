<?php

namespace Drupal\aust_newsletter\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\aust_newsletter\Helper\Helper;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;

/**
 * Class MarieForm.
 *
 * @package Drupal\aust_newsletter\Form
 */
class NewsletterForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'aust_newsletter';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['email'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => t('Adresse email')),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('OK'),
      '#ajax' => [
        'callback' => [$this, 'form_ajax_submit'],
        'method' => 'replace',
        'effect' => 'fade'
      ],
    );
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $email = $form_state->getValue('email');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('That e-mail address is not valid.'));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

  }

  /**
   * {@inheritdoc}
   */
public function form_ajax_submit(array &$form, FormStateInterface $form_state) {
    
    $email = $form_state->getValue('email');
    $ajax_response = new AjaxResponse();
    $msg = "";

    $user = Helper::checkEmail($email);
    if(empty($email) ){
      $msg ="<span class=\"error\">" .t( "Merci de remplire le champs avec votre adresse E-mail") .".</span>";
    }else if(!$user){
      $query = \Drupal::database();
      $query ->insert('aust_newsletter_emails')
         ->fields(['email' => $email])
         ->execute();
      $msg ="<span class=\"success\"> " .t("Votre adresse a été enregistrée") . ". </span>";
    }else{
      $msg ="<span class=\"error\"> " .t("L'adresse email") . " '" . $email . "' " .t("existe déjà") . ".</span>";
    }

    $ajax_response->addCommand(new OpenModalDialogCommand(t('Newsletter'), $msg, ['width' => '400']));
    return $ajax_response;
  }

}
