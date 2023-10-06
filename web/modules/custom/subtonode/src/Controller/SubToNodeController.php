<?php

/**
 * This does whatever it wants to.
 */

namespace Drupal\subtonode\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Datetime;

class SubToNodeController extends ControllerBase {
  public function subtonode($webform_submission) {
    //$sid = 2;
	
    $node_details = WebformSubmission::load($webform_submission);
    $wf_changed = $node_details->getChangedTime();
    $submission_array = $node_details->getOriginalData();
	//kint($submission_array['telephoneadvanced']['phone']);
    $profession_demandeur = $submission_array['profession_demandeur'];
	$nom_ = $submission_array['nom_'];
	$prenom = $submission_array['prenom'];
	$cin = $submission_array['cin'];
	$adresse = $submission_array['adresse'];
	$telephone_ = $submission_array['telephoneadvanced']['phone'];
	$email = $submission_array['email'];
	$en_qualite = $submission_array['en_qualite'];
	$references_foncieres = $submission_array['references_foncieres'];
	$statut_foncier_ = $submission_array['statut_foncier_'];
	$prefecture = $submission_array['prefecture'];
	$commune = $submission_array['commune'];
	$nature_du_projet_envisage = $submission_array['nature_du_projet_envisage'];
	$autres_projet = $submission_array['autres_projet'];
	$cin_fid = $submission_array['carte_d_identite_nationale_scannee_pdf'];
	$Justificatif_propriete_fid = $submission_array['justificatif_de_propriete_certificat'];
	$plan_cadastral_ou_plan_topographique_fid = $submission_array['plan_cadastral_ou_plan_topographique'];
	$liste_des_coordonnees_lambert_fid = $submission_array['liste_des_coordonnees_lambert'];
	$accord_de_proprietaire_fid = $submission_array['accord_de_proprietaire'];
	//$uuid_note =  $node_details->getUuid();
	// Create file PDF CIN.
    if (!empty($cin_fid)) {
      $file = \Drupal\file\Entity\File::load($cin_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_cin = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF justificatif_de_propriete_certificat.
    if (!empty($Justificatif_propriete_fid)) {
      $file = \Drupal\file\Entity\File::load($Justificatif_propriete_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $Justificatif_propriete_pdf_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF plan_cadastral_ou_plan_topographique.
    if (!empty($plan_cadastral_ou_plan_topographique_fid)) {
      $file = \Drupal\file\Entity\File::load($plan_cadastral_ou_plan_topographique_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $plan_cadastral_pdf_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF liste_des_coordonnees_lambert.
    if (!empty($liste_des_coordonnees_lambert_fid)) {
      $file = \Drupal\file\Entity\File::load($liste_des_coordonnees_lambert_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_liste_des_coordonneespdf_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF accord_de_proprietaire.
    if (!empty($accord_de_proprietaire_fid)) {
      $file = \Drupal\file\Entity\File::load($accord_de_proprietaire_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_accord = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
	// Create node object with attached file.
    $node = Node::create([
      'type' => 'note',
	              'uid' => '1',
            'status' => 1,
            'promote' => 0,
            'created' => time(),
	  'title' => 'demande de la part : ' . $nom_ . ' ' . $prenom ,
      'field_profession_demandeur' => $profession_demandeur,
	  'field_nom' => $nom_,
      'field_prenom' => $prenom,
	  'field_cin' => $cin,
      'field_adresse' => $adresse,
	  'phone_number' => $telephone_,
      'field_email' => $email,
	  'field_en_qualite' => $en_qualite,
	  'field_references_foncieres' => $references_foncieres,
      'field_statut_foncier' => $statut_foncier_,
	  'field_prefecture' => $prefecture,
      'field_commune' => $commune,
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
	  'field_uuid' =>uniqid(),
    ]);

    $node->save();

    return drupal_set_message(t('You have successfully created a node from webform submission @sid', array('@sid' => $webform_submission)), 'success');
  }
}

