<?php

namespace Drupal\dv_e_document\Helper;
//require_once '/var/www/austweb/aust/vendor/autoload.php';
//require_once '/home2/dardevma/au.dardev.ma/vendor/autoload.php';
//require __DIR__ . '/vendor/autoload.php';
//echo (require __DIR__);
use Twilio\Rest\Client;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;
//use Drupal\dv_e_document\Controller\MainController;
use Drupal\node\Entity\Node;


class Helper{
	  const SETTINGS = 'dv_e_document.settings';


public static function sendMail($to, $code, $title){
	  $config = \Drupal::config('dv_e_document.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'dv_e_document';
      $key = 'notif_article';
      $to = $to;
      
      $params['message'] = "<html lang='en'><head><title> Démonstration pour les agences urbaines </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://www.aust.ma/themes/gavias_castron/logo-fr.png' >
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
      $params['node_title'] = "AUST - E-note";
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  //$to
         //   return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  $from_send = 'documents@aust.ma';
	//  $ccmail = 'mourad.dardari@gmail.com,m.dardari@tequality.ma';
	  $ccmail = $config->get('emailcci');
	    $headers = "From: ". $from_send . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
	  $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      return mail($to, $subject, $params['message'], $headers);
  }

public static function sendMailFinal($document_email, $document_suivi,$document_uri,$document_nom ,$document_command){
        $config = \Drupal::config('dv_e_document.settings');
        $config_global = \Drupal::config('notemail.settings');
        $mailManager = \Drupal::service('plugin.manager.mail');
        $search_ht  = array('https://', 'http://');
        $replace_ht = array('', '');
        $document_uri = str_replace($search_ht,$replace_ht,$document_uri);
        $search  = array('[mail]', '[code]', '[document_uri]', '[client]','[n_command]');
        $replace = array($document_email, $document_suivi, $document_uri, $document_nom,$document_command);
        $module = 'dv_e_document';
        $key = 'send_document_file';
        $params['message'] = "<html lang='en'><head><title> Agence Urbaine de nador </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://dardev.ma/wp-content/uploads/2020/11/logo-160x97.png' >
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
                                                             <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('document_mail_final')['value']) . " </span>
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
                                                                        <span style='font-size:8.5pt;'>".$config_global->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config_global->get('footer2')."
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
      $params['node_title'] = "AUST - E-document";
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $ccmail = $config_global->get('emailcci');
	  $from_send = 'documents@aust.ma';
	    $headers = "From: ". $from_send . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
      $subject_info = "E-document";
	  $subject = "=?UTF-8?B?" . base64_encode($subject_info) . "?=";
     // return mail($document_email, $subject, $params['message'], $headers);

      $result =  mail($document_email, $subject, $params['message'], $headers);
                //	echo "ok";
                if ($result !== true) {
                    $message = t('There was a problem sending your email notification to @email.', array('@email' => $document_email));
                    \Drupal::messenger()->addMessage($message, 'error');
                    \Drupal::logger('Workflowmail')->error($message);
                  }
                  else {
                    \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $document_email)));
                  }
    //  return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  }

}
