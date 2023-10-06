<?php
/**
 * @file
 * Contains \Drupal\dv_e_document\Controller\MainControllerWorflow.
 */

namespace Drupal\dv_e_document\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Site\Settings;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\user\Entity\User;
use Drupal\Component\Serialization\Json;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\taxonomy\Entity\Term;

class MainControllerWorflow extends ControllerBase
{
    /**
     * récupérer la liste des offres
     * @param $limit
     * @return \Drupal\Core\Entity\EntityInterface[]|static[]
     */
   
    
public static function getProvinceById($product_id) {
    $product = Product::load($product_id); 
    $termId = $product->get('field_commune_province')->target_id;
    $parent = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($termId);

    $parent = reset($parent);
    $parent_tid = $parent->id();

    $term2 = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($parent_tid);

    return $term2->getName();
}



public function getMailsByRoleTech($document_province,$document_etape)
{

    if ($document_province == "Nador") {
        $document_province = 1;
    } elseif ($document_province == "Driouch") {
        $document_province = 2;
    } elseif ($document_province == "Guercif") {
        $document_province = 3;
    }

    if ($document_etape == "document_annuler") {
        $roles_by_etap = "e_documents";
    } elseif ($document_etape == "document_comptabilite") {
        $roles_by_etap = "comptable";
    }elseif ($document_etape == "document_traitement") {
        $roles_by_etap = "e_documents";
    }elseif ($document_etape == "document_livree") {
        $roles_by_etap = "e_documents";
    }elseif ($document_etape == "document_paiement_annule") {
        $roles_by_etap = "comptable";
    } elseif ($document_etape == "annulation") {
        $roles_by_etap = "e_documents";
    }





    $query = \Drupal::entityQuery('user');
    $condition_or = $query->orConditionGroup()
    ->condition('field_province.0.value', $document_province)
    ->condition('field_province.1.value', $document_province)
    ->condition('field_province.2.value', $document_province);
    
    $condition_and = $query->andConditionGroup()
    ->condition('status', 1)
    ->condition('roles', $roles_by_etap);
    $query = $query->condition($condition_or)
    ->condition($condition_and)
    ->execute();
    
    //$group = $query->orConditionGroup()
    //  ->condition('roles', 'administrator')
    // ->condition('roles', 'technicien');
    //$tids = $query->condition($group)->execute();
    $users = User::loadMultiple($query);
    return $users;
    
    //kint();
    }



    }

   