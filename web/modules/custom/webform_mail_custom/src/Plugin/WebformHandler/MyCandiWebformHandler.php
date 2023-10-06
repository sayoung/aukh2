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
 * Creates a new node from Webform recrutement Submissions.
 *
 * @WebformHandler(
 *   id = "Create a node recrutement",
 *   label = @Translation("Create a node from recrutement"),
 *   category = @Translation("Entity Creation"),
 *   description = @Translation("Creates a new node from Webform recrutement Submissions."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */

class MyCandiWebformHandler extends WebformHandlerBase {
  
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
    // Set sticky.
$language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $values = $webform_submission->getData();
 
    // Before you actually save the node, make sure you get what you
    // want. So at first comment the line further down with 
    // $node->save(), run the debug code, check the output and then
    // uncomment the $node->save() and delete the debug foreach loop.
    // You can use whatever debug method you prefer, this is just a
    // simple one-time example.
   //  $values = $webform_submission->getData();

	$recurtement = \Drupal::routeMatch()->getParameter('node');
	 $term_name = $recurtement->title->value;
        if($term_name){
                  $commission_id = Helper::getTidByName($term_name, Helper::TYPE_VID);
                  if(!$commission_id){
                      $commission_id = Helper::addTerm($term_name, Helper::TYPE_VID);
                  }
              }
			// demande_maniscrite 
	    $demande_fid = $values['demande_maniscrite'];
		
    if (!empty($demande_fid)) {
      $file = \Drupal\file\Entity\File::load($demande_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_demande = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }


		

		// Document 

		// Autorisation

    // This is the node creating/saving part.
      $node = Node::create([
	// Use this format: 'node_machine_name_field' => $values('webform_machine_name_field') for below fields.
 	  'type' =>  'recrutement',
	  'uid' => 1,
	  'title' => 'Inscription de la part : '.  $values['nom_']  ,
	  'field_nom_' =>  $values['nom_'],
	  'field_cin_recru' =>  $values['carte_d_identite_nationale_cin_'],
	  'field_intitule_de_diplome' =>  $values['intitule_de_licence'],
	  'field_telephone_' =>  $values['telephone_']['phone'],
	  'field_email_' =>  $values['email_'],
	  'field_date_de_naissance_' =>  $values['date_de_naissance_'],

	 'field_offre_emploi' => ['target_id' => intval($commission_id)],
	 
	 
	 // Les Documents 
	  // demande_manuscrite 
      'field_demande_manuscrite' => [
        'target_id' => (!empty($node_demande) ? $node_demande->id() : NULL),
        'alt' => 'demande_manuscrite',
        'title' => 'demande_manuscrite'
      ],
	  // cv 
      
    // etc... 
     ]);

    $node->save();
	
  }
    // Resave the webform submission without trigger any hooks or handlers.
   // $webform_submission->resave();

    // Display debugging information about the current action.
  //  $this->displayDebug($webform_submission);
  

  }

