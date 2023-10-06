<?php
namespace Drupal\dardev_appel_offre\Newsletter;

use Drupal\dardev_appel_offre\Helper\Helper;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\node\Entity\Node;

Class Newsletter {
 // const SETTINGS = 'dardev_appel.settings';

  public function openModalForm() {
    $response = new AjaxResponse();
    $modal_form = \Drupal::formBuilder()->getForm('Drupal\dardev_appel_offre\Form\SendForm');
    $response->addCommand(new OpenModalDialogCommand(t('Send Newsletter'), $modal_form, ['width' => '1200']));
    return $response;
  }

public static function newsLetterHTML(){

            $config = \Drupal::config('dardev_appel.settings');
            \Drupal::messenger()->addMessage($config->get('nidds_val'));
            //$config = \Drupal::config(Helper::SETTINGS);
            $niids = $config->get('nidds_val');
            $acheteur_public = \Drupal::config('system.site')->get('name');
            
            
            $node = Node::load($niids);
            $date_mise_ligne =  $node->created->value;
            $objet =  $node->field_objet_de_l_appel_d_offres->value;
            $reference =  $node->field__appel_d_offre->value;
            $date_heure_remise_plis =  $node->field_date_et_heure_d_ouverture->value;
            $date_heure_remise_plis = date('m/d/Y H:i:s', $date_heure_remise_plis);
            
            // $objet =  "objetc 1"; //$nid_value->get('field_objet_de_l_appel_d_offres')->value;
            // $reference = "ref 1"; //$nid_value->get('field__appel_d_offre')->value;
            // $date_mise_ligne = "date 1"; //$nid_value->get('created')->value;
            // $date_heure_remise_plis = "date 2"; //$nid_value->get('field_date_et_heure_d_ouverture')->value;

            //\Drupal::messenger()->addMessage($nid_value);
            $host = \Drupal::request()->getSchemeAndHttpHost() . "/aunador";
            $news = \Drupal\node\Entity\Node::loadMultiple($config->get('appel_d_offre'));
            $search  = array('[acheteur_public]', '[objet]' , '[reference]','[date_mise_ligne]', '[date_heure_remise_plis]');
            $replace = array($acheteur_public,$objet, $reference,$date_mise_ligne,$date_heure_remise_plis);
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
                                    <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('intro')['value']) . " </span>
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

            

            return $params['message'];
}
  
public static function sendNewsLetter($niids)
    {
      $config = \Drupal::config(Helper::SETTINGS);
      //$user_email = $email->email;
      $site_name = \Drupal::config('system.site')->get('name');
      $site_email = \Drupal::config('system.site')->get('mail');
      $subject = $config->get('subject');
      $params = static::newsLetterHTML();
        $result = "";

         $emails = array(); 
         $controller = new Helper();
         foreach($controller->listEmails($niids) as $nids){
                        $emails[] = $nids->email;
                        
                    }
        //kint($emails);
        //die;
        
        //$emails = Helper::listEmails($niid);
        $langcode = \Drupal::currentUser()->getPreferredLangcode();
        $send = true;
        $reply = NULL;
        $from_send = 'e_services@aunador.ma';
        $message['headers']['Content-Type'] = 'text/html; charset=UTF-8; format=flowed; delsp=yes';
        $headers = "From: ". $from_send . "\r\n" .
                    "MIME-Version: 1.0" . "\r\n" .
                    "Content-type: text/html; charset=UTF-8" . "\r\n";
            $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
            
                  if(empty($emails)) {
                      \Drupal::messenger()->addMessage(t('Your message has not been sent to  @email.'));
                      
          }else {
              foreach($emails as $email){


          //  var_dump($email); echo "</br>";

          $result =  mail($email, $subject, $params, $headers);
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

public static function mail_utf8($to, $from_user, $from_email, $subject, $message = '')
    {
        $from_user = "=?UTF-8?B?" . base64_encode($from_user) . "?=";
        $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
        $headers = "From: $from_user <$from_email>\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";
        return mail($to, $subject, $message, $headers);
        $message_y = t('Your message has been sent to  @email.', array('@email' => $to));
        \Drupal::messenger()->addMessage($message_y, 'error');
        \Drupal::logger('notemail')->error($message_y);
        \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $to)));
    }

}
