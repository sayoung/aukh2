<?php

namespace Drupal\webform_mail_custom\Plugin\WebformHandler;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\Core\Form\FormStateInterface;

use Drupal\node\Entity\Node;

use Drupal\webform\WebformInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\Entity\WebformSubmission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\taxonomy\Entity\Term;
use Drupal\webform_mail_custom\Helper\Helper;

/**
 * Creates a new node from Webform pre instruction Submissions.
 *
 * @WebformHandler(
 *   id = "Create pre instruction",
 *   label = @Translation("Create a node from pre instruction"),
 *   category = @Translation("Entity Creation"),
 *   description = @Translation("Creates a new node from Webform pre instruction Submissions."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */

class pre_instruction extends WebformHandlerBase {
  
  /**
   * {@inheritdoc}
   */
     /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'states' => [WebformSubmissionInterface::STATE_COMPLETED],
      'notes' => '',
      'sticky' => NULL,
      'locked' => NULL,
      'data' => '',
      'message' => '',
      'message_type' => 'status',
      'debug' => FALSE,
    ];
  }
     /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $results_disabled = $this->getWebform()->getSetting('results_disabled');

    $form['trigger'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Trigger'),
    ];
    $form['trigger']['states'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Execute'),
      '#options' => [
        WebformSubmissionInterface::STATE_DRAFT => $this->t('...when <b>draft</b> is saved.'),
        WebformSubmissionInterface::STATE_CONVERTED => $this->t('...when anonymous submission is <b>converted</b> to authenticated.'),
        WebformSubmissionInterface::STATE_COMPLETED => $this->t('...when submission is <b>completed</b>.'),
        WebformSubmissionInterface::STATE_UPDATED => $this->t('...when submission is <b>updated</b>.'),
      ],
      '#required' => TRUE,
      '#access' => $results_disabled ? FALSE : TRUE,
      '#default_value' => $results_disabled ? [WebformSubmissionInterface::STATE_COMPLETED] : $this->configuration['states'],
    ];

    $form['actions'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Actions'),
    ];
    $form['actions']['sticky'] = [
      '#type' => 'select',
      '#title' => $this->t('Change status'),
      '#empty_option' => $this->t('- None -'),
      '#options' => [
        '1' => $this->t('Flag/Star'),
        '0' => $this->t('Unflag/Unstar'),
      ],
      '#default_value' => ($this->configuration['sticky'] === NULL) ? '' : ($this->configuration['sticky'] ? '1' : '0'),
    ];
    $form['actions']['locked'] = [
      '#type' => 'select',
      '#title' => $this->t('Change lock'),
      '#description' => $this->t('Webform submissions can only be unlocked programatically.'),
      '#empty_option' => $this->t('- None -'),
      '#options' => [
        '' => '',
        '1' => $this->t('Lock'),
        '0' => $this->t('Unlock'),
      ],
      '#default_value' => ($this->configuration['locked'] === NULL) ? '' : ($this->configuration['locked'] ? '1' : '0'),
    ];
    $form['actions']['notes'] = [
      '#type' => 'webform_codemirror',
      '#mode' => 'text',
      '#title' => $this->t('Append the below text to notes (Plain text)'),
      '#default_value' => $this->configuration['notes'],
    ];
    $form['actions']['message'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Display message'),
      '#default_value' => $this->configuration['message'],
    ];
    $form['actions']['message_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Display message type'),
      '#options' => [
        'status' => t('Status'),
        'error' => t('Error'),
        'warning' => t('Warning'),
        'info' => t('Info'),
      ],
      '#default_value' => $this->configuration['message_type'],
    ];
    $form['actions']['data'] = [
      '#type' => 'webform_codemirror',
      '#mode' => 'yaml',
      '#title' => $this->t('Update the below submission data. (YAML)'),
      '#default_value' => $this->configuration['data'],
    ];

    $elements_rows = [];
    $elements = $this->getWebform()->getElementsInitializedFlattenedAndHasValue();
    foreach ($elements as $element_key => $element) {
      $elements_rows[] = [
        $element_key,
        (isset($element['#title']) ? $element['#title'] : ''),
      ];
    }
    $form['actions']['elements'] = [
      '#type' => 'details',
      '#title' => $this->t('Available element keys'),
      'element_keys' => [
        '#type' => 'table',
        '#header' => [$this->t('Element key'), $this->t('Element title')],
        '#rows' => $elements_rows,
      ],
    ];
    $form['actions']['token_tree_link'] = $this->tokenManager->buildTreeLink();

    // Development.
    $form['development'] = [
      '#type' => 'details',
      '#title' => $this->t('Development settings'),
    ];
    $form['development']['debug'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable debugging'),
      '#description' => $this->t('If checked, trigger actions will be displayed onscreen to all users.'),
      '#return_value' => TRUE,
      '#default_value' => $this->configuration['debug'],
    ];

    $this->tokenManager->elementValidate($form);

    return $this->setSettingsParentsRecursively($form);
  }

     /**
   * {@inheritdoc}
   */
   
     public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    $state = $webform_submission->getWebform()->getSetting('results_disabled') ? WebformSubmissionInterface::STATE_COMPLETED : $webform_submission->getState();
    if (in_array($state, $this->configuration['states'])) {
      $this->executeAction($webform_submission);
    }
  }
  
  protected function executeAction(WebformSubmissionInterface $webform_submission) {
     $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $submission_array = $webform_submission->getData();
 $id_array = $webform_submission->getOwnerId();
  $account = \Drupal\user\Entity\User::load($id_array);
 // $name = $account->getUsername();
 // $archi_mail = $account->getEmail();
  // $tel = $account->get("field_numero_de_telephone")->value;
   
    // Before you actually save the node, make sure you get what you
    // want. So at first comment the line further down with 
    // $node->save(), run the debug code, check the output and then
    // uncomment the $node->save() and delete the debug foreach loop.
    // You can use whatever debug method you prefer, this is just a
    // simple one-time example.
   //  $values = $webform_submission->getData();

	$recurtement = \Drupal::routeMatch()->getRawParameter('node');
	$name = $submission_array['nom_complet'];
    $archi_mail = $submission_array['e_mail_'];
    $tel = $submission_array['telephone'];
	if (!empty($submission_array['commune'])) {$commune = $submission_array['commune'];}
	$nature_du_projet_envisage = $submission_array['nature_du_projet_envisage'];
	$situation_du_projet_ = $submission_array['situation_du_projet'];
	$nom_prenom_du_proprietaire_ = $submission_array['nom_prenom_du_proprietaire_'];
	$references_foncieres = $submission_array['references_foncieres'];
// -------------------------- -------------------------------	
	$accord_de_proprietaire_fid = $submission_array['accord_de_proprietaire'];
    $carte_d_identite_nationale_scannee_pdf_fid = $submission_array['carte_d_identite_nationale_scannee_pdf'];
	$Justificatif_propriete_fid = $submission_array['justificatif_de_propriete_certificat'];
	
	
	
	
	
	$uuid_note =  time();
// Create file PDF CIN.
// Create file PDF accord_de_proprietaire. // c  ok 
    if (!empty($accord_de_proprietaire_fid)) {
      $file = \Drupal\file\Entity\File::load($accord_de_proprietaire_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_accord = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF $node_plans_architectureaux_facades // c  ok 
	    if (!empty($carte_d_identite_nationale_scannee_pdf_fid)) {
      $file = \Drupal\file\Entity\File::load($carte_d_identite_nationale_scannee_pdf_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_carte_d_identite_nationale_scannee_pdf = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF justificatif_de_propriete_certificat. // c  ok 
    if (!empty($Justificatif_propriete_fid)) {
      $file = \Drupal\file\Entity\File::load($Justificatif_propriete_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $Justificatif_propriete_pdf_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }


	



    // This is the node creating/saving part.
	 $node = Node::create([
 	  'type' =>  'pre_instruction',
	  'uid' => 1,
	  'title' => 'Demande de la part : ' . $name . ' '   ,
      'field_commune' => $commune,
      'field_nature_du_projet_envisage' => $nature_du_projet_envisage,
      'field_statut_foncier' => $situation_du_projet_,
      'field_nom' => $nom_prenom_du_proprietaire_,
      'field_references_foncieres' => $references_foncieres,
      'field_email_' => $archi_mail,
      'field_ndeg_tel' => $tel,
	  
	  
	  
	  ///////////////////////////////
	  // c  ok 
	  'field_justificatif_de_propriete' => [
        'target_id' => (!empty($Justificatif_propriete_pdf_file) ? $Justificatif_propriete_pdf_file->id() : NULL),
        'alt' => 'Attestation de propriété (Certificat de propriété, Acte adulaire, etc.)',
        'title' => 'Attestation de propriété (Certificat de propriété, Acte adulaire, etc.)'
      ],
	  // c ok 
      'field_accord_de_proprietaire' => [
        'target_id' => (!empty($node_pdf_accord) ? $node_pdf_accord->id() : NULL),
        'alt' => 'Plans architectureaux (Plan de masse)',
        'title' => 'Plans architectureaux (Plan de masse)'
      ],
      // c ok 
      'field_carte_d_identite_nationale' => [
        'target_id' => (!empty($node_carte_d_identite_nationale_scannee_pdf) ? $node_carte_d_identite_nationale_scannee_pdf->id() : NULL),
        'alt' => 'Plan cadastral avec calcul de contenances ou Levé topographique',
        'title' => 'Plan cadastral avec calcul de contenances ou Levé topographique'
      ],
      
	 
	  
	  
	  
	  'field_cheking' => 1,
	  'field_uuid' => $uuid_note,
                		]);
					  $node->save();


	
  }
    // Resave the webform submission without trigger any hooks or handlers.
   // $webform_submission->resave();

    // Display debugging information about the current action.
  //  $this->displayDebug($webform_submission);
  

  }

