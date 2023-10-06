<?php

namespace Drupal\dardev_dashbord\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dardev_dashbord\UtilsProvider;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StatisticRokhas
 *
 * @package Drupal\dardev_dashbord\Form
 */
class StatisticRokhas extends ConfigFormBase {

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
    return 'statistic_rokhas';
  }

  /**
   * @return array
   */
  protected function getEditableConfigNames() {
    return [
      'statistic.settings',
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
        'field_valider' => $this->t('Validé'),
        'field_annulation_du_dossier' => $this->t('Annulé'),
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
    if (!in_array($value, ['field_valider', 'field_annulation_du_dossier'])) {
      $form_state->setError($element, $this->t('Invalid value selected for the Type field.'));
    }
  }


  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);


    // Rebuild the form to display the new element.
    $form_state->setRebuild(TRUE);


  }
}
