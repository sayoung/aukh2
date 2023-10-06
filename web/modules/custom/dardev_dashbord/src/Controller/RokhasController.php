<?php
/**
 * @file
 * Contains \Drupal\dardev_dashbord\Controller\EnoteController.
 */

namespace Drupal\dardev_dashbord\Controller;
use Drupal\commerce_product\Entity\ProductInterface;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\commerce_product\Entity\Product;
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

class RokhasController extends ControllerBase
{
    /**
     * récupérer la liste des offres
     * @param $limit
     * @return \Drupal\Core\Entity\EntityInterface[]|static[]
     */


    public function getRokhasTraitee(Request $request) {




        $from = $request->request->get('from');
        $to = $request->request->get('to');
        if (empty($from)) {
            $from = "01-01-".date("Y") ;
        } else {
            echo  $from;
            echo ("<br>");
            echo date("Y");
        }
        if (empty($to)) {
            $to = "-1 day";
        } else {
            echo  $to;
        }
        
        $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $pre_count_livre = \Drupal::entityQuery('commerce_product');
        $pre_count_livre->condition('type', 'instruction');
        $pre_count_livre->condition('created', strtotime($from), '>=');
        $pre_count_livre->condition('created', strtotime($to), '<=');
        $pre_count_livre->condition('field_is_payed', 1 );
        $pre_count_livre->condition('langcode', $lang_code);
        $count_livre = $pre_count_livre->count()->execute();



    

    
        $pre_count_annuler = \Drupal::entityQuery('commerce_product');
        $pre_count_annuler->condition('type', 'instruction');
        $pre_count_annuler->condition('created', strtotime($from), '>=');
        $pre_count_annuler->condition('created', strtotime($to), '<=');
        $pre_count_annuler->condition('field_annulation_du_dossier',1 );
        $pre_count_annuler->condition('langcode', $lang_code);
        $count_annuler = $pre_count_annuler->count()->execute();
    
        $pre_nador_count = \Drupal::entityQuery('commerce_product');
        $pre_nador_count->condition('type', 'instruction');
        $pre_nador_count->condition('created', strtotime($from), '>=');
        $pre_nador_count->condition('created', strtotime($to), '<=');
        $pre_nador_count->condition('field_is_payed',1 );
        $pre_nador_count->condition('field_prefecture',"Nador" );
        $pre_nador_count->condition('langcode', $lang_code);
        $nador_count = $pre_nador_count->count()->execute();
    
        $pre_driouch_count = \Drupal::entityQuery('commerce_product');
        $pre_driouch_count->condition('type', 'instruction');
        $pre_driouch_count->condition('created', strtotime($from), '>=');
        $pre_driouch_count->condition('created', strtotime($to), '<=');
        $pre_driouch_count->condition('field_is_payed',1 );
        $pre_driouch_count->condition('field_prefecture',"Driouch" );
        $pre_driouch_count->condition('langcode', $lang_code);
        $driouch_count = $pre_driouch_count->count()->execute();
    
    
        $pre_guercif_count = \Drupal::entityQuery('commerce_product');
        $pre_guercif_count->condition('type', 'instruction');
        $pre_guercif_count->condition('created', strtotime($from), '>=');
        $pre_guercif_count->condition('created', strtotime($to), '<=');
        $pre_guercif_count->condition('field_prefecture',"Guercif" );
        $pre_guercif_count->condition('field_is_payed',1 );
        $pre_guercif_count->condition('langcode', $lang_code);
        $guercif_count = $pre_guercif_count->count()->execute();
    
        $pre_nador_count_a = \Drupal::entityQuery('commerce_product');
        $pre_nador_count_a->condition('type', 'instruction');
        $pre_nador_count_a->condition('created', strtotime($from), '>=');
        $pre_nador_count_a->condition('created', strtotime($to), '<=');
        $pre_nador_count_a->condition('field_prefecture',"Nador" );
        $pre_nador_count_a->condition('field_annulation_du_dossier',1 );
        $pre_nador_count_a->condition('langcode', $lang_code);
        $nador_count_a = $pre_nador_count_a->count()->execute();
    
        $pre_driouch_count_a = \Drupal::entityQuery('commerce_product');
        $pre_driouch_count_a->condition('type', 'instruction');
        $pre_driouch_count_a->condition('created', strtotime($from), '>=');
        $pre_driouch_count_a->condition('created', strtotime($to), '<=');
        $pre_driouch_count_a->condition('field_annulation_du_dossier',1 );
        $pre_driouch_count_a->condition('field_prefecture',"Driouch" );
        $pre_driouch_count_a->condition('langcode', $lang_code);
        $driouch_count_a = $pre_driouch_count_a->count()->execute();
    
    
        $pre_guercif_count_a = \Drupal::entityQuery('commerce_product');
        $pre_guercif_count_a->condition('type', 'instruction');
        $pre_guercif_count_a->condition('created', strtotime($from), '>=');
        $pre_guercif_count_a->condition('created', strtotime($to), '<=');
        $pre_guercif_count_a->condition('field_prefecture',"Guercif" );
        $pre_guercif_count_a->condition('field_annulation_du_dossier',1 );
        $pre_guercif_count_a->condition('langcode', $lang_code);
        $guercif_count_a = $pre_guercif_count_a->count()->execute();
    

        $pre_sum_livre = \Drupal::entityQueryAggregate('commerce_product');
        $pre_sum_livre->condition('type', 'instruction');
        $pre_sum_livre->condition('created', strtotime($from), '>=');
        $pre_sum_livre->condition('created', strtotime($to), '<=');
        $pre_sum_livre->condition('field_is_payed', 1 );
        $pre_sum_livre->condition('langcode', $lang_code);
        $pre_sum_livre->aggregate('field_metrage_du_projet' ,'sum');
        $sum_livre = $pre_sum_livre->execute();
        $sum = $sum_livre['0']['field_metrage_du_projet_sum'];
        $total_livre = $sum * 3.6;

        $pre_sum_annuler = \Drupal::entityQueryAggregate('commerce_product');
        $pre_sum_annuler->condition('type', 'instruction');
        $pre_sum_annuler->condition('created', strtotime($from), '>=');
        $pre_sum_annuler->condition('created', strtotime($to), '<=');
        $pre_sum_annuler->condition('field_annulation_du_dossier',1 );
        $pre_sum_annuler->condition('langcode', $lang_code);
        $pre_sum_annuler->aggregate('field_metrage_du_projet' ,'sum');
        $sum_annuler = $pre_sum_annuler->execute();
        $sum_a = $sum_annuler['0']['field_metrage_du_projet_sum'];
        $total_annuler = $sum_a * 3.6;
        
        return array(
            '#theme' => 'theme_dashbord_rokhas',
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
            '#total_livre' => $total_livre,
            '#total_annuler' => $total_annuler,
            '#attached' => array(
                      'library' => array(
                       'dardev_dashbord/dash-hp'
                              )
                    )
        );
    
    }
}    