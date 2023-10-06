<?php

namespace Drupal\dardev_dashbord\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dardev_dashbord\UtilsProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Export
 *
 * @package Drupal\dardev_dashbord\Form
 */
class Export extends ConfigFormBase {

  protected $utilsProvider;

  public function __construct(UtilsProvider $utilsProvider) {
    $this->utilsProvider = $utilsProvider;
  }

  public static function create(ContainerInterface $container) {
    return new static (
      $container->get('dardev_dashbord.utils_provider')
    );
  }
  /**
   * @return string
   */
  public function getFormId() {
    return 'export';
  }

  /**
   * @return array
   */
  protected function getEditableConfigNames() {
    return [
      'export.settings',
    ];
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $form['dates_fieldset'] = [
      '#type' => 'details',
      '#title' => $this->t('Dates'),
      '#prefix' => '<div id="holidays-fieldset-wrapper">',
      '#suffix' => '</div>',
      '#open' => TRUE,
    ];


      $form['dates_fieldset']['date_1'] = [
        '#type' => 'date',
        '#title' => t('Date 1'),
        '#required' => FALSE,
        '#default_value' => '2023-01-13'
      ];

    $form['dates_fieldset']['date_2'] = [
      '#type' => 'date',
      '#title' => t('Date 2'),
      '#required' => FALSE,
      '#default_value' => '2023-01-14'
    ];

    $form['dates_fieldset']['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type'),
      '#options' => [
        'Dispatcheur' => $this->t('Dispatcheur'),
        'Archived' => $this->t('Archived'),
      ],
      '#required' => TRUE,
      '#validate' => ['validateType'],
    ];

    return parent::buildForm($form, $form_state);
  }


  /**
   * Custom validation callback for the 'type' field.
   */
  public function validateType(array $element, FormStateInterface $form_state) {
    $value = $form_state->getValue('type');
    if (!in_array($value, ['Dispatcheur', 'Archived'])) {
      $form_state->setError($element, $this->t('Invalid value selected for the Type field.'));
    }
  }


  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $date1 = $form_state->getValue(['date_1']);
    $date2 = $form_state->getValue(['date_2']);
    $type = $form_state->getValue(['type']);


    $resp = $this->utilsProvider->getData($date1, $date2, $type);

    $form_state->setResponse($resp);

    parent::submitForm($form, $form_state);
  }
}
