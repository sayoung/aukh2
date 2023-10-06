<?php

namespace Drupal\md_new_prestation\Plugin\WebformHandler;

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
use Drupal\md_new_prestation\Helper\Helper;
use Drupal\Component\Serialization\Json;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;

/**
 * Creates a new product from Webform prestation Submissions.
 *
 * @WebformHandler(
 *   id = "Create product prestation",
 *   label = @Translation("Create product from prestation"),
 *   category = @Translation("Entity Creation"),
 *   description = @Translation("Creates a new product from Webform prestation Submissions."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */

class EprestationNew extends WebformHandlerBase {
  
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
 
    // Before you actually save the node, make sure you get what you
    // want. So at first comment the line further down with 
    // $node->save(), run the debug code, check the output and then
    // uncomment the $node->save() and delete the debug foreach loop.
    // You can use whatever debug method you prefer, this is just a
    // simple one-time example.
   //  $values = $webform_submission->getData();

	$recurtement = \Drupal::routeMatch()->getRawParameter('node');

	$cin = $submission_array['cin'];
	$mail = $submission_array['e_mail_'];
	$telephone = $submission_array['telephone_'];
	//$metrage_du_projet = $submission_array['metrage_du_projet'];
	$field_architecte_ou_igt = $submission_array['architecte_ou_igt'];
	if (!empty($submission_array['commune'])) {$commune = $submission_array['commune'];}
	
	//$field_metrage_du_projet = $submission_array['metrage_du_projet'];
    if (!empty($submission_array['metrage_du_projet'])) {$metrage_du_projet = $submission_array['metrage_du_projet'];} 
	else{
		$metrage_du_projet = $submission_array['superficie_loti'] + $submission_array['superficie_plancher'];
		}
	
	/* if (!empty($submission_array['superficie_loti'])) 
	{$metrage_du_projet = $submission_array['superficie_loti'] + $submission_array['superficie_plancher'] ;}
	
	if (!empty($submission_array['superficie_plancher'])) 
	{$metrage_du_projet = $submission_array['superficie_plancher'] + $submission_array['superficie_plancher'] ;}
	*/
	if (!empty($submission_array['nature_du_projet_2'])) {$field_nature_du_projet = $submission_array['nature_du_projet_2'];}
	if (!empty($submission_array['nature_du_projet'])) {$field_nature_du_projet = $submission_array['nature_du_projet'];}
	$field_ndeg_de_dossier = $submission_array['ndeg_de_dossier'];
	$field_situation_du_projet = $submission_array['situation_du_projet'];
	$field_nom_du_petitionnaire = $submission_array['nom_du_petitionnaire'];
	//$montant_a_verser_en_lettres_dh_ = $submission_array['montant_a_verser_en_lettres_dh_'];
	$price = $submission_array['price'];
	$sidd = $webform_submission->id();
	$zone = $submission_array['type_de_zone'];
	$type_projet = $submission_array['type_de_projet'];
	$superficie_plancher = $submission_array['superficie_plancher'];
	$superficie_loti = $submission_array['superficie_loti'];
	$detail = $submission_array['detail'];
	$titre_foncier = $submission_array['_titre_foncier'];
	$time_st = time();
	
	
	$product = Product::create([
              'uid' => 1,
              'type' => 'instruction',
			  'stores' => '5',
              'title' => 'Dossier N : ' . $field_ndeg_de_dossier  ,
			  'status' => 1,
            ]);
			$product->set('field_id_pdf', $sidd);
			$product->set('field_cin', $cin);
			$product->set('field_mail', $mail);
			$product->set('field_telephone', $telephone);
			$product->set('field_metrage_du_projet', $metrage_du_projet);
			$product->set('field_architecte_ou_igt', $field_architecte_ou_igt);
			$product->set('field_commune', $commune);
			$product->set('field_nom_du_petitionnaire', $field_nom_du_petitionnaire);
			//$product->set('field_prix_en_lettre', $montant_a_verser_en_lettres_dh_);
			$product->set('field_nature_du_projet', $field_nature_du_projet);
			$product->set('field_situation_du_projet', $field_situation_du_projet);
			$product->set('field_ndeg_de_dossier', trim($field_ndeg_de_dossier));
			$product->set('field_zone', $zone);
			$product->set('field_type_de_projet', $type_projet);
			$product->set('field_superficie_plancher', $superficie_plancher);
			$product->set('field_superficie_loti', $superficie_loti);
			$product->set('field_detail', $detail);
			$product->set('field_titre_foncier', $titre_foncier);
			
			
			
			            $variation = ProductVariation::create([
              'type' => 'instruction',
              'sku' => $time_st,
			  'price' => new \Drupal\commerce_price\Price($price, 'MAD'),
			  'title' => $field_nom_du_petitionnaire  ,
              'status' => 1
            ]);
            
            $variation->save();
            $product->addVariation($variation);
            $product->save();

	
  }
    // Resave the webform submission without trigger any hooks or handlers.
   // $webform_submission->resave();

    // Display debugging information about the current action.
  //  $this->displayDebug($webform_submission);
  

  }