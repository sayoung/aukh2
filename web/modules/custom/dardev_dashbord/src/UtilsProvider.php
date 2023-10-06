<?php

namespace Drupal\dardev_dashbord;

use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UtilsProvider
 * @package Drupal\dardev_dashbord\Services
 */
class UtilsProvider
{


  /**
   * @return \Drupal\Component\Render\MarkupInterface|string
   */
  public function getData($date1, $date2, $type)
  {
    $startDateTime = new DrupalDateTime($date1);
    $endDateTime = new DrupalDateTime($date2);
    $ids1 = \Drupal::entityQuery('node')
      ->condition('type', 'note')
      ->condition('created', [
        $startDateTime->format('U'),
        $endDateTime->format('U'),
      ], 'BETWEEN')
      ->condition('moderation_state', $type);
    $nids = $ids1->execute();

    // Define the CSV file path.
    $file_path = 'public://node_export.csv';

    // Create the directory if it doesn't exist.
    $fileSystem = \Drupal::service('file_system');
    // Create the directory if it doesn't exist.
    $directory = $fileSystem->dirname($file_path);
    $fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    // Get the absolute file path.
    $absolute_file_path = \Drupal::service('file_system')->realpath($file_path);

    // Open the CSV file for writing.
    $handle = fopen($absolute_file_path, 'w');

    // Check if the file was opened successfully.
    if ($handle === false) {
      // Handle the error here, e.g., log the error message or display a user-friendly message.
      \Drupal::logger('my_module')->error('Failed to open the file: @file_path', ['@file_path' => $absolute_file_path]);
      return NULL;
    }

    $dataHeader = [];
    if ($type === 'Dispatcheur') {
      $dataHeader = ['Titre', 'Commune', 'Reference', 'N° commande', 'Date de réception', 'Date de vérification', 'Date comptabilite', 'Date traitement', 'Date vérification 1', 'Date Approbation', 'Date Signature', 'Date d\'envoie', 'temps d\'attente total (heures)'];
    } elseif ($type === 'Archived') {
      $dataHeader = ['Titre', 'Commune', 'Reference', 'N° commande', 'Date de vérification', 'Date annulation', 'Date archive', 'temps d\'attente total (heures)'];

    }
    // Write the CSV headers.
    fputcsv($handle, $dataHeader);
    $nodes = Node::loadMultiple($nids);
    $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
    foreach ($nodes as $node) {
      $revision_ids = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->revisionIds($node);
      $latest_revision_created = 0;
      $latest_revision_id = NULL;
      $tmp = [];
      $title = $node->getTitle();
      $com = $node->get('field_commune')->value;
      $ref = $node->get('field_references_foncieres')->value;
      $n_cmd = $node->get('field_n_command')->value;

      foreach ($revision_ids as $revision_id) {
        $revision = $nodeStorage->loadRevision($revision_id);
        $created_time = $revision->getRevisionCreationTime();
        $moderationState = $revision->get('moderation_state')->target_id;
        $first = TRUE;
        if ($type === 'Dispatcheur') {
          switch ($moderationState) {
            case 'checking':
              if ($created_time > $latest_revision_created) {
                $latest_revision_created = $created_time;
                $tmp['checking_date'] = $latest_revision_created;
                $tmp['check'] = $moderationState;
              }
              break;

            case 'comptabilite':
              $tmp['comptabilite_date'] = $created_time;
              $tmp['comptabilite'] = $moderationState;
              break;

            case 'traitement':
              $tmp['traitement_date'] = $created_time;
              $tmp['traitement'] = $moderationState;
              break;

            case 'validation':
              $tmp['validation_date'] = $created_time;
              $tmp['validation'] = $moderationState;
              break;

            case 'approbation':
              $tmp['approbation_date'] = $created_time;
              $tmp['approbation'] = $moderationState;
              break;

            case 'signature':
              $tmp['signature_date'] = $created_time;
              $tmp['signature'] = $moderationState;
              break;

            case 'traitee':
              $tmp['traitee_date'] = $created_time;
              $tmp['traitee'] = $moderationState;
              break;

            case 'dispatcheur':
              if ($first) {
                $first = FALSE;
                $tmp['dispatcheur_date'] = $created_time;
                $tmp['dispatcheur'] = $moderationState;
                break;
              }
              break;
          }
        } elseif ($type === 'Archived') {
          switch ($moderationState) {
            case 'checking':
              if ($created_time > $latest_revision_created) {
                $latest_revision_created = $created_time;
                $tmp['checking_date'] = $latest_revision_created;
                $tmp['check'] = $moderationState;
              }
              break;

            case 'annulation':
              $tmp['annulation_date'] = $created_time;
              $tmp['annulation'] = $moderationState;
              break;

            case 'archived':
              $tmp['archived_date'] = $created_time;
              $tmp['archived'] = $moderationState;
              break;
          }
        }


        /*$created_date = DrupalDateTime::createFromTimestamp($created_time)
          ->format('Y-m-d H:i:s');
        echo 'Id: '. $revision->id() .' etat: <strong>'. $revision->get('moderation_state')->target_id. '</strong> crée le: '. $created_date;
        dump('----------------------------');*/
      }
      $totalTime = NULL;
      if ($tmp['checking_date'] && (isset($tmp['dispatcheur_date']) || isset($tmp['archived_date']))) {
        $totalTime = $this->calculateWaitingTime($tmp, $type);
        //dump('Total: ', $totalTime);
      }
      $dataBody = [];
      if ($type === 'Dispatcheur') {
        $dataBody = [
          $title,
          $com,
          $ref,
          $n_cmd,
          $tmp['checking_date'] ? DrupalDateTime::createFromTimestamp($tmp['checking_date'])
            ->format('Y-m-d H:i:s') : '-',
          $tmp['comptabilite_date'] ? DrupalDateTime::createFromTimestamp($tmp['comptabilite_date'])
            ->format('Y-m-d H:i:s') : '-',
          $tmp['traitement_date'] ? DrupalDateTime::createFromTimestamp($tmp['traitement_date'])
            ->format('Y-m-d H:i:s') : '-',
          $tmp['validation_date'] ? DrupalDateTime::createFromTimestamp($tmp['validation_date'])
            ->format('Y-m-d H:i:s') : '-',
          $tmp['approbation_date'] ? DrupalDateTime::createFromTimestamp($tmp['approbation_date'])
            ->format('Y-m-d H:i:s') : '-',
          $tmp['signature_date'] ? DrupalDateTime::createFromTimestamp($tmp['signature_date'])
            ->format('Y-m-d H:i:s') : '-',
          $tmp['traitee_date'] ? DrupalDateTime::createFromTimestamp($tmp['traitee_date'])
            ->format('Y-m-d H:i:s') : '-',
          $tmp['dispatcheur_date'] ? DrupalDateTime::createFromTimestamp($tmp['dispatcheur_date'])
            ->format('Y-m-d H:i:s') : '-',
          $totalTime
        ];
      } elseif ($type === 'Archived') {
        $dataBody = [
          $title,
          $com,
          $ref,
          $n_cmd,
          $tmp['checking_date'] ? DrupalDateTime::createFromTimestamp($tmp['checking_date'])
            ->format('Y-m-d H:i:s') : '-',
          $tmp['annulation_date'] ? DrupalDateTime::createFromTimestamp($tmp['annulation_date'])
            ->format('Y-m-d H:i:s') : '-',
          $tmp['archived_date'] ? DrupalDateTime::createFromTimestamp($tmp['archived_date'])
            ->format('Y-m-d H:i:s') : '-',
          $totalTime
        ];
      }
      fputcsv($handle, $dataBody);
    }
    // Close the CSV file.
    fclose($handle);
    // Create a file response.
    $response = new Response();
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="node_export.csv"');
    $response->setContent(file_get_contents($file_path));

    // Return the file response.
    return $response;
  }

  private function calculateWaitingTime($dates, $type)
  {
    $checking_timestamp = $dates['checking_date'];
    $dispatcheur_timestamp = $type === 'Dispatcheur' ? $dates['dispatcheur_date'] : $dates['archived_date'];


    $checking_datetime = new DrupalDateTime();
    $checking_datetime->setTimestamp($checking_timestamp);

    $dispatcheur_datetime = new DrupalDateTime();
    $dispatcheur_datetime->setTimestamp($dispatcheur_timestamp);

// Calculer la différence en heures entre les deux dates.
    $interval = $dispatcheur_datetime->diff($checking_datetime);
    $hours = $interval->days * 24 + $interval->h;

// Exclure les jours fériés et les weekends.
    $current_date = clone $checking_datetime;
    $excluded_hours = 0;
    $config = \Drupal::configFactory()->getEditable('holidays.settings');
    $holidays_count = $config->get('holidays_count');
    $jours_feries = [];
    $tmp = [];
    for ($j = 0; $j < $holidays_count; $j++) {
      $jf_datetime = new DrupalDateTime();
      $jf_datetime->setTimestamp(strtotime($config->get('holidays_fieldset.day_' . $j)))->setTime(9, 00, 00);
      $jours_feries[] = $jf_datetime->getTimestamp();

    }
    //dump($jours_feries);
    while ($current_date <= $dispatcheur_datetime) {
      $current_weekday = $current_date->format('N'); // 1 (lundi) à 7 (dimanche)

      if ($current_date->format('H:i') > '16:30') {
        $current_date->modify('+1 day')->setTime(9, 0, 0);
        continue;
      }


      // Exclure les weekends (samedi et dimanche).
      if ($current_weekday >= 6) {
        $current_date->modify('+1 day')->setTime(9, 0, 0);
        $excluded_hours += 24; // Exclure 24 heures par jour de weekend.
        continue;
      }

      // Exclure les jours fériés.
      $current_date_timestamp = $current_date->getTimestamp();
      if (in_array($current_date_timestamp, $jours_feries)) {
        $current_date->modify('+1 day')->setTime(9, 0, 0);
        continue;
        $excluded_hours += 24; // Exclure 24 heures pour chaque jour férié.
      }

      $time_difference = 0;
      if ($dispatcheur_datetime->format('N') == $current_date->format('N')) {
        $time_difference = $current_date->diff($dispatcheur_datetime);
      } else {
        $target_time = new DrupalDateTime('16:30');
        $time_difference = $current_date->diff($target_time);
      }

      /*echo '<pre>';
      var_dump($current_date->getTimestamp());*/


      $tmp[] = $time_difference;
      /*$hours = $time_difference->h;
      $minutes = $time_difference->i;*/

      //dump($time_difference);
// Output the result
      //echo "Time difference: $hours hours and $minutes minutes";

      $current_date->modify('+1 day')->setTime(9, 0, 0);
    }


    $nbHours = 0;
    $nbMinutes = 0;
    if (count($tmp) >= 1) {
      foreach ($tmp as $elem) {
        $nbHours += $elem->h;
        $nbMinutes += $elem->i;
      }
    }
    //die;

// Calculer le nombre total d'heures d'attente en excluant les jours non ouvrables.
    $total_working_hours = $hours - $excluded_hours;
    /*if ($total_working_hours < 0) {
      $total_working_hours = 0; // S'assurer que le résultat final n'est pas négatif.
    }*/

// Afficher le résultat.
    /*print('Nombre d\'heures d\'attente (en excluant les jours non ouvrables) : ' . $total_working_hours);
    die;*/
    // return $nbHours. ':' . $nbMinutes;
    return $nbHours;
  }

  public function getAvg($date1, $date2, $type, $entityType)
  {
    $nids = $this->getTotalData($date1, $date2, $type, $entityType);
    $count = count($nids);
    $nodes = $entityType == 'node' ?Node::loadMultiple($nids) : Product::loadMultiple($nids);
    $data = $this->calculateAverageByCity($nodes);

    $startDateTime = new DrupalDateTime($date1);
    $endDateTime = new DrupalDateTime($date2);
    $days_difference = ceil(abs($endDateTime->format('U') - $startDateTime->format('U')) / (60 * 60 * 24));
    if ($days_difference > 0) {
      $average_published_nodes = $count / $days_difference;
    } else {
      $average_published_nodes = 0;
    }
    return [
      'count' => $count,
      'avg' => round($average_published_nodes),
      'dataCities' => $data
    ];
  }

  public function getTotalData($date1, $date2, $type, $entityType)
  {
    $startDateTime = new DrupalDateTime($date1);
    $endDateTime = new DrupalDateTime($date2);

    $ids1 = \Drupal::entityQuery($entityType);
    if ($entityType == 'node') {
      $ids1->condition('type', 'note')
        ->condition('moderation_state', $type);
    } elseif ($entityType == 'commerce_product') {
      $ids1->condition('type', 'instruction')
        ->condition($type, '1');
    }

    $ids1->condition('created', [
      $startDateTime->format('U'),
      $endDateTime->format('U'),
    ], 'BETWEEN');

    return $ids1->execute();
  }

  private function calculateAverageByCity($nodes)
  {
    $data = [];

    foreach ($nodes as $node) {
      $city = $node->get('field_commune')->value;

      if (!isset($data[$city])) {
        $data[$city] = 1;
      } else {
        $data[$city]++;
      }
    }
    return $data;
  }

  public function getProductData($date1, $date2, $type)
  {
    $startDateTime = new DrupalDateTime($date1);
    $endDateTime = new DrupalDateTime($date2);
    $ids1 = \Drupal::entityQuery('commerce_product')
      ->condition('type', 'instruction')
      ->condition('created', [
        $startDateTime->format('U'),
        $endDateTime->format('U'),
      ], 'BETWEEN')
      ->condition($type, '1');
    $nids = $ids1->execute();
    $nodes = Product::loadMultiple($nids);
    $file_path = 'public://product_export.csv';

    // Create the directory if it doesn't exist.
    $fileSystem = \Drupal::service('file_system');
    // Create the directory if it doesn't exist.
    $directory = $fileSystem->dirname($file_path);
    $fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    // Get the absolute file path.
    $absolute_file_path = \Drupal::service('file_system')->realpath($file_path);

    // Open the CSV file for writing.
    $handle = fopen($absolute_file_path, 'w');

    // Check if the file was opened successfully.
    if ($handle === false) {
      // Handle the error here, e.g., log the error message or display a user-friendly message.
      \Drupal::logger('my_module')->error('Failed to open the file: @file_path', ['@file_path' => $absolute_file_path]);
      return NULL;
    }

    $dataHeader = [];
    if ($type === 'field_valider') {
      $dataHeader = ['Titre', 'Prix', 'Titre foncier', 'Nom du Pétitionnaire', 'Commune', 'N° de dossier', 'n° commande', 'Métrage du projet', 'is payed', 'date de paiement', 'valider', 'date de validation', 'validation par', 'date facturation', 'facturation par', 'Valider (Rokhas)', 'Date et Heure de Validation de Rokhas', 'Validation Rokhas par:'];
    } elseif ($type === 'field_annulation_du_dossier') {
      $dataHeader = ['Titre', 'Prix', 'Titre foncier', 'Nom du Pétitionnaire', 'Commune', 'N° de dossier', 'n° commande', 'Métrage du projet', 'annulation du dossier', 'Data annulation', 'annulation par'];

    }
    // Write the CSV headers.
    fputcsv($handle, $dataHeader);
    foreach ($nodes as $node) {
      $dataBody = [];
      $dataBody[] = $node->getTitle();
      $variation = ProductVariation::load($node->get('variations')->target_id);
      $dataBody[] = $variation->get('price')->number . ' ' . $variation->get('price')->currency_code;
      $dataBody[] = $node->get('field_titre_foncier')->value;
      $dataBody[] = $node->get('field_nom_du_petitionnaire')->value;
      $dataBody[] = $node->get('field_commune')->value;
      $dataBody[] = $node->get('field_ndeg_de_dossier')->value;
      $dataBody[] = $node->get('field_n_command')->value;
      $dataBody[] = $node->get('field_metrage_du_projet')->value;


      if ($type === 'field_valider') {
        $dataBody[] = $node->get('field_is_payed')->value == 1 ? 'oui' : '';
        $formattedDate = date('d-m-Y H:i:s', strtotime($node->get('field_date_de_paiement')->value));
        $dataBody[] = $node->get('field_is_payed')->value == 1 ? $formattedDate : '';
        $dataBody[] = 'oui';
        $dataBody[] = $node->get('field_date_de_validation')->value;
        $dataBody[] = $node->get('field_validation_par')->value;
        $dataBody[] = $node->get('field_date_facturation')->value;
        $dataBody[] = $node->get('field_facturation_par')->value;
        $dataBody[] = $node->get('field_valide_au_niveau_de_rokhas')->value == 1 ? 'oui' : 'non';
        $dataBody[] = $node->get('field_date_rokhas')->value;
        $dataBody[] = $node->get('field_valide_rokhas_par')->value;

      } elseif ($type === 'field_annulation_du_dossier') {
        $dataBody[] = 'oui';
        $dataBody[] = $node->get('field_data_annulation')->value;
        $dataBody[] = $node->get('field_annulation_par')->value;
      }
      fputcsv($handle, $dataBody);
    }

    // Close the CSV file.
    fclose($handle);
    // Create a file response.
    $response = new Response();
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="product_export.csv"');
    $response->setContent(file_get_contents($file_path));

    // Return the file response.
    return $response;
  }


}
