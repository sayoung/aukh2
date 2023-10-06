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
 * Class Statistic
 *
 * @package Drupal\dardev_dashbord\Form
 */
class Statistic extends ConfigFormBase {

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
    return 'statistic';
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


    /*$database = \Drupal::database();
    $entityType = 'commerce_product';
    $fieldName = 'field_date_de_paiement';
    $table = $entityType . '__' . $fieldName;
    $currentRows = NULL;
    $newFieldsList = [];
    $fieldStorage = FieldStorageConfig::loadByName($entityType, $fieldName);

    if (is_null($fieldStorage)) {
      die('makaynch');
    }
    // Get all current data from DB.
    if ($database->schema()->tableExists($table)) {
      // The table data to restore after the update is completed.
      $currentRows = $database->select($table, 'n')
        ->fields('n')
        ->execute()
        ->fetchAll();
    }
    // Use existing field config for new field.
    foreach ($fieldStorage->getBundles() as $bundle => $label) {
      $field = FieldConfig::loadByName($entityType, $bundle, $fieldName);
      $newField = $field->toArray();
      $newField['field_type'] = 'datetime';
      $newField['settings'] = [];
      $newFieldsList[] = $newField;
    }

    // Deleting field storage which will also delete bundles(fields).
    $newFieldStorage = $fieldStorage->toArray();
    $newFieldStorage['type'] = 'datetime';
    $newFieldStorage['settings'] = [];

    $fieldStorage->delete();

    // Purge field data now to allow new field and field_storage with same name
    // to be created.
    field_purge_batch(40);

    // Create new field storage.
    $newFieldStorage = FieldStorageConfig::create($newFieldStorage);
    $newFieldStorage->save();

    // Create new fields.
    foreach ($newFieldsList as $nfield) {
      $nfieldConfig = FieldConfig::create($nfield);
      $nfieldConfig->save();
    }

    // Restore existing data in new table.
    if (!is_null($currentRows)) {
      foreach ($currentRows as $row) {
        $database->insert($table)
          ->fields((array) $row)
          ->execute();
      }
    }

    die('lol');*/
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
    parent::submitForm($form, $form_state);


    // Rebuild the form to display the new element.
    $form_state->setRebuild(TRUE);


  }
}
