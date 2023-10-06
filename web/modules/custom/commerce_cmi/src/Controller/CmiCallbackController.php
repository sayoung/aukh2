<?php

namespace Drupal\commerce_cmi\Controller;

use Drupal\commerce_cmi\Helper\Helper;
use Drupal\commerce_order\Entity\Order;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
/**
 * Returns response for cmi Form Payment Method.
 */
class CmiCallbackController extends ControllerBase {

    /**
     * @var EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    public function __construct(EntityTypeManagerInterface $entityTypeManager) {
        $this->entityTypeManager = $entityTypeManager;
    }
    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('entity_type.manager')
        );
    }
    /**
     * cmi callback request.
     *
     * @todo Handle Callback from cmi payment gateway.
     */
    public function CmiCallback() {

        $text="";
        $postParams = array();
        foreach ($_POST as $key => $value){
            array_push($postParams, $key);
        }
        \Drupal::logger('commerce_cmi_log')->warning('order in CmiCallback 0 est : ' . $_POST['oid'] . '.');
        if(isset($_POST['oid'])){
		\Drupal::logger('commerce_cmi_log')->warning('order in CmiCallback 1 est : ' . $_POST['oid'] . '.');
            $order_id= $_POST['oid'];
           // $order_id  = "xxxxAUnotebbb";
				$position = strpos($order_id, 'AU');

				if ($position !== false) {
					$result_order_id = substr($order_id, 0, $position);
					// echo $result;  // Output will be "xxxx"
				}

			
			$order = Order::load($result_order_id);
			
			$payment_gateway = $order->get('payment_gateway')->first()->entity;
			$configuration = $payment_gateway->get('configuration');
            $storeKey = trim($configuration['SLKSecretkey']);
            $confirmation_mode = $configuration['confirmation_mode'];
            natcasesort($postParams);
            $hach = "";
            $hashval = "";
            foreach ($postParams as $param){
                $paramValue = html_entity_decode(preg_replace("/\n$/","",$_POST[$param]), ENT_QUOTES, 'UTF-8');

                $hach = $hach . "(!".$param."!:!".$_POST[$param]."!)";
                $escapedParamValue = str_replace("|", "\\|", str_replace("\\", "\\\\", $paramValue));

                $lowerParam = strtolower($param);
                if($lowerParam != "hash" && $lowerParam != "encoding" )	{
                    $hashval = $hashval . $escapedParamValue . "|";
                }
            }


            $escapedStoreKey = str_replace("|", "\\|", str_replace("\\", "\\\\", $storeKey));
            $hashval = $hashval . $escapedStoreKey;

            $calculatedHashValue = hash('sha512', $hashval);
            $actualHash = base64_encode (pack('H*',$calculatedHashValue));
        }
        $retrievedHash = $_POST["HASH"];
        if($retrievedHash == $actualHash && $_POST["ProcReturnCode"] == "00" )	{
            $order->set('state', 'completed' );
            $order->save();
			$store_idd = $order->getStoreId();
			if( $store_idd == 1) {
				$webform_submission_id =  $_POST['itemnumberN'];
				Helper::createnote($order_id,$webform_submission_id);
				
			}
			if ( $store_idd == 3)  {
				Helper::dossierpaye();
			}
			if ( $store_idd == 5)  {
				Helper::dossierpayeRokhas($order_id);
			}	
			
			if ( $store_idd == 4)  {
				
				$uuid_vente =  $_POST['uuid_vente'];
				Helper::dossierpayeEvente($uuid_vente);
			}
			if ( $store_idd == 40)  {		
					$vnomcomplet =  $_POST['BillToName'];
					 $vmail =  $_POST['email'];
					 $vtel =  $_POST['tel'];
					 
					// $pridcut_title1 =  $_POST['pridcut_title'];
					 $pridcut_title = html_entity_decode(preg_replace("/\n$/","",$_POST['pridcut_title']), ENT_QUOTES, 'UTF-8');
					// $pridcut_title = mb_convert_encoding($pridcut_title1, "UTF-8");
					 $remarque  =  $_POST['remarque'];
					 $adress_user = $_POST['BillToStreet1'];
					  $support = $_POST['support'];  
					  $echelle = $_POST['echelle']; 
					// if (($support != "") || ($support != NULL)){ $support = $_POST['support'];}else {$support = "NULL";}
					// if (($echelle != "") || ($echelle != NULL)){ $echelle = $_POST['echelle'];}else {$echelle = "NULL";}
					 $unite = $_POST['unite'];
					 $dimension = $_POST['dimension'];
					  $cin_fid = $_POST['cin_fid'];
					  // if (($cin_fid != "") || ($cin_fid != NULL) ){ $cin_fid = $_POST['cin_fid'];}else {$cin_fid = "NULL";}
					 $product_id = $_POST['product_id'];
					 $price = $order->getTotalPrice(); 
					}  
				$payment_storage = \Drupal::entityTypeManager()->getStorage('commerce_payment');
				$payment_gateway = $order->get('payment_gateway')->first()->entity;
				$payment = $payment_storage->create([
				  'state' => 'completed',
				  'amount' => $order->getTotalPrice(),
				  'payment_gateway' => $payment_gateway->id(),
				  'order_id' => $order_id,
				  'remote_id' => $_POST['oid'],
				  'remote_state' => $_POST['Response'],
				]);
					try {
						$payment->save();
					} catch (\Exception $e) {
						\Drupal::logger('commerce_cmi')->error('Failed to save payment for order: ' . $order_id . '. Error: ' . $e->getMessage());
					}


				if($confirmation_mode == "1" ){
					$text = "ACTION=POSTAUTH";
				} else {
					$text = "APPROVED";
				}
			  \Drupal::logger('commerce_cmi_log')->warning('order  completed : ' . $order->id() . '.');
        }else {
			$order->set('state', 'pending' );
			$order->save();
            $text= "APPROVED";
             \Drupal::logger('commerce_cmi_log')->warning('order cancule  : ' . $order->id() . '.');
        }

        return new Response($text);

    }

    /**
     * cmi callback request.
     *
     * @todo Handle Callback from cmi payment gateway.
     */
    public function CmiSendData() {
        die('CmiSendData');
    }


    /**
     * cmi callback request.
     *
     * @todo Handle Callback from cmi payment gateway.
     */
    public function CmiOK() {

        $msg= "<h4>Your order was successfully with Payment ID: ".$_POST["acqStan"]."</h4>"  . " <br />\r\n";
        return  array(
            '#type' => 'markup',
            '#markup' => $msg,
        ) ;
    }


    /**
     * cmi callback request.
     *
     * @todo Handle Callback from cmi payment gateway.
     */
    public function CmiFail() {
        die('CmiFail');
    }
}