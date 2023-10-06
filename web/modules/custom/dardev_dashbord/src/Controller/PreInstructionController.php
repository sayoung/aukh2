<?php
/**
 * @file
 * Contains \Drupal\dardev_dashbord\Controller\PreInstructionController.
 */

namespace Drupal\dardev_dashbord\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Site\Settings;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\user\Entity\User;
use Drupal\Core\Datetime\DrupalDateTime;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PreInstructionController extends ControllerBase
{
    /**
     * récupérer la liste des offres
     * @param $limit
     * @return \Drupal\Core\Entity\EntityInterface[]|static[]
     */
    public function getNotesA() {

        $weekAgo = new DrupalDateTime('-300 days');
        $weekAgo = $weekAgo->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT);  
        $weekplus = new DrupalDateTime('-3 days');
        $weekplus = $weekplus->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT);    
        $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $ids1 = \Drupal::entityQuery('node')
        ->condition('type', 'pre_instruction')
        ->condition('moderation_state','archived')
        ->condition('created', $weekplus, '<=')
        ->condition('created', $weekAgo, '>=')
        ->condition('langcode', $lang_code);
                $count = $ids1->count()->execute();
            // $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);
        return array(
            '#theme' => 'theme_dashbord',
            '#nodes' => $count,
            '#cur_lang' => $lang_code,
            '#attached' => array(
                    'library' => array(
                    'dardev_dashbord/md-patrimoine-hp'
                            )
                    )
        );
            return $users;
//kint($users->get('mail')->value);
}




public function getPreInstructionTraitee(Request $request) {
    $from = $request->request->get('from');
    $to = $request->request->get('to');
    if (empty($from)) {
        $from = "01-01-".date("Y") ;
    } else {
        echo  $from;
      //  echo ("<br>");
    //    echo date("Y");
    }
    if (empty($to)) {
        $to = "-1 day";
    } else {
       // echo  $to;
    }
    
    //$from = "19-11-2020";
   
   // $to = "19/11/2022";

    //$from = str_replace("/","-","Hello world!");


    //exit;

    
    $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $pre_count_livre = \Drupal::entityQuery('node');
    $pre_count_livre->condition('type', 'pre_instruction');
    $pre_count_livre->condition('moderation_state','instruction_dispatcher');
    $pre_count_livre->condition('created', strtotime($from), '>=');
    $pre_count_livre->condition('created', strtotime($to), '<=');
    $pre_count_livre->condition('langcode', $lang_code);
    $count_livre = $pre_count_livre->count()->execute();


    $id_livre = \Drupal::entityQuery('node');
    $id_livre->condition('type', 'pre_instruction');
    $id_livre->condition('moderation_state','instruction_dispatcher');
    $id_livre->condition('created', strtotime($from), '>=');
    $id_livre->condition('created', strtotime($to), '<=');
   // $id_livre->condition('langcode', $lang_code);
   // $id_livre->range(0,10);
    $id_livre_result = $id_livre->execute();
   //$bhhg = $id_livre->execute();
   $id_livre_result = Node::loadMultiple($id_livre_result);
  // kint($bhhg);
  // die;
    $id_too = array(); 

    foreach($id_livre_result as $note_l){
        $date1 = $note_l->get('created')->value;
        $date2 = $note_l->get('changed')->value;
        //kint($note_l->get('changed')->value);
       // $diff = date_diff( $date1, $date2 );echo "<br>";
        $time_elapsed= $date2-$date1;
        #convert as you like
        $minutes=$time_elapsed/60;
        $hours=$minutes/60;
         $days = $hours/24;
        #return the value
        $value = $days;
        $id_too [] = $value;
        //echo($value);
        
    }
    //echo $id_too;
    $a = array_filter($id_too);
    //echo $a;
    //die;
    if(empty($a)) {
       $average_livre = "-";
    }else{
        $average_livre = array_sum($a)/count($a);
    }
    
    

    $pre_nador_count = \Drupal::entityQuery('node');
    $pre_nador_count->condition('type', 'pre_instruction');
    $pre_nador_count->condition('moderation_state','instruction_dispatcher');
    $pre_nador_count->condition('created', strtotime($from), '>=');
    $pre_nador_count->condition('created', strtotime($to), '<=');
    $pre_nador_count->condition('field_prefecture',"Nador" );
    $pre_nador_count->condition('langcode', $lang_code);
    $nador_count = $pre_nador_count->count()->execute();

    $pre_driouch_count = \Drupal::entityQuery('node');
    $pre_driouch_count->condition('type', 'pre_instruction');
    $pre_driouch_count->condition('moderation_state','instruction_dispatcher');
    $pre_driouch_count->condition('created', strtotime($from), '>=');
    $pre_driouch_count->condition('created', strtotime($to), '<=');
    $pre_driouch_count->condition('field_prefecture',"Driouch" );
    $pre_driouch_count->condition('langcode', $lang_code);
    $driouch_count = $pre_driouch_count->count()->execute();


    $pre_guercif_count = \Drupal::entityQuery('node');
    $pre_guercif_count->condition('type', 'pre_instruction');
    $pre_guercif_count->condition('moderation_state','instruction_dispatcher');
    $pre_guercif_count->condition('created', strtotime($from), '>=');
    $pre_guercif_count->condition('created', strtotime($to), '<=');
    $pre_guercif_count->condition('field_prefecture',"Guercif" );
    $pre_guercif_count->condition('langcode', $lang_code);
    $guercif_count = $pre_guercif_count->count()->execute();



    return array(
        '#theme' => 'theme_dashbord_preinstruction',
        '#count_livre' => $count_livre,
        '#nador_count' => $nador_count,
        '#driouch_count' => $driouch_count,
        '#guercif_count' => $guercif_count,

        '#from' => $from,
        '#to' => $to,
        '#cur_lang' => $lang_code,
        '#average_livre' => $average_livre,
        '#attached' => array(
                  'library' => array(
                   'dardev_dashbord/dash-hp'
                          )
                )
    );

}




    }

   