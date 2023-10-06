<?php

namespace Drupal\commerce_cmi\PluginForm\CmiRedirect;

use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\commerce_order\Entity\Order;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\user\Entity\User;
use Drupal\commerce_cmi\Helper\Helper;
use Drupal\webform\Entity;
use Drupal\webform\Entity\WebformSubmission;

use Drupal\webform_product\Plugin\WebformHandler;
use Drupal\commerce_product\Entity\ProductInterface;

use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_price\Price;



/**
 * Implements PaymentCmiForm class.
 *
 * - this class used for build to payment form.
 */
class PaymentCmiForm extends BasePaymentOffsiteForm {

    // const CMI_API_URL = 'https://testpayment.cmi.co.ma/fim/est3Dgate';

    public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
        $form = parent::buildConfigurationForm($form, $form_state);
        $payment = $this->entity;
        $redirect_method = 'post';

        /** @var \Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayInterface $payment_gateway_plugin */
        $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();
        $orgClientId  = trim($payment_gateway_plugin->getConfiguration()['merchant_id']);
        $redirect_url  = trim($payment_gateway_plugin->getConfiguration()['actionslk']);
        $SLKSecretkey  = trim($payment_gateway_plugin->getConfiguration()['SLKSecretkey']);

        $current_user = \Drupal::currentUser();
        $user = \Drupal\user\Entity\User::load($current_user->id());
        $email = $user->get('mail')->value;



        $data = [];


        $order        = $payment->getOrder();
        $total_price  = $order->getTotalPrice();
		$sx = $order->getOrderNumber();

        $symbolCur  = $total_price->getCurrencyCode();
		$lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $amount = $payment->getAmount()->getNumber();
        // $orgOkUrl =  Url::fromRoute('commerce_cmi.ok', [], ['absolute' => TRUE])->toString();
        // $orgFailUrl = Url::fromRoute('commerce_cmi.fail', [], ['absolute' => TRUE])->toString();
        // $shopurl = Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString();
        $orgOkUrl = $form['#return_url'];
        $orgFailUrl = $form['#cancel_url'];
        $shopurl = $form['#cancel_url'];
        $orgTransactionType = "PreAuth";
        $orgRnd =  microtime();
        $orgCallbackUrl = Url::fromRoute('commerce_cmi.callback', [], ['absolute' => TRUE])->toString();
		//$orgCallbackUrl = "https://www.aust.ma/fr/commerce-cmi/callback";
        $order_id = \Drupal::routeMatch()->getParameter('commerce_order')->id();
         \Drupal::logger('commerce_cmi_log')->warning('before : order est : ' . $order_id . '.');
        $entityManager = \Drupal::entityTypeManager();
		$store_idd = $order->getStoreId();
		$order_item = \Drupal\commerce_order\Entity\OrderItem::load($order->order_items->target_id);

					$id2 = $order_item->getPurchasedEntityId(); 
				$ProductV = ProductVariation::load($id2);

		
			$id22 = $ProductV->getProductId();					
				

		$identity = $order_item->getPurchasedEntityId();
		$some_data = null;
		if( $store_idd == 1) {
			$session_manager = \Drupal::service('session_manager');
			if ($session_manager->isStarted()) {
			  $session_id = $session_manager->getId();
			  $key = 'commerce_webform_order_' . $session_id;
					// Get the tempstore for that unique key.
					$tempstore = \Drupal::service('tempstore.shared')->get($key);
					// Retrieve the value from the tempstore.
					$some_data = $tempstore->get('siids');
					// $tempstore->delete($key);
					// $tempstore->deleteAll();
			}

			$articleprestationid = $id22;
			
		}

		if( $store_idd == 3) {
			$articleprestationid = $id22;
		}
		if( $store_idd == 5) {
			$articleprestationid = $id22;
		}
			if( $store_idd == 4) {
			

				$position = null;
				$remar = null;
				$cin_fid = null;
				$communeName = null;
					$position = $order_item->get('field_emplacement_x_y_')->getString();
                    $remar = $order_item->get('field_numero_des_feuilles_quadri')->getString();
					
					$communeName = $order_item->get('field_commun')->getString();
				//	$cin_fid =	$order_item->get('field_cin')[0]->getValue()['target_id'];
					
					

					
			
					
					
					$echelle_id = null;
					$format_id = null;
					$dimension_id = null;
					$unite_id  = null;
					
					
					
					$echelle = null;
					$format = null;
					$dimension = null;
                    $unite = null;

					if (!empty($ProductV->attribute_echelle[0])) {
                        $echelle_id =	$ProductV->attribute_echelle[0]->getValue()['target_id'];
                            }

                    if (!empty($ProductV->attribute_dimension[0])) {
						$format_id = $ProductV->attribute_dimension[0]->getValue()['target_id'];
					}

                    if (!empty($ProductV->attribute_dimension_4[0])) {
                    $dimension_id =	$ProductV->attribute_dimension_4[0]->getValue()['target_id'];
                    }
                    if (!empty($ProductV->attribute_unite[0])) {
                        $unite_id =	$ProductV->attribute_unite[0]->getValue()['target_id'];
                        }                       
				
				
				
				

			
			
			if ($echelle_id !== null) {
				$echelle = \Drupal\commerce_product\Entity\ProductAttributeValue::load($echelle_id);
			}
			if ($format_id !== null) {
				$format = \Drupal\commerce_product\Entity\ProductAttributeValue::load($format_id);
			}
			if ($dimension_id !== null) {
				$dimension = \Drupal\commerce_product\Entity\ProductAttributeValue::load($dimension_id);
			}
			if ($unite_id !== null) {
				$unite = \Drupal\commerce_product\Entity\ProductAttributeValue::load($unite_id);
			}
			// $dimension = \Drupal\commerce_product\Entity\ProductAttributeValue::load($dimension_id);
			// $commune = \Drupal\commerce_product\Entity\ProductAttributeValue::load($commune_id);
	
	
			
		}
		
		$pridcut_title1 = $ProductV->getTitle();			
        $pridcut_title = mb_convert_encoding($pridcut_title1, "UTF-8");
        
		$identity = $order_item->getPurchasedEntityId();
        $order = $entityManager->getStorage('commerce_order')->load($order_id);


        $billing = $order->billing_profile->entity->address->getValue();

				
		$tell = $order->billing_profile->entity->field_tel->value;

        $BillToName = trim(html_entity_decode($billing[0]["family_name"] ?? '' .' '. $billing[0]["given_name"] ?? ''));
        $BillToStreet1 = trim(html_entity_decode($billing[0]["address_line1"] ?? '' .' '. $billing[0]["address_line2"] ?? ''));
        $BillToCity = trim(html_entity_decode($billing[0]["locality"] ?? ''));
        $BillToCountry = trim(html_entity_decode($billing[0]["country_code"] ?? ''));
        $BillToPostalCode = trim(html_entity_decode($billing[0]["postal_code"] ?? ''));
        $BillToStateProv = trim(html_entity_decode($billing[0]["administrative_area"] ?? ''));
        $BillToCompany = trim(html_entity_decode($billing[0]["organization"] ?? ''));

		switch ($lang) {
			case "en":
			case "fr":
			case "ar":
				 $data['lang'] = $lang;
			break;
			default:
				 $data['lang'] = "en";
		}
		$uuid_vente =  time();

        $data['clientid'] = $orgClientId;
        $data['amount'] = $amount;
        $data['okUrl'] = $orgOkUrl;
        $data['failUrl'] = $orgFailUrl;
        $data['TranType'] = $orgTransactionType;


        $data['callbackUrl'] = $orgCallbackUrl;
        $data['shopurl'] = $shopurl;
        $data['currency'] ="504";
        $data['rnd'] = $orgRnd;
        $data['storetype'] ="3D_PAY_HOSTING";
        $data['hashAlgorithm'] ="ver3";
        $data['refreshtime'] ="5";
        $data['BillToName'] = $BillToName;
        $data['BillToCompany'] = $BillToCompany;
        $data['BillToStreet1'] = $BillToStreet1;
        $data['BillToCity'] = $BillToCity;
        $data['BillToStateProv'] = $BillToStateProv;
        $data['BillToPostalCode'] = $BillToPostalCode;
        $data['BillToCountry'] = $BillToCountry;
        // $data['email'] = $email;
        $data['email'] = $order->getEmail();
		$data['tel'] = $tell;
		$data['pridcut_title'] =  $pridcut_title;
		$data['product_id'] = $id22;
		if( $store_idd == 1) {
			$ord_id = $order_id ."AUnote". $id22;
		$data['itemnumberN'] = $some_data;
		$data['prod_id'] = $articleprestationid;
		}elseif ( $store_idd == 3)  {
			$data['itemnumberN'] = $articleprestationid;
			$ord_id = $order_id ."AUrokhas". $id22;
		}elseif ( $store_idd == 5)  {
			$data['itemnumberN'] = $articleprestationid;
			$ord_id = $order_id ."AUrokhas". $id22;
		}
		elseif ( $store_idd == 4) {
			$ord_id = $order_id ."AUvente". $id22;
    		$data['position'] = $position;
    		$data['remarque'] = $remar;
    		$echelleName = ($echelle) ? $echelle->getName() : '';
			$formatName = ($format) ? $format->getName() : '';
			$dimensionName = ($dimension) ? $dimension->getName() : '';
			$uniteName = ($unite) ? $unite->getName() : '';
			$data['uuid_vente'] = $uuid_vente;
    	//	if (null !== $cin_fid ) { $data['cin_fid'] = $cin_fid;} else {$data['cin_fid'] = 1;}
        }
		else {
			
		}
		
		 

        $data['encoding'] ="UTF-8";
        $data['oid'] = $ord_id;
        // $data['symbolCur'] = $symbolCur;
        //$data['amountCur'] ="5";
        $vmail = $order->getEmail();
		$price = $order->getTotalPrice();
		
		if ( $store_idd == 4) {
			
		Helper::ventedocum_create_historique($BillToName,$vmail,$tell,$pridcut_title,$position,$remar,$BillToStreet1,$formatName,$echelleName,$uniteName,$communeName,$price,$dimensionName,$id22, $ord_id,$uuid_vente);	
        }
		if ( $store_idd == 5)  {
			// Helper::dossierpayeRokhas_add_cmd($order_id,$articleprestationid);
		}
		$storeKey = $SLKSecretkey;


        $postParams = array();
        foreach ($data as $key => $value){
            array_push($postParams, $key);
        }

        natcasesort($postParams);

        $hashval = "";
        foreach ($postParams as $param) {
    if (isset($data[$param])) {
        $paramValue = trim($data[$param]);
        $escapedParamValue = str_replace("|", "\\|", str_replace("\\", "\\\\", $paramValue));

        $lowerParam = strtolower($param);
        if ($lowerParam != "hash" && $lowerParam != "encoding") {
            $hashval = $hashval . $escapedParamValue . "|";
        }
      //  \Drupal::logger('commerce_cmi')->info('Param Value: ' . $paramValue);
    } else {
        \Drupal::logger('commerce_cmi_log')->warning('The parameter ' . $param . ' is not set.');
    }
}


        $escapedStoreKey = str_replace("|", "\\|", str_replace("\\", "\\\\", $storeKey));
        $hashval = $hashval . $escapedStoreKey;

        $calculatedHashValue = hash('sha512', $hashval);
        $hash = base64_encode (pack('H*',$calculatedHashValue));
        $data['HASH'] = $hash;
//\Drupal::logger('commerce_cmi')->warning('hash est : ' . $hash . '.');

        return $this->buildRedirectForm($form, $form_state, $redirect_url, $data, $redirect_method);

    }



}