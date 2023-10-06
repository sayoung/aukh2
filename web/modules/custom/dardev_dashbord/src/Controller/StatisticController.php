<?php
/**
 * @file
 * Contains \Drupal\dardev_dashbord\Controller\EnoteController.
 */

namespace Drupal\dardev_dashbord\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dardev_dashbord\Form\Statistic;
use Drupal\dardev_dashbord\Form\StatisticRokhas;
use Drupal\dardev_dashbord\UtilsProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StatisticController extends ControllerBase
{

  protected $utilsProvider;

  public function __construct(UtilsProvider $utilsProvider)
  {
    $this->utilsProvider = $utilsProvider;
  }

  public static function create(ContainerInterface $container)
  {
    return new static (
      $container->get('dardev_dashbord.utils_provider')
    );
  }

  public function buildForm()
  {
    // Build the form using the form class.
    $form = $this->formBuilder()->getForm(Statistic::class);

    $date1 = \Drupal::request()->request->get('date_1') ? \Drupal::request()->request->get('date_1') : NULL;
    $date2 = \Drupal::request()->request->get('date_2') ? \Drupal::request()->request->get('date_2') : NULL;
    $type = \Drupal::request()->request->get('type') ? \Drupal::request()->request->get('type') : NULL;

    $count = 0;
    $dataAvg = [];
    if ($date1 && $date2 && $type) {
      $dataAvg = $this->utilsProvider->getAvg($date1, $date2, $type, 'node');
    }
    return [
      '#theme' => 'theme_statistic',
      '#data' => [
        'form' => $form,
        'date1' => $date1,
        'date2' => $date2,
        'type' => $type,
        'dataform' =>'enote',
        'data_avg' => $dataAvg
      ],
      '#attached' => [
        'library' => [
          'dardev_dashbord/dash-hp'
        ],
        'drupalSettings' => [
          'dardev_dashbord' => [
            'datachart' => $dataAvg
          ]
        ]
      ]
    ];
  }

  public function buildFormRokhas()
  {
    // Build the form using the form class.
    $form = $this->formBuilder()->getForm(StatisticRokhas::class);

    $date1 = \Drupal::request()->request->get('date_1') ? \Drupal::request()->request->get('date_1') : NULL;
    $date2 = \Drupal::request()->request->get('date_2') ? \Drupal::request()->request->get('date_2') : NULL;
    $type = \Drupal::request()->request->get('type') ? \Drupal::request()->request->get('type') : NULL;

    $count = 0;
    $dataAvg = [];
    if ($date1 && $date2 && $type) {
      $dataAvg = $this->utilsProvider->getAvg($date1, $date2, $type, 'commerce_product');
    }

    return [
      '#theme' => 'theme_statistic',
      '#data' => [
        'form' => $form,
        'date1' => $date1,
        'date2' => $date2,
        'type' => $type,
        'dataform' => 'rokhas',
        'data_avg' => $dataAvg
      ],
      '#attached' => [
        'library' => [
          'dardev_dashbord/dash-hp'
        ],
        'drupalSettings' => [
          'dardev_dashbord' => [
            'datachart' => $dataAvg
          ]
        ]
      ]
    ];
  }
}

