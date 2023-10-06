<?php
namespace Drupal\commerce_cmi\Helper;


//require_once '/home/adminaust/public_html/aust/vendor/autoload.php';
require_once '/home/aukhtequality/public_html/vendor/autoload.php';

use Drupal\commerce_cmi\Controller;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\commerce_order\Entity\Order;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\user\Entity\User;
use Drupal\webform\Entity;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\webform_product\Plugin\WebformHandler;
use Drupal\webform\Entity\Webform;
use Drupal\webform\WebformSubmissionForm;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\webform\WebformInterface;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform_product\Plugin\WebformHandler\WebformProductWebformHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Drupal\commerce_cmi\PluginForm\CmiRedirect;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Datetime;

use Drupal\Component\Serialization\Json;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;

 
Class Helper{

    
            /**  ---------------------------------------------------------------------------------------------- */
public static function update_cmd_numbre($order_id,$some_data){
	try {
		$items = \Drupal::entityTypeManager()
            ->getStorage('node')
            ->loadByProperties(['field_webform_' => $some_data]); 

        if(empty($items)) {
            throw new \Exception("No node items found for webform submission id: $some_data");
        }
		 $nodeId = reset($items)->id();
        $nodeNote = Node::load($nodeId);
		if(!$nodeNote) {
            throw new \Exception("Unable to load node with id: $nodeId");
        }
		$nodeNote->field_n_command = $order_id;
		//$prodId = $nodeNote->get('field_product_id')->value; 
		$nodeNote->save();
		
		
		} catch (\Exception $e) {
        \Drupal::logger('noteEmail')->error("Error in createNote method: " . $e->getMessage());
    }
}
public static function createNote($orderId, $webformSubmissionId){
    try {
        $items = \Drupal::entityTypeManager()
            ->getStorage('node')
            ->loadByProperties(['field_webform_' => $webformSubmissionId]); 

        if(empty($items)) {
            throw new \Exception("No node items found for webform submission id: $webformSubmissionId");
        }

        $nodeId = reset($items)->id();
        $nodeNote = Node::load($nodeId);

        if(!$nodeNote) {
            throw new \Exception("Unable to load node with id: $nodeId");
        }

        $newState = 'checking';
        $prodId = $nodeNote->get('field_product_id')->value; 

        $nodeNote->field_n_command = $orderId;
        $nodeNote->set('field_cheking', 1);
        $nodeNote->set('field_paye', 1);
        // $node_note->field_paye = 1;
        $nodeNote->set('moderation_state', $newState);
        $nodeNote->save();

        $mssg = "The payment for order $orderId has been activated and node id is: $nodeId";
        \Drupal::logger('noteEmail')->notice($mssg);

        $product = Product::load($prodId);
        if(!$product) {
            throw new \Exception("Unable to load product with id: $prodId");
        }

        $product->set('field_is_payed' , 1);
        $product->save();

        $mssg1 = "Product id is: $prodId";
        \Drupal::logger('noteEmail')->notice($mssg1);
        
    } catch (\Exception $e) {
        \Drupal::logger('noteEmail')->error("Error in createNote method: " . $e->getMessage());
    }
}


/** /-------------------------------------------------------------------------- */ 
public static function dossierpaye(){
	$aassz =  \Drupal::request()->request->get('itemnumberN');
				
			//$ProductV->set('field_is_payed' , 1);
			//$product->save();
		
			//$id2 = $ProductV->getProductId();
			//$id2 = 49540;
			//kint($id2);die;
			$product = Product::load($aassz);
			$product->set('field_is_payed' , 1);
			$product->save();
			
}

public static function dossierpayeRokhas($order_id){
	$aassz =  \Drupal::request()->request->get('itemnumberN');
	$time_daba = date("Y-m-d H:i:s");			
			//$ProductV->set('field_is_payed' , 1);
			
			//$product->save();
		
			//$id2 = $ProductV->getProductId();
			//$id2 = 49540;
			//kint($id2);die;  
			$product = Product::load($aassz);
			$product->set('field_is_payed' , 1);
			$product->set('field_date_de_paiement', date('Y-m-d\TH:i:s', time()));
			$product->set('field_date_de_paiement_2', date('Y-m-d\TH:i:s', time()));
			//$product->set('field_date_de_paiement_2', $now->format('Y-m-d\TH:i:s'));
            $product->set('field_n_command' ,  $order_id);
			 
			$product->save();
			
}
public static function dossierpayeEvente($uuid_vente) {
	try {
		$items = \Drupal::entityTypeManager()
            ->getStorage('node')
            ->loadByProperties(['field_uuid' => $uuid_vente]); 

        if(empty($items)) {
            throw new \Exception("No document items found with N de suivi: $uuid_vente");
        }
		 $nodeId = reset($items)->id();
        $nodeNote = Node::load($nodeId);
		if(!$nodeNote) {
            throw new \Exception("E-vente: Unable to load node with id: $nodeId");
        }
		$nodeNote->set('field_cheking', 1);
		//$prodId = $nodeNote->get('field_product_id')->value; 
		$nodeNote->save();
		
		
		} catch (\Exception $e) {
        \Drupal::logger('E-vente')->error("Error in valider le piement du Document method: " . $e->getMessage());
    }
			
}
public static function dossierpayeRokhas_add_cmd($order_id,$articleprestationid){
	$product = Product::load($articleprestationid);
	$product->set('field_n_command' ,  $order_id);
	$product->save();
}
/** -----------------------------------------------------------------------------*/ 


public static function  ventedocum_create_historique($vnomcomplet,$vmail,$vtel,$pridcut_title,$position,$remarque,$adress_user,$format,$echelle,$uniteName,$commune,$price,$dimensionName,$product_id, $order_id,$uuid_vente){
    
      if (!empty($cin_fid)) {
      $file = \Drupal\file\Entity\File::load($cin_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $cin_fid_file = file_save_data($data, 'public://' . $file->getFilename(), \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);
    }
    $price = str_replace(" MAD","",$price);

    //$file = file_save_data($data, 'public://druplicon.png', FILE_EXISTS_REPLACE);
    $pridcut_title1 = mb_convert_encoding($pridcut_title, "UTF-8");
    $uuid_note =  time();
    $node = Node::create([
 	  'type' =>  'documents_urbanisme',
	  'uid' => 1,
	  'title' =>  $pridcut_title ,
	  'field_comune_' => $product_id,
      'field_n_command' => $order_id,
	  'field_document_name' =>  $pridcut_title1 ,
	  'field_echelle' => $echelle,
	  'field_unite' => $uniteName,
	  'field_email' => $vmail,
	  'field_emplacement_x_y' => $position,
	  'field_emplacement_feuille_' => $remarque,
	  'field_nom' => $vnomcomplet,
      'field_prix_2' => $price,
	  'field_quantite' => "1",
	  'field_dimension' => $dimensionName,
	  'field_support' => $format,
	  'field_telephone_' => $vtel,
	  'field_commune_doc' => $commune,
      'field_adresse' => $adress_user,
      'field_uuid' => $uuid_vente,
	  'field_carte_d_identite_nationale' => [
        'target_id' => (!empty($cin_fid_file) ? $cin_fid_file->id() : NULL),
        'alt' => 'Carte d identité nationale scannée (PDF)(*)',
        'title' => 'Carte d identité nationale scannée'
      ],
	 // 'field_cheking' => 1,
	  //'field_uuid' => $uuid_note,
	  // 'field_webform_' => $webform_submission->id(),
                		]);
					  $node->save();
				   
  
}
}