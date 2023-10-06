<?php

namespace Drupal\dardev_appel_offre\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dardev_appel_offre\Helper\Helper;


use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class MarieForm.
 *
 * @package Drupal\dardev_appel_offre\Form
 */
class NewsletterForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dardev_appel_offre';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#prefix'] = '';
    $form['#suffix']  = '';
    $form['email'] = array(
      '#type' => 'textfield',
      '#field_prefix'=> false,
      '#title' => $this->t('Bulletin d\'information '),
      '#prefix' => '<div class="form-inline">',
      
      '#attributes' => array('class' => array('form-control mx-sm-3', '',''), 'placeholder' => t('Adresse email')),
    );
     $form['submit'] = array(
            '#type' => 'submit',
            '#attributes' => array('class' => array('theme-btn btn-style-one')),
            '#value' => t('S\'inscrire'),
            '#suffix' => '</div>', 
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
    $email_validator = \Drupal::service('email.validator');
    assert($email_validator instanceof \Egulias\EmailValidator\EmailValidator);
        if (!$email_validator->isValid($form_state
    ->getValue('email'))) {
    $form_state
      ->setErrorByName('email', $this
      ->t('That e-mail address is not valid.'));
  }

        /* $name = $form_state->getValue('candidate_name');
          if(preg_match('/[^A-Za-z]/', $name)) {
             $form_state->setErrorByName('candidate_name', $this->t('your name must in characters without space'));
          }
*/

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

    $field = $form_state->getValues();
    $email = $field['email'];

    $ajax_response = new AjaxResponse();

    $field  = array(
      'email' =>  $email
    );
    $msg ="";
    $user = Helper::checkEmail($email);
	if(empty($email) ){
		$msg ="<span class=\"error\">Merci de remplire le champs avec votre adresse E-mail.</span>";
	}else if(!$user){
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $query = \Drupal::database();
        $query ->insert('dardev_appel_offre_emails')
           ->fields($field)
           ->execute();
        $msg ="<span class=\"success\"> " .t("Votre adresse a été enregistrée") . "</span>";
      }else{
        $msg ="<span class=\"error\">" .t("L'adresse email") . "  '" . $email . "' " .t("est invalide") . ".</span>";
      }

    }else{
        $msg ="<span class=\"error\"> " .t("L'adresse email") . " '" . $email . "' " .t("existe déjà") . ".</span>";
    } 

    $ajax_response->addCommand(new OpenModalDialogCommand(t('Newsletter'), $msg, ['width' => '400']));
	//$ajax_response->addCommand(new InvokeCommand('Newsletter', $msg, ['width' => '400']));
	//$response->addCommand(new InvokeCommand(NULL, 'customRedirect', ['/']));
  
    return $ajax_response;

   }

}
