<?php
/**
 * @file
 * Contains \Drupal\dardev_dashbord\Controller\EnoteController.
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
use Drupal\dardev_dashbord\Controller\getWorkdays;

class EnoteController extends ControllerBase
{
  /**
   * récupérer la liste des offres
   * @param $limit
   * @return \Drupal\Core\Entity\EntityInterface[]|static[]
   */
  public function getNotesA()
  {

    $weekAgo = new DrupalDateTime('-300 days');
    $weekAgo = $weekAgo->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    $weekplus = new DrupalDateTime('-3 days');
    $weekplus = $weekplus->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $ids1 = \Drupal::entityQuery('node')
      ->condition('type', 'note')
      ->condition('moderation_state', 'archived')
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


  public function getNotesTraitee(Request $request) {
    $from = $request->request->get('from');
    $to = $request->request->get('to');
    if (empty($from)) {
      $from = "01-01-".date("Y") ;
    } else {

    }
    if (empty($to)) {
      $to = "-1 day";
    } else {
    }

    //$from = "19-11-2020";

    // $to = "19/11/2022";

    //$from = str_replace("/","-","Hello world!");


    //exit;

    $lon = $request->query->get('lon');

    $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $pre_count_livre = \Drupal::entityQuery('node');
    $pre_count_livre->condition('type', 'note');
    $pre_count_livre->condition('moderation_state','to_client');
    $pre_count_livre->condition('created', strtotime($from), '>=');
    $pre_count_livre->condition('created', strtotime($to), '<=');
    $pre_count_livre->condition('langcode', $lang_code);
    $count_livre = $pre_count_livre->count()->execute();


    $id_livre = \Drupal::entityQuery('node');
    $id_livre->condition('type', 'note');
    $id_livre->condition('moderation_state','to_client');
    $id_livre->condition('created', strtotime($from), '>=');
    $id_livre->condition('created', strtotime($to), '<=');
    $id_livre->condition('langcode', $lang_code);
    $id_livre->range(0,10);
    $id_livre_result = $id_livre->execute();
    $id_livre_result = Node::loadMultiple($id_livre_result);
    // kint($id_livre_result);
    $id_too = array();
    $controller = new getWorkdays();
    $date_formatter = \Drupal::service('date.formatter');

    foreach($id_livre_result as $note_l){
      $date1 = $note_l->get('created')->value;
      $date2 = $note_l->get('changed')->value;
      $date11 = $date_formatter->format($date1, 'custom', 'Y-m-d');
      $date22 = $date_formatter->format($date2, 'custom', 'Y-m-d');
      //kint($note_l->get('changed')->value);
      // $diff = date_diff( $date1, $date2 );echo "<br>";
      // $time_elapsed= $date2-$date1;
      #convert as you like
      //$minutes=$time_elapsed/60;
      //$hours=$minutes/60;
      #return the value
      $hours = $controller->getWorkdaysFun($date11, $date22, $workSat = FALSE, $patron = NULL);
      $value = $hours;
      $id_too [] = $value;
      //echo($value);

    }
    $a = array_filter($id_too);
    if(empty($a)) {
      $average_livre = "-";
    }else{
      $average_livre = array_sum($a)/count($a);
    }


    $pre_count_annuler = \Drupal::entityQuery('node');
    $pre_count_annuler->condition('type', 'note');
    $pre_count_annuler->condition('moderation_state','archived');
    $pre_count_annuler->condition('created', strtotime($from), '>=');
    $pre_count_annuler->condition('created', strtotime($to), '<=');
    $pre_count_annuler->condition('langcode', $lang_code);
    $count_annuler = $pre_count_annuler->count()->execute();


    $id_annuler = \Drupal::entityQuery('node');
    $id_annuler->condition('type', 'note');
    $id_annuler->condition('moderation_state','archived');
    $id_annuler->condition('created', strtotime($from), '>=');
    $id_annuler->condition('created', strtotime($to), '<=');
    $id_annuler->condition('langcode', $lang_code);
    $id_annuler->range(0,10);
    $id_annuler_result = $id_annuler->execute();
    $id_annuler_result = Node::loadMultiple($id_annuler_result);
    // kint($id_livre_result);
    $id_too_a = array();

    foreach($id_annuler_result as $note_l){
      $date1 = $note_l->get('created')->value;
      $date2 = $note_l->get('changed')->value;
      //kint($note_l->get('changed')->value);
      // $diff = date_diff( $date1, $date2 );echo "<br>";
      $time_elapsed= $date2-$date1;
      #convert as you like
      $minutes=$time_elapsed/60;
      $hours=$minutes/60;
      #return the value
      $value = $hours;
      $id_too_a [] = $value;
      //echo($value);

    }
    $average_annuler_pre = array_filter($id_too_a);
    if(empty($average_annuler_pre)) {
      $average_annuler = "-";
    }else{
      $average_annuler = array_sum($average_annuler_pre)/count($average_annuler_pre);
    }


    $pre_nador_count = \Drupal::entityQuery('node');
    $pre_nador_count->condition('type', 'note');
    $pre_nador_count->condition('moderation_state','to_client');
    $pre_nador_count->condition('created', strtotime($from), '>=');
    $pre_nador_count->condition('created', strtotime($to), '<=');
    $pre_nador_count->condition('field_prefecture',"Nador" );
    $pre_nador_count->condition('langcode', $lang_code);
    $nador_count = $pre_nador_count->count()->execute();

    $pre_driouch_count = \Drupal::entityQuery('node');
    $pre_driouch_count->condition('type', 'note');
    $pre_driouch_count->condition('moderation_state','to_client');
    $pre_driouch_count->condition('created', strtotime($from), '>=');
    $pre_driouch_count->condition('created', strtotime($to), '<=');
    $pre_driouch_count->condition('field_prefecture',"Driouch" );
    $pre_driouch_count->condition('langcode', $lang_code);
    $driouch_count = $pre_driouch_count->count()->execute();


    $pre_guercif_count = \Drupal::entityQuery('node');
    $pre_guercif_count->condition('type', 'note');
    $pre_guercif_count->condition('moderation_state','to_client');
    $pre_guercif_count->condition('created', strtotime($from), '>=');
    $pre_guercif_count->condition('created', strtotime($to), '<=');
    $pre_guercif_count->condition('field_prefecture',"Guercif" );
    $pre_guercif_count->condition('langcode', $lang_code);
    $guercif_count = $pre_guercif_count->count()->execute();

    $pre_nador_count_a = \Drupal::entityQuery('node');
    $pre_nador_count_a->condition('type', 'note');
    $pre_nador_count_a->condition('moderation_state','archived');
    $pre_nador_count_a->condition('created', strtotime($from), '>=');
    $pre_nador_count_a->condition('created', strtotime($to), '<=');
    $pre_nador_count_a->condition('field_prefecture',"Nador" );
    $pre_nador_count_a->condition('langcode', $lang_code);
    $nador_count_a = $pre_nador_count_a->count()->execute();

    $pre_driouch_count_a = \Drupal::entityQuery('node');
    $pre_driouch_count_a->condition('type', 'note');
    $pre_driouch_count_a->condition('moderation_state','archived');
    $pre_driouch_count_a->condition('created', strtotime($from), '>=');
    $pre_driouch_count_a->condition('created', strtotime($to), '<=');
    $pre_driouch_count_a->condition('field_prefecture',"Driouch" );
    $pre_driouch_count_a->condition('langcode', $lang_code);
    $driouch_count_a = $pre_driouch_count_a->count()->execute();


    $pre_guercif_count_a = \Drupal::entityQuery('node');
    $pre_guercif_count_a->condition('type', 'note');
    $pre_guercif_count_a->condition('moderation_state','archived');
    $pre_guercif_count_a->condition('created', strtotime($from), '>=');
    $pre_guercif_count_a->condition('created', strtotime($to), '<=');
    $pre_guercif_count_a->condition('field_prefecture',"Guercif" );
    $pre_guercif_count_a->condition('langcode', $lang_code);
    $guercif_count_a = $pre_guercif_count_a->count()->execute();

    return array(
      '#theme' => 'theme_dashbord',
      '#count_livre' => $count_livre,
      '#count_annuler' => $count_annuler,
      '#nador_count' => $nador_count,
      '#driouch_count' => $driouch_count,
      '#guercif_count' => $guercif_count,
      '#guercif_count_a' => $guercif_count_a,
      '#nador_count_a' => $nador_count_a,
      '#driouch_count_a' => $driouch_count_a,
      '#from' => $from,
      '#to' => $to,
      '#cur_lang' => $lang_code,
      '#average_livre' => $average_livre,
      '#average_annuler' => $average_annuler,
      '#attached' => array(
        'library' => array(
          'dardev_dashbord/dash-hp'
        )
      )
    );

  }


}

