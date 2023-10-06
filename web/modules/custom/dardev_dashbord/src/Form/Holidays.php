<?php

namespace Drupal\dardev_dashbord\Form;

use DateTime;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Holidays
 *
 * @package Drupal\dardev_dashbord\Form
 */
class Holidays extends ConfigFormBase {

  /**
   * @return string
   */
  public function getFormId() {
    return 'holidays';
  }

  /**
   * @return array
   */
  protected function getEditableConfigNames() {
    return [
      'holidays.settings',
    ];
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    //////////////////////

    //////////////////////
    $config = $this->config('holidays.settings');

    $form['holidays_fieldset'] = [
      '#type' => 'details',
      '#title' => $this->t('Holidays (Closed Days)'),
      '#prefix' => '<div id="holidays-fieldset-wrapper">',
      '#suffix' => '</div>',
      '#open' => TRUE,
    ];

    $holidays_count = $config->get('holidays_count');


    if (is_null($holidays_count)) {
      $holidays_count = 1;
    }

    $form['holidays_count'] = [
      '#type' => 'hidden',
      '#value' => $holidays_count,
    ];

    for ($j = 0; $j < $holidays_count; $j++) {
      $form['holidays_fieldset']['day_' . $j] = [
        '#type' => 'date',
        '#title' => t('Day'),
        '#required' => FALSE,
        '#default_value' => $config->get(
          'holidays_fieldset.day_' . $j
        ),
      ];

      $form['holidays_fieldset']['title_' . $j] = [
        '#type' => 'textfield',
        '#title' => t('Title'),
        '#required' => FALSE,
        '#default_value' => $config->get(
          'holidays_fieldset.title_' . $j
        ),
      ];
    }

    $form['holidays_fieldset']['actions']['add_day'] = [
      '#type' => 'submit',
      '#value' => t('Add one more'),
      '#submit' => ['::addOneDay'],
      '#name' => 'add_day',
      '#ajax' => [
        'callback' => '::addMoreDayCallback',
        'wrapper' => 'holidays-fieldset-wrapper',
      ],
    ];

    if ($holidays_count > 1) {
      $form['holidays_fieldset']['actions']['remove_day'] = [
        '#type' => 'submit',
        '#value' => t('Remove one'),
        '#submit' => ['::removeDayCallback'],
        '#name' => 'remove_day',
        '#ajax' => [
          'callback' => '::addMoreDayCallback',
          'wrapper' => 'holidays-fieldset-wrapper',
        ],
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {


  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $langs = \Drupal::languageManager()->getLanguages();
    $configSaver = $this->configFactory->getEditable('holidays.settings');


    $holidays_count = $form_state->getValue('holidays_count');

    $configSaver->set('holidays_count', $holidays_count);
    $configSaver->clear('holidays_fieldset');

    for ($i = 0; $i < $holidays_count; $i++) {
      $configSaver->set(
        'holidays_fieldset.day_' . $i,
        $form_state->getValue(
          ['day_' . $i]
        )
      );
      $configSaver->set(
        'holidays_fieldset.title_' . $i,
        $form_state->getValue(
          ['title_' . $i]
        )
      );
    }

    $configSaver->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function addMoreDayCallback(array &$form, FormStateInterface $form_state) {
    return $form['holidays_fieldset'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOneDay(array &$form, FormStateInterface $form_state) {
    $configSaver = $this->configFactory->getEditable('holidays.settings');
    $patterns_count = $this->config('holidays.settings')->get('holidays_count');
    $add_button = $patterns_count + 1;
    $configSaver->set('holidays_count', $add_button);
    $configSaver->save();

    $form_state->set(
      ['holidays_fieldset', 'holidays_count'],
      $add_button
    );
    $form_state->setRebuild();
  }

  /**
   * Submit handler for the "remove one" button.
   *
   * Decrements the max counter and causes a form rebuild.
   */
  public function removeDayCallback(array &$form, FormStateInterface $form_state) {
    $configSaver = $this->configFactory->getEditable('holidays.settings');
    $patterns_count = $this->config('holidays.settings')->get('holidays_count');
    $remove_button = $patterns_count - 1;
    $configSaver->set('holidays_count', $remove_button);
    $configSaver->save();
    $form_state->set(
      ['holidays_fieldset', 'holidays_count'],
      $remove_button
    );
    $form_state->setRebuild();
  }
}
