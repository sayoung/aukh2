<?php

namespace Drupal\dardev_appel_offre\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;
/**
 * Class DisplayTableController.
 *
 * @package Drupal\dardev_appel_offre\Controller
 */
class DisplayTableController extends ControllerBase {


  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'dardev_appel_offre_description',
        '#description' => 'foo',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {

    //create table header
    $header_table = array(
      'id' => t('ID'),
      'email' => t('Email'),
      'id_offre' => t('Appel d\'offre'),
      'offre'=> t('Appel d\'offre'),
    );

//select records from table
    $query = \Drupal::database()->select('dardev_appel_offre_emails', 'm');
      $query->fields('m', ['id','email','id_offre','offre']);
      $query->orderBy('id_offre', 'desc');
      $results = $query->execute()->fetchAll();
        $rows=array();
    foreach($results as $data){
        $delete = Url::fromUserInput('/backoffice/dardev_appel_offre/form/delete/'.$data->id);
        //$edit   = Url::fromUserInput('/backoffice/dardev_appel_offre/form/dardev_appel_offre?num='.$data->id);

      //print the data from table
             $rows[] = array(
                'id' =>$data->id,
                'email' => $data->email,
                'id_offre' => $data->id_offre,
                'offre' => $data->offre,

                Link::fromTextAndUrl('Delete', $delete),
                 //\Drupal::l('Edit', $edit),
            );

    }
    //display data in site
    $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No emails found'),
        ];
//        echo '<pre>';print_r($form['table']);exit;
        return $form;

  }

}
