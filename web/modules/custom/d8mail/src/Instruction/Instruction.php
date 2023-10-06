<?php

namespace Drupal\d8mail\Instruction;
//require_once 'C:/wamp64/www/aust/vendor/autoload.php';
//require_once '/home/clients/6a4bc57ff0996f843d4507bc1f2b5951/aubm/vendor/autoload.php';
//require __DIR__ . '/vendor/autoload.php';
//echo (require __DIR__);
//use Twilio\Rest\Client;
require_once '/home/dardev/public_html/aut.dardev.ma/vendor/autoload.php';
//require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;



class Instruction{
	  const SETTINGS = 'd8mail.settings';

  public static function sendSMS($number, $code){
$config = \Drupal::config('d8mail.settings');
$sid    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
$token  = "05c5ce6fba5353c372d542a7c23e8e32";
$twilio = new Client($sid, $token);
 //   $twilio = new Client(self::SID, self::TOKEN);
    try { 
 $twilio->messages
                ->create($number,
                           array("from" => "+12024106762", "body" =>str_replace("[code]", $code, $config->get('message_phone')))
                  );
	} catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
 //   drupal_set_message($message, 'error');
    \Drupal::logger('d8mail')->error($message);
    return;
     }
   
  }
  public static function sendSMSFinal($number, $code){
    $config = \Drupal::config('d8mail.settings'); 
$sid    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
$token  = "05c5ce6fba5353c372d542a7c23e8e32";
$twilio = new Client($sid, $token);
 try { 
 $result =  $twilio->messages
                ->create($number,
                           array("from" => "+12024106762", "body" => str_replace("[code]", $code, $config->get('message_phone_final')))
                  );
                  return $result;
				  
				  } catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
   // drupal_set_message($message, 'error');
    \Drupal::logger('d8mail')->error($message);
    return;
     }
  }

  public static function sendMail($to, $code, $title){
	  $config = \Drupal::config('d8mail.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'd8mail';
  $key = 'node_insert';
  $to = $to; 
  $params['message'] = "<html lang='en'><head><title> Agence Urbaine de Skhirate Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png' >
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
                                                            <span style='font-size:12pt;'> " . str_replace("[code]", $code, $config->get('message_mail')['value']) . " </span>
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
  $params['node_title'] = "pré-instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;

  $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  if ( ! $result['result']) {
    $message = t('There was a problem sending your email notification to @email for creating node @id.');
  //  drupal_set_message($message, 'error');
    \Drupal::logger('d8mail')->error($message);
    return;
  }

  $message = t('An email notification has been sent to @email for creating node @id.');
 // drupal_set_message($message);
  \Drupal::logger('d8mail')->notice($message);

  }

public static function sendMailFinal($to, $code, $title , $avis_pdf, $avis){
	$config = \Drupal::config('d8mail.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'd8mail';
  $key = 'node_insert';
  $to = $to; 
  $search  = array('[code]', '[avis]', '[url_avis_pdf]');
$replace = array($code, $avis, $avis_pdf);
      $params['message'] = "<html lang='en'><head><title> Agence Urbaine de Skhirate Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png' >
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
                                                             <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailFinal')['value']) . "</span>
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
      $params['node_title'] = "pré-instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;

  $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  if ( ! $result['result']) {
    $message = t('There was a problem sending your email notification to @email for creating node @id.');
  //  drupal_set_message($message, 'error');
    \Drupal::logger('d8mail')->error($message);
    return;
  }

  $message = t('An email notification has been sent to @email for creating node @id.');
 // drupal_set_message($message);
  \Drupal::logger('d8mail')->notice($message);
  }


public static function sendMailFinalCCI($to, $code, $title , $avis_pdf, $avis){
	$config = \Drupal::config('d8mail.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'd8mail';
  $key = 'node_insert';
  $to = 's.jabbari@aust.gov.ma';
  $search  = array('[code]', '[avis]', '[url_avis_pdf]');
$replace = array($code, $avis, $avis_pdf);
      $params['message'] = "<html lang='en'><head><title> Agence Urbaine de Skhirate Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png' >
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
                                                             <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailFinal')['value']) . "</span>
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
      $params['node_title'] = "pré-instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;

  $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  if ( ! $result['result']) {
    $message = t('There was a problem sending your email notification to @email for creating node @id.');
  //  drupal_set_message($message, 'error');
    \Drupal::logger('d8mail')->error($message);
    return;
  }

  $message = t('An email notification has been sent to @email for creating node @id.');
 // drupal_set_message($message);
  \Drupal::logger('d8mail')->notice($message);
  }

   
  
public static function sendMailFinalSansPDF($to, $code, $title ,$avis){
	$config = \Drupal::config('d8mail.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'd8mail';
  $key = 'node_insert';
  $to = $to; 
  $search  = array('[code]', '[avis]');
$replace = array($code, $avis);
      $params['message'] = "<html lang='en'><head><title> Agence Urbaine de Skhirate Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png' >
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
                                                             <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailFinal')['value']) . "</span>
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
      $params['node_title'] = "pré-instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;

  $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  if ( ! $result['result']) {
    $message = t('There was a problem sending your email notification to @email for creating node @id.');
  //  drupal_set_message($message, 'error');
    \Drupal::logger('d8mail')->error($message);
    return;
  }

  $message = t('An email notification has been sent to @email for creating node @id.');
 // drupal_set_message($message);
  \Drupal::logger('d8mail')->notice($message);
  }

  
public static function sendMailFinalSansPDFCCI($to, $code, $title ,$avis){
	$config = \Drupal::config('d8mail.settings');
	  $mailManager = \Drupal::service('plugin.manager.mail');

  $module = 'd8mail';
  $key = 'node_insert';
  $to = 's.jabbari@aust.gov.ma,mourad.dardari@gmail.com'; 
  $search  = array('[code]', '[avis]');
$replace = array($code, $avis);
      $params['message'] = "<html lang='en'><head><title> Agence Urbaine de Skhirate Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png' >
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
                                                             <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailFinal')['value']) . "</span>
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
      $params['node_title'] = "pré-instruction";
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = true;

  $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  if ( ! $result['result']) {
    $message = t('There was a problem sending your email notification to @email for creating node @id.');
  //  drupal_set_message($message, 'error');
    \Drupal::logger('d8mail')->error($message);
    return;
  }

  $message = t('An email notification has been sent to @email for creating node @id.');
 // drupal_set_message($message);
  \Drupal::logger('d8mail')->notice($message);
  }

   

}
