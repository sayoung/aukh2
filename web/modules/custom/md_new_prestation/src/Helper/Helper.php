<?php
namespace Drupal\md_new_prestation\Helper;


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

  const SETTINGS = 'Helper.settings';

 
  public static function sendSMSV($number){

    $config = \Drupal::config('Helper.settings');
$sid    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
$token  = "05c5ce6fba5353c372d542a7c23e8e32";
$twilio = new Client($sid, $token);
      try {
    $result =  $twilio->messages
                ->create($number,
				//array("from" => "+12024106762", "body" => "helllo ")
                  array("from" => "+12024106762", "body" => $config->get('isms_phone_ver'))
                  );
                  return $result;
				  } catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
   // drupal_set_message($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
     }
  }

  public static function sendSMS($number,$code){

    $config = \Drupal::config('Helper.settings');
$sid    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
$token  = "05c5ce6fba5353c372d542a7c23e8e32";
$twilio = new Client($sid, $token);
   try {
    $result =  $twilio->messages
                ->create($number,
				//array("from" => "+12024106762", "body" => "helllo ")
                  array("from" => "+12024106762", "body" => str_replace("[code]", $code, $config->get('isms_phone')))
                  );
                  return $result;
			} catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
   // drupal_set_message($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
     }
  }

  public static function sendSMSF($number,$code){

    $config = \Drupal::config('Helper.settings');
$sid    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
$token  = "05c5ce6fba5353c372d542a7c23e8e32";
$twilio = new Client($sid, $token);
    try { $result =  $twilio->messages
                ->create($number,
				//array("from" => "+12024106762", "body" => "helllo ")
                  array("from" => "+12024106762", "body" => str_replace("[code]", $code, $config->get('isms_phone_final')))
                  );
                  return $result;
				  } catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
   // drupal_set_message($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
     }
  }
   public static function sendSMSA($number,$code,$commnetaire){

    $config = \Drupal::config('Helper.settings');
	  $search  = array('[code]', '[commenatire]');
  $replace = array($code, $commnetaire);
$sid    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
$token  = "05c5ce6fba5353c372d542a7c23e8e32";
$twilio = new Client($sid, $token);
    try { $result =  $twilio->messages
                ->create($number,
				//array("from" => "+12024106762", "body" => "helllo ")
                  array("from" => "+12024106762", "body" => str_replace($search, $replace, $config->get('isms_phone_annulation')))
                  );
                  return $result;
				  } catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
   // drupal_set_message($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
     }
  }
public static function sendMailVerification($to, $title){
     try {
	  $config = \Drupal::config('Helper.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'md_new_prestation';
  $key = 'node_insert';
  $to = $to; 
  $params['message'] = "<html lang='en'><head><title> AUST </title></head><body><div>
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
                                              <span style='font-size:12pt;'> " . $config->get('imessage_mail_veri')['value'] . " </span>
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
   $params['node_title'] = "instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;
  
  // $ccmail = $config->get('emailcci');
  $ccmail_m =  'dardev.maroc@gmail.com'; 
    $from_send = 'noreply@aust.ma';
    $headers = "From: ". $from_send . "\r\n" .
      "Bcc: " . $ccmail_m . "\r\n" .
      "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
      "MIME-Version: 1.0" . "\r\n" .
      "Content-type: text/html; charset=UTF-8" . "\r\n";
    $subject = "=?UTF-8?B?" . base64_encode('instruction') . "?=";

 // $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
   $result = mail($to, $subject, $messageBody, $headers);
    if (!$result) {
      $message = t('instruction Verification : There was a problem sending your email notification to @email.', array('@email' => $to));
      \Drupal::messenger()->addMessage($message, 'error');
      \Drupal::logger('md_new_prestation')->error($message);
      return;
    } else {
      \Drupal::messenger()->addMessage(t('instruction Verification : Your message has been sent to  @email.', array('@email' => $to)));
    }
  } catch (\Exception $e) {
    \Drupal::logger('md_new_prestation')->error("Error in sendMail method: " . $e->getMessage());
  }
 
   $message = t('An email notification has been sent to @email :', array('@email' => $to));
 // drupal_set_message($message);
  \Drupal::logger('md_new_prestation')->notice($message);

  }
public static function sendMailValidation($to, $code, $title){
      try {
	  $config = \Drupal::config('Helper.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'md_new_prestation';
  $key = 'node_insert';
  $to = $to; 
  $messageBody = "<html lang='en'><head><title> AUST </title></head><body><div>
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
                                                            <span style='font-size:12pt;'> " . str_replace("[code]", $code, $config->get('imessage_mail_first')['value']) . " </span>
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
 $params['node_title'] = "instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;
  
  $ccmail = $config->get('emailcci');
  $ccmail_m = "dardev.maroc@gmail.com";
    $from_send = 'noreply@aust.ma';
    $headers = "From: ". $from_send . "\r\n" .
      "Bcc: " . $ccmail_m . "\r\n" .

      "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
      "MIME-Version: 1.0" . "\r\n" .
      "Content-type: text/html; charset=UTF-8" . "\r\n";
    $subject = "=?UTF-8?B?" . base64_encode('instruction') . "?=";

 // $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
   $result = mail($to, $subject, $messageBody, $headers);
    if (!$result) {
      $message = t('instruction validatin : There was a problem sending your email notification to @email.', array('@email' => $to));
      \Drupal::messenger()->addMessage($message, 'error');
      \Drupal::logger('md_new_prestation')->error($message);
      return;
    } else {
      \Drupal::messenger()->addMessage(t('instruction validatin : Your message has been sent to  @email.', array('@email' => $to)));
    }
  } catch (\Exception $e) {
    \Drupal::logger('md_new_prestation')->error("Error in sendMail method: " . $e->getMessage());
  }
 
   $message = t('An email notification has been sent to @email :', array('@email' => $to));
 // drupal_set_message($message);
  \Drupal::logger('md_new_prestation')->notice($message);

  }


public static function sendMailComptable($to, $code, $title){
      try {
	  $config = \Drupal::config('Helper.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'md_new_prestation';
  $key = 'node_insert';
  //$email = 'i.aittalount@aust.gov.ma';  
  //  $email = 'i.aittalount@aust.gov.ma,m.abrou@aust.gov.ma,n.merouan@aust.gov.ma,h.afif@aust.gov.ma,samirjabbari@gmail.com'; 
  $messageBody = "<html lang='en'><head><title> AUST </title></head><body><div>
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
                                                            <span style='font-size:12pt;'> " . str_replace("[code]", $code, $config->get('imessage_mail_comptable')['value']) . " </span>
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
  $params['node_title'] = "instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;
  
  $ccmail = 'i.aittalount@aust.gov.ma'; 
  $ccmail_m =  'dardev.maroc@gmail.com'; 
    $from_send = 'noreply@aust.ma';
    $headers = "From: ". $from_send . "\r\n" .
      "Bcc: " . $ccmail_m . "\r\n" .
	   "Cc: " . $ccmail . "\r\n" .
      "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
      "MIME-Version: 1.0" . "\r\n" .
      "Content-type: text/html; charset=UTF-8" . "\r\n";
    $subject = "=?UTF-8?B?" . base64_encode('instruction') . "?=";

 // $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
   $result = mail($to, $subject, $messageBody, $headers);
    if (!$result) {
      $message = t('instruction Comptable : There was a problem sending your email notification to @email.', array('@email' => $to));
      \Drupal::messenger()->addMessage($message, 'error');
      \Drupal::logger('md_new_prestation')->error($message);
      return;
    } else {
      \Drupal::messenger()->addMessage(t('instruction Comptable : Your message has been sent to  @email.', array('@email' => $to)));
    }
  } catch (\Exception $e) {
    \Drupal::logger('md_new_prestation')->error("Error in sendMail method: " . $e->getMessage());
  }
 
   $message = t('An email notification has been sent to @email :', array('@email' => $to));
 // drupal_set_message($message);
  \Drupal::logger('md_new_prestation')->notice($message);
  }



public static function sendMailAnnulation($to, $code,$commnetaire ,$title){
      try {
	  $config = \Drupal::config('Helper.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'md_new_prestation';
  $key = 'node_insert';
  $to = $to; 
  $search  = array('[code]', '[commenatire]');
  $replace = array($code, $commnetaire);
  $messageBody = "<html lang='en'><head><title> AUST </title></head><body><div>
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
                                                            <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('imessage_mail_annulation')['value']) . " </span>
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
   $params['node_title'] = "instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;
  
  // $ccmail = $config->get('emailcci');
  $ccmail_m =  'dardev.maroc@gmail.com'; 
    $from_send = 'noreply@aust.ma';
    $headers = "From: ". $from_send . "\r\n" .
      "Bcc: " . $ccmail_m . "\r\n" .
      "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
      "MIME-Version: 1.0" . "\r\n" .
      "Content-type: text/html; charset=UTF-8" . "\r\n";
    $subject = "=?UTF-8?B?" . base64_encode('instruction') . "?=";

 // $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
   $result = mail($to, $subject, $messageBody, $headers);
    if (!$result) {
      $message = t('instruction Annulation : There was a problem sending your email notification to @email.', array('@email' => $to));
      \Drupal::messenger()->addMessage($message, 'error');
      \Drupal::logger('md_new_prestation')->error($message);
      return;
    } else {
      \Drupal::messenger()->addMessage(t('instruction Annulation : Your message has been sent to  @email.', array('@email' => $to)));
    }
  } catch (\Exception $e) {
    \Drupal::logger('md_new_prestation')->error("Error in sendMail method: " . $e->getMessage());
  }
 
   $message = t('An email notification has been sent to @email :', array('@email' => $to));
 // drupal_set_message($message);
  \Drupal::logger('md_new_prestation')->notice($message);

  }


public static function sendMailFacture($to, $code, $title){
      try {
	  $config = \Drupal::config('Helper.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'md_new_prestation';
  $key = 'node_insert';
  $to = $to; 
  $messageBody = "<html lang='en'><head><title> AUST </title></head><body><div>
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
                                                            <span style='font-size:12pt;'> " . str_replace("[code]", $code, $config->get('imessage_mail')['value']) . " </span>
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
   $params['node_title'] = "instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;
  
  // $ccmail = $config->get('emailcci');
  $ccmail_m =  'dardev.maroc@gmail.com'; 
    $from_send = 'noreply@aust.ma';
    $headers = "From: ". $from_send . "\r\n" .
      "Bcc: " . $ccmail_m . "\r\n" .
      "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
      "MIME-Version: 1.0" . "\r\n" .
      "Content-type: text/html; charset=UTF-8" . "\r\n";
    $subject = "=?UTF-8?B?" . base64_encode('instruction') . "?=";

 // $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
   $result = mail($to, $subject, $messageBody, $headers);
    if (!$result) {
      $message = t('instruction Facture : There was a problem sending your email notification to @email.', array('@email' => $to));
      \Drupal::messenger()->addMessage($message, 'error');
      \Drupal::logger('md_new_prestation')->error($message);
      return;
    } else {
      \Drupal::messenger()->addMessage(t('instruction Facture : Your message has been sent to  @email.', array('@email' => $to)));
    }
  } catch (\Exception $e) {
    \Drupal::logger('md_new_prestation')->error("Error in sendMail method: " . $e->getMessage());
  }
 
   $message = t('An email notification has been sent to @email :', array('@email' => $to));
 // drupal_set_message($message);
  \Drupal::logger('md_new_prestation')->notice($message);
  }


public static function sendtoadministration($to, $code, $title,$superficie ){
     try {
	  $config = \Drupal::config('Helper.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'md_new_prestation';
  $key = 'node_insert';
 $ccmail = $config->get('iemailcci');
  $search  = array('[code]', '[superficie]');
  $replace = array($code, $superficie);
  
  $email = 'm.abrou@aust.gov.ma,n.merouan@aust.gov.ma,h.afif@aust.gov.ma,s.jabbari@aust.gov.ma'; 
  $messageBody = "<html lang='en'><head><title> AUST </title></head><body><div>
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
                                                            <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('toadministration')['value']) . " </span>
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
 $params['node_title'] = "instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;
  
   $ccmail = $email;
  $ccmail_m =  'dardev.maroc@gmail.com'; 
    $from_send = 'noreply@aust.ma';
    $headers = "From: ". $from_send . "\r\n" .
      "Bcc: " . $ccmail_m . "\r\n" .
      //"Cc: " . $ccmail . "\r\n" .
      "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
      "MIME-Version: 1.0" . "\r\n" .
      "Content-type: text/html; charset=UTF-8" . "\r\n";
    $subject = "=?UTF-8?B?" . base64_encode('instruction') . "?=";

 // $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
   $result = mail($to, $subject, $messageBody, $headers);
    if (!$result) {
      $message = t('instruction validatin : There was a problem sending your email notification to @email.', array('@email' => $to));
      \Drupal::messenger()->addMessage($message, 'error');
      \Drupal::logger('md_new_prestation')->error($message);
      return;
    } else {
      \Drupal::messenger()->addMessage(t('instruction validatin : Your message has been sent to  @email.', array('@email' => $to)));
    }
  } catch (\Exception $e) {
    \Drupal::logger('md_new_prestation')->error("Error in sendMail method: " . $e->getMessage());
  }
 
   $message = t('An email notification has been sent to @email :', array('@email' => $to));
 // drupal_set_message($message);
  \Drupal::logger('md_new_prestation')->notice($message);

}
}