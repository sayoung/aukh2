<?php

namespace Drupal\notemail\Helper;
require_once '/home/aukhtequality/public_html/vendor/autoload.php';
// require __DIR__ . '/vendor/autoload.php';
// $autoloaderPath = '/home/' . basename(__DIR__) . '/public_html/vendor/autoload.php';
// echo (__DIR__);
// echo "<br/>";
// require_once $autoloaderPath;

use Twilio\Rest\Client;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\notemail\Controller\MainController;
use Drupal\node\Entity\Node;
use Drupal\Core\Render\Markup;



Class Helper{
	  const SETTINGS = 'notemail.settings';

	 // const $aa    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
 // const $aaa  = "05c5ce6fba5353c372d542a7c23e8e32";
  public static function sendSMS($number, $code){
$config = \Drupal::config('notemail.settings');
$sid    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
$token  = "05c5ce6fba5353c372d542a7c23e8e32";
$twilio = new Client($sid, $token);
 //   $twilio = new Client(self::SID, self::TOKEN);
      try {
       $twilio->messages
                ->create($number,
                           array("from" => "+12024106762", "body" =>str_replace("[code]", $code, $config->get('message_phone')))
						    );

    return;

     } catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
   // \Drupal::messenger()->addMessage($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
     }
    
                 
  }



  
  public static function sendMailAvant($to, $code, $title, $nom, $prenom ,$titre_foncier){
	  $config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
      $to = $to;
	  $search  = array('[code]', '[nom]', '[prenom]','[titre_f]');
	  $replace = array($code , $nom, $prenom,$titre_foncier);
      $params['message'] = "<html lang='en'><head><title> Agence urbaine de Skhirate-Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png'  alt='logo aust'>
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
                                                            <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('message_mail_avant')['value']) . " </span>
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
		$subject_note = "Votre demande de la Note de Renseignements relative au terrain objet du TF : " . $titre_foncier ;
		$params['node_title'] = $subject_note;
		$from_send = 'noreply@aust.ma';
		$ccmail = $config->get('emailcci');
		  
		$headers = "From: ". $from_send . "\r\n" .
				"Bcc: " . $ccmail . "\r\n" .
				"MIME-Version: 1.0" . "\r\n" .
				"Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
		
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
	 
	  
	  
		$result = $mailManager->mail($module, $key, $to, 'en', $params, 'nru@aust.gov.ma', TRUE);
		if ($result['result'] !== true) {
		   \Drupal::messenger()->addStatus(t('There was a problem sending your message and it was not sent.'), 'error');
		 }
		else {
			 $message = t('An email notification has been sent to @email ', array('@email' => $to));
		  \Drupal::messenger()->addMessage($message);
			\Drupal::logger('notemail')->notice($message);
		}
	  
  }
  
  


  
  public static function sendMail($to, $code, $title, $nom, $prenom ,$titre_foncier){
  try {
    $config = \Drupal::config('notemail.settings');
    $module = 'notemail';
    $key = 'send_confirmation';
    $search  = array('[code]', '[nom]', '[prenom]','[titre_f]');
    $replace = array($code , $nom, $prenom, $titre_foncier);

    $messageBody = "<html lang='en'><head><title> Agence urbaine de Skhirate-Témara </title></head><body><div>
                                                                <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png'  alt='logo aust'>
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
                            <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('message_mail')['value']) . " </span>
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

    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $subjectNote = "Votre demande de la Note de Renseignements relative au terrain objet du TF : " . $titre_foncier;
    $params['node_title'] = $subjectNote;

    $ccmail = $config->get('emailcci');
    $ccmail_m = "mourad.dardari@gmail.com";
    $from_send = 'noreply@aust.ma';
    $headers = "From: ". $from_send . "\r\n" .
      "Bcc: " . $ccmail_m . "\r\n" .
      "Cc: " . $ccmail . "\r\n" .
      "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
      "MIME-Version: 1.0" . "\r\n" .
      "Content-type: text/html; charset=UTF-8" . "\r\n";
    $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";

    $result = mail($to, $subject, $messageBody, $headers);
    if (!$result) {
      $message = t('There was a problem sending your email notification to @email for creating node @id.', array('@email' => $to));
      \Drupal::messenger()->addMessage($message, 'error');
      \Drupal::logger('notemail')->error($message);
      return;
    } else {
      \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $to)));
    }
  } catch (\Exception $e) {
    \Drupal::logger('notemail')->error("Error in sendMail method: " . $e->getMessage());
  }
}

  
  
  public static function sendMailccicomptable($commande, $tf, $title){
	  $config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
     // $to = $to;
  $search  = array('[cmd]', '[tf]');
  $replace = array($commande, $tf);
      $params['message'] = "<html lang='en'><head><title> Agence urbaine de Skhirate-Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png'  alt='logo aust'>
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
                                                            <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('imessage_mail_cci_comptable')['value']) . " </span>
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
	  //$to
         //   return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  $from_send = 'nru@aust.gov.ma';

	  $ccmail = $config->get('emailccicomptable');
	    $headers = "From: ". $from_send . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
		
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
	  $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      return mail($ccmail, $subject, $params['message'], $headers);
	  
  }

public static function sendMail_cci($to, $code, $title,$nom , $prenom){
	  $config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
      $to = $to;
      $params['message'] = "<html lang='en'><head><title> Agence urbaine de Skhirate-Témara </title></head><body><div>
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
                                                            <span style='font-size:12pt;'> Demande de la part : " . $nom  . " " . $prenom . " </span></br>
                                                        </font>
                                                    </div>
													<div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
															<span style='font-size:12pt;'> E-mail : " . $to . " </span>
                                                        </font>
                                                    </div>
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
      $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $controller = new MainController();
	  $too = array(); 
	  foreach($controller->getMailsByRoleTechVer() as $user){
	    $too[] = $user->get('mail')->value;
	//	print_r($emails); echo "</br>";
		
		
      
	}
	// $ccmail = array(); 
	 $ccmail = $config->get('emailcci');
//$too  = array('dardari.mourad@gmail.com','m.dardari@tequality.ma');
//foreach($ccmail as $email){
                                //  var_dump($email); echo "</br>";
									$mailManager->mail($module, $key, $ccmail, $langcode, $params, NULL, $send);
								//	echo "ok";
                              //  }


	
	//return $mailManager->mail($module, $key, $user->get('mail')->value, $langcode, $params, NULL, $send);
  }

public static function sendMailFinal($to, $code, $title,$note,$titre_f){
	$config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'send_note';
  $search  = array('[code]', '[note]', '[titre_f]');
  $replace = array($code, $note, $titre_f);
      $params['message'] = Markup::create("<html lang='en'><head><title> Agence urbaine de Skhirate-Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png'  alt='aust'>
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
                                                             <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailFinal')['value']) . " </span>
                                                        </font>
										
                                                    </div>
                                                </td>
                                            </tr>
											<tr style='height:32.8pt;' height='54'>
											<td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
											<a href='mailto:nru@aust.gov.ma'>unsubscribe !</a>
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
                                </div></body></html>");
      //$params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $subject_note = "Votre demande de la Note de Renseignements relative au terrain objet du TF : " . $titre_f ;
	   $params['node_title'] = $subject_note;
	$attachments_note = array(
     'filecontent' => file_get_contents($note),
     'filename' => 'note-' . $titre_f . '.pdf',
     'filemime' => 'application/pdf',
	 
   );
   $params['attachments'][] = [
        'filecontent' => file_get_contents($note),
        'filename' => 'note-' . $titre_f . '.pdf',
        'filemime' => 'application/pdf',
    ];
   /*
    $attachments_extrait = array(
     'filecontent' => file_get_contents($extrait),
     'filename' => 'Extrait-' . $titre_f . '.pdf',
     'filemime' => 'application/pdf',
	 
   );
   $attachments_reglement = array(
     'filecontent' => file_get_contents($reglement),
     'filename' => 'reglement-' . $titre_f . '.pdf',
     'filemime' => 'application/pdf',
	 
   );
   */

   // $params['attachments'][] = $attachments_note;
   // $params['attachments'][] = $attachments_extrait;
   // $params['attachments'][] = $attachments_reglement;
   $params['format'] = 'text/html';

   
	  //$ccmail = $config->get('emailcci');
	  /*
	  $from_send = 'nru@aust.gov.ma';
	    $headers = "From: ". $from_send . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n"; */

   $params['headers'] = [
        'Bcc' => "cci@dardev.ma",
        'Cc' => $config->get('emailcci'),
    ];
	  
	  
	   // $result = $mailManager->mail($module, $key, $to, 'en', $params, 'nru@aust.gov.ma', TRUE);
	    $result = \Drupal::service('plugin.manager.mail')->mail($module, $key, $to, $langcode, $params, 'nru@aust.gov.ma', TRUE);
if ($result['result'] !== true) {
        \Drupal::messenger()->addStatus(t('There was a problem sending your message and it was not sent.'), 'error');
    } else {
        $message = t('An email notification has been sent to @email ', array('@email' => $to));
        \Drupal::messenger()->addMessage($message);
        \Drupal::logger('notemail')->notice($message);
    }

  }



public static function sendMailFinal_3($to, $code, $title,$note,$extrait,$reglement, $regelement_note_2, $regelement_note_3,$titre_f){
	$config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'send_note';
  $search  = array('[code]', '[note]', '[extrait]', '[reglement]','[titre_f]');
  $replace = array($code, $note,$extrait, $reglement , $titre_f);
      $params['message'] = Markup::create("<html lang='en'><head><title> Agence urbaine de Skhirate-Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png'  alt='aust'>
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
                                                             <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailFinal')['value']) . " </span>
                                                        </font>
										
                                                    </div>
                                                </td>
                                            </tr>
											<a href='mailto:votreadresse@mail.fr'>unsubscribe !</a>
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
                                </div></body></html>");
      //$params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $subject_note = "Votre demande de la Note de Renseignements relative au terrain objet du TF : " . $titre_f ;
	   $params['node_title'] = $subject_note;
	$attachment_note = array(
     'filecontent' => file_get_contents($note),
     'filename' => 'note_' . $titre_f . '.pdf',
     'filemime' => 'application/pdf',
	 
   );
    $attachment_extrait = array(
     'filecontent' => file_get_contents($extrait),
     'filename' => 'Extrait_' . $titre_f . '.pdf',
     'filemime' => 'application/pdf', 
   );
       $attachment_reglement = array(
     'filecontent' => file_get_contents($reglement),
     'filename' => 'reglement_' . $titre_f . '.pdf',
     'filemime' => 'application/pdf', 
   );
   
    $attachment_reglement_2 = array(
     'filecontent' => file_get_contents($regelement_note_2),
     'filename' => 'reglement_2_' . $titre_f . '.pdf',
     'filemime' => 'application/pdf', 
   );
   
    $attachment_reglement_3 = array(
     'filecontent' => file_get_contents($regelement_note_3),
     'filename' => 'reglement_3_' . $titre_f . '.pdf',
     'filemime' => 'application/pdf', 
   );
   
   $ccmail = $config->get('emailcci');
   $params['attachments'][] = $attachment_note;
   $params['attachments'][] = $attachment_extrait;
   $params['attachments'][] = $attachment_reglement;
   $params['attachments'][] = $attachment_reglement_2;
   $params['attachments'][] = $attachment_reglement_3;
   
   $params['format'] = 'text/html';
   $params['headers']['Bcc'] = "cci@dardev.ma";
   $params['headers']['Cc'] = $ccmail;
   
	  //$ccmail = $config->get('emailcci');
	  $from_send = 'noreply@aust.ma';
	    $headers = "From: ". $from_send . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
		
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
	  
	  
	    $result = $mailManager->mail($module, $key, $to, 'en', $params, 'nru@aust.gov.ma', TRUE);
 if ($result['result'] !== true) {
   \Drupal::messenger()->addStatus(t('There was a problem sending your message and it was not sent.'), 'error');
 }
 else {
      $message = t('An email notification has been sent to @email ', array('@email' => $to));
  \Drupal::messenger()->addMessage($message);
    \Drupal::logger('notemail')->notice($message);
 }
 //$subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
 //  return mail($to, $subject_note, $params['message'], $headers);
 
    //  return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  }




public static function sendMailFinal_2($to, $code, $title,$note,$extrait,$reglement, $regelement_note_2,$titre_f){
	$config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'send_note';
  $search  = array('[code]', '[note]', '[extrait]', '[reglement]', '[regelement_note_2]','[titre_f]');
  $replace = array($code, $note,$extrait, $reglement, $regelement_note_2 , $titre_f);
      $params['message'] = Markup::create("<html lang='en'><head><title> Agence urbaine de Skhirate-Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png'  alt='aust'>
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
                                                             <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailFinal')['value']) . " </span>
                                                        </font>
										
                                                    </div>
                                                </td>
                                            </tr>
											<a href='mailto:votreadresse@mail.fr'>unsubscribe !</a>
											
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
									
                                </div></body></html>");
      //$params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $subject_note = "Votre demande de la Note de Renseignements relative au terrain objet du TF : " . $titre_f ;
	   $params['node_title'] = $subject_note;
	  $attachment_note = array(
     'filecontent' => file_get_contents($note),
     'filename' => 'note_' . $titre_f . '.pdf',
     'filemime' => 'application/pdf',
	 
   );
    $attachment_extrait = array(
     'filecontent' => file_get_contents($extrait),
     'filename' => 'Extrait_' . $titre_f . '.pdf',
     'filemime' => 'application/pdf', 
   );
       $attachment_reglement = array(
     'filecontent' => file_get_contents($reglement),
     'filename' => 'reglement_' . $titre_f . '.pdf',
     'filemime' => 'application/pdf', 
   );
   
    $attachment_reglement_2 = array(
     'filecontent' => file_get_contents($regelement_note_2),
     'filename' => 'reglement_2_' . $titre_f . '.pdf',
     'filemime' => 'application/pdf', 
   );
   
   
   
   $params['attachments'][] = $attachment_note;
   $params['attachments'][] = $attachment_extrait;
   $params['attachments'][] = $attachment_reglement;
   $params['attachments'][] = $attachment_reglement_2;
   
   $ccmail = $config->get('emailcci');
   $params['format'] = 'text/html';
   $params['headers']['Bcc'] = "cci@dardev.ma";
   $params['headers']['Cc'] = $ccmail;
   
	  //$ccmail = $config->get('emailcci');
	  $from_send = 'noreply@aust.ma';
	    $headers = "From: ". $from_send . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
		
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
	  
	  
	    $result = $mailManager->mail($module, $key, $to, 'en', $params, 'nru@aust.gov.ma', TRUE);
 if ($result['result'] !== true) {
   \Drupal::messenger()->addStatus(t('There was a problem sending your message and it was not sent.'), 'error');
    $message = t('There was a problem sending your message and it was not sent.');
   // \Drupal::messenger()->addMessage($message, 'error');
    \Drupal::logger('notemail')->error($message);
 }
 else {
        $message = t('An email notification has been sent to @email ', array('@email' => $to));
  \Drupal::messenger()->addMessage($message);
  \Drupal::logger('notemail')->notice($message);
 }
 //$subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
 //  return mail($to, $subject_note, $params['message'], $headers);
 
    //  return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  }







 public static function sendMailFinal_cci($to, $code, $title,$note,$extrait,$reglement,$nom , $prenom,$titre_f){
	$config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
  $search  = array('[code]', '[note]', '[extrait]', '[reglement]','[titre_f]','[nom]','[prenom]' );
  $replace = array($code, $note,$extrait, $reglement , $titre_f , $nom , $prenom);
      $params['message'] = Markup::create("<html lang='en'><head><title> Agence urbaine de Skhirate-Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png'   alt='logo aust'>
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
                                                             <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailFinal')['value']) . " </span>
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
                                </div></body></html>");
	   $subject_note = "Votre demande de la Note de Renseignements relative au terrain objet du TF : " . $titre_f ;
	   
      $params['node_title'] = $subject_note;
	  
	$attachments_note = array(
     'filecontent' => file_get_contents($note),
     'filename' => 'note-' . $titre_f . '.pdf',
     'filemime' => 'application/pdf',
	 
   );
    $attachments_extrait = array(
     'filecontent' => file_get_contents($extrait),
     'filename' => 'Extrait-' . $titre_f . '.pdf',
     'filemime' => 'application/pdf',
	 
   );
   $attachments_reglement = array(
     'filecontent' => file_get_contents($reglement),
     'filename' => 'reglement-' . $titre_f . '.pdf',
     'filemime' => 'application/pdf',
	 
   );
   $params['attachments'][] = $attachments_note;
   $params['attachments'][] = $attachments_extrait;
   $params['attachments'][] = $attachments_reglement;
   $params['format'] = 'text/html';
   $ccmail = $config->get('emailcci');
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	$result = $mailManager->mail($module, $key, $ccmail, 'en', $params, 'nru@aust.gov.ma', TRUE);
 if ($result['result'] !== true) {
   \Drupal::messenger()->addStatus(t('There was a problem sending your message and it was not sent.'), 'error');
 }
 else {
   \Drupal::messenger()->addStatus(t('Your message has been sent.'));
 }  /*
	  $controller = new MainController();
	  $too = array(); 
	  foreach($controller->getMailsByRoleTechVer() as $user){
	    $too[] = $user->get('mail')->value;
	}
foreach($too as $email){                               
									$mailManager->mail($module, $key, $email, $langcode, $params, NULL, $send);
                                } */
  }
  
  public static function sendSMSFinal($number, $code){
    $config = \Drupal::config('notemail.settings'); 
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
   // \Drupal::messenger()->addMessage($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
     }
  }
  public static function sendSMSAnnulerpaiement($number, $code){
 $config = \Drupal::config('notemail.settings');    
$sid    = "AC2c48a26d30de1ad1c96aeaa76bc48a91";
$token  = "05c5ce6fba5353c372d542a7c23e8e32";
$twilio = new Client($sid, $token);
     try { 
	 $result =  $twilio->messages
                ->create($number,
                           array("from" => "+12024106762", "body" => str_replace("[code]", $code, $config->get('sendSMSAnnulerpaiement')))
                  );
                  return $result;
		     } catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
    // \Drupal::messenger()->addMessage($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
     }
  }
  
  public static function sendMailAnnulerpaiement($email, $code, $title,$nom , $prenom,$rf, $motif){
	  $config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'archive_note';
	  $search  = array('[code]', '[nom]', '[prenom]', '[reference]', '[motif]');
	  $replace = array($code, $nom , $prenom, $rf, $motif);
      $params['message'] = "<html lang='en'><head><title> Agence urbaine de Skhirate-Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png'  alt='logo aust'>
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
                                                            <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailAnnulerpaiement')['value']) . " </span>
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
        $params['node_title'] = "AUST :" . $title;
		$langcode = \Drupal::currentUser()->getPreferredLangcode();
		$send = true;
		$subject_note = "Votre demande de la Note de Renseignements relative au terrain objet du TF : " . $rf ;
		$params['node_title'] = $subject_note;

		 
		 
      $ccmail = $config->get('emailcci');
      $ccmail_m = "mourad.dardari@gmail.com";
      $message['headers']['Cc'] = $ccmail;
	  $from_send = 'noreply@aust.ma';
	  $headers = "From: ". $from_send . "\r\n" .
	        "Bcc: " . $ccmail . "\r\n" .
	        "Cc: " . $ccmail_m . "\r\n" .
	        "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";
	  $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
	  return $result = mail($email, $subject, $params['message'], $headers);

  if ( ! $result['result']) {
    $message = t('There was a problem sending your email notification to @email for disable node @id.', array('@email' => $email));
    \Drupal::messenger()->addMessage($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
  }else {
                    \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $email)));
                  }
		 
  }
  
 public static function sendMailAnnulerpaiement_cci($to, $code, $title,$nom , $prenom,$rf){
	  $config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'archive_note';
	  $search  = array('[code]', '[nom]', '[prenom]', '[reference]');
	  $replace = array($code, $nom , $prenom,$rf);
      $params['message'] = "<html lang='en'><head><title> Agence urbaine de Skhirate-Témara </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='/themes/gavias_castron/logo-fr.png'  alt='logo aust'>
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
                                                            <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('sendMailAnnulerpaiement')['value']) . " </span>
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
	  $controller = new MainController();
	  	  $too = array(); 
	  foreach($controller->getMailsByRoleTechVer() as $user){
	    $too[] = $user->get('mail')->value;
	}
foreach($too as $email){                               
									$mailManager->mail($module, $key, $email, $langcode, $params, NULL, $send);
                                }
  }
 
}
