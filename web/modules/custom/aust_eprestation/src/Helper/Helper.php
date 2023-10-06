<?php
namespace Drupal\aust_eprestation\Helper;


require_once '/home/austma/public_html/vendor/autoload.php';
use Twilio\Rest\Client;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;

use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\Node;
use Drupal\commerce_product\Entity\Product;

Class Helper{

  const SETTINGS = 'eprestation.settings';

  const API_COMMISSION  = "http://192.186.209.182/api_commission/api/commission";
  const API_COMMUNE = "http://192.186.209.182/api_commission/api/commune";
  const API_PREFECTURE = "http://192.186.209.182/api_commission/api/prefecture";

  const TYPE_VID = "type_commission";
  const COMMUNE_VID = "communes";
  const PREFECTURE_VID = "prefecture";
  const API_KEY = "mlPkDBLlKOyk5tVZM311isBXUX0dFP0QNxUWzu1jbWxW02r";



  public static function getTidByName($name, $vid) {
    $properties = [];
    if (!empty($name)) {
      $properties['name'] = $name;
    }
    if (!empty($vid)) {
      $properties['vid'] = $vid;
    }
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadByProperties($properties);
    $term = reset($terms);

    return !empty($term) ? $term->id() : 0;
  }

  public static function addTerm($name, $vid){
     $term = Term::create([
        'vid' => $vid,
        'name' => $name,
    ]);
    $term->enforceIsNew();
    $term->save();
    return $term->id();
  }

  public static function checkEPrestation($ePrestationID){
    $query = \Drupal::entityQuery('commerce_product')
    ->condition('type', 'e_prestation')
    ->condition('field_idaust_commission', $ePrestationID);
    return $query->execute();
  }
    public static function checkEPrestationFront($ePrestationCm, $ePrestationNmDs, $ePrestationType){
    $query = \Drupal::entityQuery('commerce_product')
    ->condition('type', 'e_prestation')
    ->condition('field_code_com', $ePrestationCm)
	->condition('field_num_doss', $ePrestationNmDs)
	->condition('field_commission', $ePrestationType);
    return $query->execute();
  }

  public static function setCalculEPrestation($ePrestationCm, $ePrestationNmDs, $ePrestationType){
    $pids = self::checkEPrestationFront($ePrestationCm, $ePrestationNmDs, $ePrestationType);

    foreach ($pids as $pid) {
      $product = Product::load($pid);
      $product->set('field_demande_de_calcul' , 1);
      $product->save();
    }
  }

  public static function deleteAll(){
    $pids = \Drupal::entityQuery('commerce_product')
      ->condition('type', 'e_prestation')
	  ->range(0, 500)
      ->execute();


    foreach ($pids as $pid) {
      $product = Product::load($pid);
      $product->delete();
    }
    drupal_set_message("All E-Prestation has bein deleted!\n");
  }

  public static function sendSMS($number, $code){

    $config = \Drupal::config('eprestation.settings');
$sid    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
$token  = "05c5ce6fba5353c372d542a7c23e8e32";
$twilio = new Client($sid, $token);
   try {   $result =  $twilio->messages
                ->create($number,
				//array("from" => "+12024106762", "body" => "helllo ")
                  array("from" => "+12024106762", "body" => str_replace("[code]", $code[0]['value'], $config->get('message_phone')))
                  );
                  return $result;
		} catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
    drupal_set_message($message, 'error');
    \Drupal::logger('aust_eprestation')->error($message);
    return;
     }
  }
  public static function sendSMSFinal($number, $code){
     $config = \Drupal::config('eprestation.settings');
$sid    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
$token  = "05c5ce6fba5353c372d542a7c23e8e32";
$twilio = new Client($sid, $token);
 try {    $result =  $twilio->messages
                ->create($number,
				//array("from" => "+12024106762", "body" => "helllo ")
                 array("from" => "+12024106762", "body" => str_replace("[code]", $code[0]['value'], $config->get('message_phone_final')))
                  );
                  return $result;
		} catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
    drupal_set_message($message, 'error');
    \Drupal::logger('aust_eprestation')->error($message);
    return;
     }
  }
  public static function sendMail($to, $code, $title){
      $config = \Drupal::config('eprestation.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
      $subject = $params['message'] = "<html lang='en'><head><title> ".$config->get('subject')." </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://aust.ma/themes/gavias_castron/logo.png' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'> ". str_replace("[code]", $code[0]['value'], $config->get('message_mail')['value']) ." </span>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
      $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;


   // return mail($to[0]['value'], $config->get('subject'), $params['message'], $headers);





      return $mailManager->mail($module, $key, $to[0]['value'], $langcode, $params, NULL, $send);
  }
  public static function sendMailFinal($to, $code, $title){
      $config = \Drupal::config('eprestation.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
      $params['message'] = "<html lang='en'><head><title> ".$config->get('subject')." </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://aust.ma/themes/gavias_castron/logo.png' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'> ". str_replace("[code]", $code[0]['value'], $config->get('message_mail_final')['value']) ."</span>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
      $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
 


      //return mail($to[0]['value'], $config->get('subject'), $params['message'], $headers);
      return $mailManager->mail($module, $key, $to[0]['value'], $langcode, $params, NULL, $send);
  }
  public static function sendMailFinalTechnicien($to, $code, $title){
      $config = \Drupal::config('eprestation.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
      $params['message'] = "<html lang='en'><head><title> ".$config->get('subject')." </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://aust.ma/themes/gavias_castron/logo.png' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'> ". str_replace("[code]", $code[0]['value'], $config->get('message_mail_tech')['value']) ."</span>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
      $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $to = $config->get('emailtechnicien');
	  $ccmail = $config->get('emailcci');
	    $headers = "From: ". $to[0] . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
		$subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
      return mail($to[0], $subject, $params['message'], $headers);
  }
   public static function sendMailFinalClient($to, $code, $title){
      $config = \Drupal::config('eprestation.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
      $params['message'] = "<html lang='en'><head><title> ".$config->get('subject')." </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://aust.ma/themes/gavias_castron/logo.png' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'> ". str_replace("[code]", $code[0]['value'], $config->get('message_mail_Final_client')['value']) ."</span>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
      $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;

      return $mailManager->mail($module, $key, $to[0]['value'], $langcode, $params, NULL, $send);
     // return mail($to[0]['value'], $config->get('subject'), $params['message'], $headers);
  }
  public static function sendMailFinalComptable($to, $code, $title){
      $config = \Drupal::config('eprestation.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
      $params['message'] = "<html lang='en'><head><title> ".$config->get('subject')." </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://aust.ma/themes/gavias_castron/logo.png' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'>". str_replace("[code]", $code[0]['value'], $config->get('message_mail_compta')['value']) ."</span>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
      $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $to = $config->get('emailcomptable');
	  $ccmail = $config->get('emailcci');
	    $headers = "From: ". $to[0] . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

        $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
      return mail($to, $subject, $params['message'], $headers);
  }


}
