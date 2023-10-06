<?php

namespace Drupal\notemail\Plugin\WebformHandler;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Render\Markup;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\webform\WebformInterface;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\Entity\WebformSubmission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\taxonomy\Entity\Term;
use Drupal\Component\Serialization\Json;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\webform\Element\WebformHtmlEditor;
use Drupal\webform\WebformSubmissionConditionsValidatorInterface;
use Drupal\webform\WebformTokenManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Entity\EntityInterface;




/**
 * Creates a new product from Webform prestation Submissions.
 *
 * @WebformHandler(
 *   id = "Create note de renseignements d'urbanisme",
 *   label = @Translation("note de renseignements d'urbanisme"),
 *   category = @Translation("Entity Creation"),
 *   description = @Translation("Creates a note de renseignements  from Webform E-note Submissions."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */
 
 

class EnoteHandler extends WebformHandlerBase {
  
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
    // Message.
    $form['message'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Message settings'),
    ];
    $form['message']['message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Message to be displayed when form is completed'),
      '#default_value' => $this->configuration['message'],
      '#required' => TRUE,
    ];

    // Development.
    $form['development'] = [
      '#type' => 'details',
      '#title' => $this->t('Development settings'),
    ];
    $form['development']['debug'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable debugging'),
      '#description' => $this->t('If checked, every handler method invoked will be displayed onscreen to all users.'),
      '#return_value' => TRUE,
      '#default_value' => $this->configuration['debug'],
    ];

    return $this->setSettingsParents($form);
  }
  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['message'] = $form_state->getValue('message');
    $this->configuration['debug'] = (bool) $form_state->getValue('debug');
  }
     /**
   * {@inheritdoc}
   */
   

   /**
   * Prepare data from the handler and the webform submission.
   *
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   *   The webform submission entity.
   *
   * @return array
   *   The prepared data from the handler and the submission.
   *
   * @throws \Exception
   */
  
//  protected function executeAction(WebformSubmissionInterface $webform_submission) {
 public function submitForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
	 
	  
 }
    /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    $current_page = $webform_submission->getCurrentPage();
    if (!empty($current_page) && $current_page == 'webform_confirmation') {   
     $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $submission_array = $webform_submission->getData();
 
$source = $webform_submission->getSourceEntity();
//	$nid = $source->id();
	$submission_id = $submission_array;

    $profession_demandeur = $submission_array['profession_demandeur'];
	$nom_ = $submission_array['nom_'];
	$prenom = $submission_array['prenom'];
	$cin = $submission_array['cin'];
	$adresse = $submission_array['adresse'];
	$telephone_ = $submission_array['telephone_mobile'];
	$email = $submission_array['email'];
	$en_qualite = $submission_array['en_qualite'];
	$references_foncieres = $submission_array['references_foncieres'];
	$statut_foncier_ = $submission_array['statut_foncier_'];
	$prefecture = $submission_array['prefecture'];
	$commune = $submission_array['commune'];
	$nature_du_projet_envisage = $submission_array['nature_du_projet_envisage'];
	$autres_projet = $submission_array['nature_du_projet_envisage'];
	$cin_fid = $submission_array['carte_d_identite_nationale_scannee_pdf'];
	$Justificatif_propriete_fid = $submission_array['justificatif_de_propriete_certificat'];
	$plan_cadastral_ou_plan_topographique_fid = $submission_array['plan_cadastral_ou_plan_topographique'];
	$liste_des_coordonnees_lambert_fid = $submission_array['liste_des_coordonnees_lambert'];
	$accord_de_proprietaire_fid = $submission_array['accord_de_proprietaire'];
	$uuid_note =  time();
	
	    $current_user = \Drupal::currentUser();
   // $user = \Drupal\user\Entity\User::load($current_user->id());
   if ($current_user->id()) {
       $user_id  = $current_user->id();
   } else {
       $user_id = 1;
   }
   

// Create file PDF CIN.
    if (!empty($cin_fid)) {
      $file = \Drupal\file\Entity\File::load($cin_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_cin = file_save_data($data, 'public://' . $file->getFilename(),  \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);
      // dump($file);
 //die;
    }
// Create file PDF justificatif_de_propriete_certificat.
    if (!empty($Justificatif_propriete_fid)) {
      $file = \Drupal\file\Entity\File::load($Justificatif_propriete_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $Justificatif_propriete_pdf_file = file_save_data($data, 'public://' . $file->getFilename(),  \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);
    }
// Create file PDF plan_cadastral_ou_plan_topographique.
    if (!empty($plan_cadastral_ou_plan_topographique_fid)) {
      $file = \Drupal\file\Entity\File::load($plan_cadastral_ou_plan_topographique_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $plan_cadastral_pdf_file = file_save_data($data, 'public://' . $file->getFilename(),  \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);
    }
// Create file PDF liste_des_coordonnees_lambert.
    if (!empty($liste_des_coordonnees_lambert_fid)) {
      $file = \Drupal\file\Entity\File::load($liste_des_coordonnees_lambert_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_liste_des_coordonneespdf_file = file_save_data($data, 'public://' . $file->getFilename(),  \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);
    }
// Create file PDF accord_de_proprietaire.
    if (!empty($accord_de_proprietaire_fid)) {
      $file = \Drupal\file\Entity\File::load($accord_de_proprietaire_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_accord = file_save_data($data, 'public://' . $file->getFilename(),  \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);
    }
//$state = $webform_submission->getWebform()->getSetting('results_disabled') ? WebformSubmissionInterface::STATE_COMPLETED : $webform_submission->getState(); 

	   	// creation de produit a partir la demande 	
      $price = 300;		
		
       	$product = Product::create([
              'uid' => $user_id,
              'type' => 'e_note',
			  'stores' => '1',
              'title' => 'E-note reference  : ' . $references_foncieres  ,
			  'status' => 1,
            ]);
			$product->set('field_cin', $cin);
			$product->set('field_mail', $email);
			$product->set('field_titre_foncier', $references_foncieres);
			$product->set('field_code_de_suivi', $uuid_note);
			
			
			
			            $variation = ProductVariation::create([
              'type' => 'instruction',
              'sku' => $uuid_note,
			  'price' => new \Drupal\commerce_price\Price($price, 'MAD'),
			  'title' => $references_foncieres  ,
              'status' => 1
            ]);
            
            $variation->save();
            $product->addVariation($variation);
            $product->save();	
			$product_id = $product->product_id->value;
			
    // creation de la note  a partir la demande 	
	 $node = Node::create([
 	  'type' =>  'note',
	  'uid' => $user_id,
	  'title' => 'Demande de la part : ' . $nom_ . ' ' . $prenom . '-' . $references_foncieres ,
	  'field_langue' => $language,
	  'field_email' => $email,
      'field_profession_demandeur' => $profession_demandeur,
	  'field_nom' => $nom_,
      'field_prenom' => $prenom,
	  'field_cin' => $cin,
      'field_adresse' => $adresse,
	  'field_phone_number' => $telephone_,
	  'field_en_qualite' => $en_qualite,
	  'field_references_foncieres' => $references_foncieres,
      'field_statut_foncier' => $statut_foncier_,
	  'field_prefecture' => $prefecture,
      'field_commune' => $commune,
//'field_n_command' => $order_id , 
	  'field_nature_du_projet_envisage' => $nature_du_projet_envisage,
	  'field_autres_projet' => $autres_projet,
	  'field_carte_d_identite_nationale' => [
        'target_id' => (!empty($node_pdf_cin) ? $node_pdf_cin->id() : NULL),
        'alt' => 'Carte d identité nationale scannée (PDF)(*)',
        'title' => 'Carte d identité nationale scannée'
      ],
	  'field_justificatif_de_propriete' => [
        'target_id' => (!empty($Justificatif_propriete_pdf_file) ? $Justificatif_propriete_pdf_file->id() : NULL),
        'alt' => 'Certificat de propriété, Acte adulaire(*)',
        'title' => 'Certificat de propriété, Acte adulaire'
      ],
	  'field_plan_cadastral_ou_plan_top' => [
        'target_id' => (!empty($plan_cadastral_pdf_file) ? $plan_cadastral_pdf_file->id() : NULL),
        'alt' => 'Plan cadastral ou plan topographique (PDF)(*)',
        'title' => 'Plan cadastral ou plan topographique'
      ],
	  'field_liste_des_coordonnees_lamb' => [
        'target_id' => (!empty($node_pdf_liste_des_coordonneespdf_file) ? $node_pdf_liste_des_coordonneespdf_file->id() : NULL),
        'alt' => 'Liste des coordonnées Lambert fournie par les services de l’ANCFCCPDF)(*)',
        'title' => 'Liste des coordonnées Lambert fournie par les services de l’ANCFCC'
      ],
	  'field_accord_de_proprietaire' => [
        'target_id' => (!empty($node_pdf_accord) ? $node_pdf_accord->id() : NULL),
        'alt' => 'Accord de propriétaire(PDF)(*)',
        'title' => 'Accord de propriétaire'
      ],

	  'field_cheking' => 0,
	  'field_uuid' => $uuid_note,
	  'field_product_id'=>$product_id,
	  'field_webform_' => $webform_submission->uuid(),
                		]);
					  $node->save();
					  
					//get node id  - - $node->id() 
					$nid_last_create = $uuid_note ;
				   
	
				   
       		 		   //$form_state->setRedirect('/fr/espace-de-paiement-enote?field_code_de_suivi_value='. $nid_last_create );
				/******  --------- Redirection -------------------------------***/
				
				//   (new RedirectResponse('/fr/espace-de-paiement-enote?field_code_de_suivi_value=' . ($nid_last_create)))->send();
	   			   
  }
  }
  


  }