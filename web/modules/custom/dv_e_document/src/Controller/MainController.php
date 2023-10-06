<?php
/**
 * @file
 * Contains \Drupal\dv_e_document\Controller\MainController.
 */

namespace Drupal\dv_e_document\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Site\Settings;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\user\Entity\User;


class MainController extends ControllerBase
{
    /**
     * récupérer la liste des offres
     * @param $limit
     * @return \Drupal\Core\Entity\EntityInterface[]|static[]
     */
    public function getMailsByRoleAdmin()
    {
        
$ids1 = \Drupal::entityQuery('user')
->condition('status', 1)
->condition('roles', 'administrator')
->execute();
$users = User::loadMultiple($ids1);

	return $users;
//kint($users->get('mail')->value);
}
    public function getMailsByRoleTechVer()
    {
        
$query = \Drupal::entityQuery('user')
->condition('status', 1)
->condition('roles', 'technicien')
->execute();
//$group = $query->orConditionGroup()
//  ->condition('roles', 'administrator')
 // ->condition('roles', 'technicien');
//$tids = $query->condition($group)->execute();
$users = User::loadMultiple($query);

	return $users;
//kint($users->get('mail')->value);
}
    public function getMailsByRoleCci() {
        
$query = \Drupal::entityQuery('user')
->condition('status', 1)
->condition('roles', 'cci')
->execute();

$users = User::loadMultiple($query);

	return $users;
}



    }

   