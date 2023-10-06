<?php

namespace Drupal\dardev_appel_offre\Helper;
use Drupal\Core\Database\Database;

Class Helper{
    
  const SETTINGS = 'dardev_appel.settings';
  
  public static function checkEmail($email){
    $conn = Database::getConnection();
    $record = array();
    $query = $conn->select('dardev_appel_offre_emails', 'm')
        ->condition('email', $email)
        ->fields('m');
    return $query->execute()->fetchAssoc();
  }

  public static function listEmails($niids){
    //kint($niids);
    $query = \Drupal::database()->select('dardev_appel_offre_emails', 'm');//->condition('id_offre',$niids)->fields('m');
    $query->condition('id_offre',$niids);
    $query->fields('m', ['id', 'email']);
    //$nids = $query->execute()->fetchAssoc();
    $nids = $query->execute()->fetchAll();
    //kint($nids);
    //die;
    return $nids;
  }

}
