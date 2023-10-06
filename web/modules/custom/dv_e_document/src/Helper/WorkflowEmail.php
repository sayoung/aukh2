<?php

namespace Drupal\dv_e_document\Helper;
require_once '/home/austma/public_html/vendor/autoload.php';
//require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\dv_e_document\Controller\MainControllerWorflow;
use Drupal\node\Entity\Node;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Component\Utility\SafeMarkup;



Class WorkflowEmail{

 const MAILSETTINS = 'workflow.settings';
	  




    public static function sendMailWorflow($document_email,$document_nom, $document_suivi,$document_command,$document_etape ){
        $config = \Drupal::config('workflow.settings');
        $mailManager = \Drupal::service('plugin.manager.mail');
        $module = 'dv_e_document';
        $key = 'sendMailWorflow';
        $logo = 'https://www.aust.ma/themes/gavias_castron/logo-fr.png';
                if ($document_etape == "document_verificaton") {
            $document_etape_m = "Document vérificaton";
        }elseif ($document_etape == "document_annuler") {
            $document_etape_m = "Document annulé";
        } elseif ($document_etape == "document_comptabilite") {
            $document_etape_m = "Comptabilité";
        } elseif ($document_etape == "document_traitement") {
            $document_etape_m = "Traitement";
        }elseif ($document_etape == "document_livree") {
            $document_etape_m = "Document livré";
        }elseif ($document_etape == "document_paiement_annule") {
            $document_etape_m = "Annuler le paiement";
        }elseif ($document_etape == "annulation") {
            $document_etape_m = "Annulation";
        }
        $search  = array('[email_client]', '[nom_complet]' , '[code]','[province]', '[n_command]','[etape]');
        $replace = array($document_email,$document_nom, $document_suivi,$document_province,$document_command,$document_etape);
        $params['message'] = "<html lang='en'><head><title> Agence urbaine de nador </title></head><body><div>
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
                                                                    <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailWorflow')['value']) . " </span>
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
                $params['node_title'] = "E-document : " . $document_etape_m . " N commande :   " . $document_command;

                $langcode = \Drupal::currentUser()->getPreferredLangcode();
                $send = true;
                $ccmail = $config->get('emailcci');
                $reply = NULL;
                $from_send = 'documents@aust.ma';
                $message['headers']['Content-Type'] = 'text/html; charset=UTF-8; format=flowed; delsp=yes';
                $headers = "From: ". $from_send . "\r\n" .
                            "MIME-Version: 1.0" . "\r\n" .
                            "Content-type: text/html; charset=UTF-8" . "\r\n";
                    $subject_info = "E-document : " . $document_etape_m . " N commande :   " . $document_command;
                    $subject = "=?UTF-8?B?" . base64_encode($subject_info) . "?=";

        $controller = new MainControllerWorflow();
                    $too = array(); 
                    
                    foreach($controller->getMailsByRoleTech($document_etape) as $user){
                        $too[] = $user->get('mail')->value;
                        
                    }
            foreach($too as $email){
                //  var_dump($email); echo "</br>";
                $result =  mail($email, $subject, $params['message'], $headers);
                //	echo "ok";
                if ($result !== true) {
                    $message = t('There was a problem sending your email notification to @email.', array('@email' => $email));
                    \Drupal::messenger()->addMessage($message, 'error');
                    \Drupal::logger('Workflowmail')->error($message);
                  }
                  else {
                    \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $email)));
                  }
            }
    }

}