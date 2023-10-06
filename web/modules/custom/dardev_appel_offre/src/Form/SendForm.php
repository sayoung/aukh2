<?php
namespace Drupal\dardev_appel_offre\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\dardev_appel_offre\Newsletter\Newsletter;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\dardev_appel_offre\Helper\Helper;
/**
 * SendForm class.
 */
class SendForm extends ConfigFormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'newsletter_modal_form_send_form';
  }
  
  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return array( 
       'config.modal_form_send_form',
       Helper::SETTINGS,

    );
  }

  /**
   * {@inheritdoc}
   */

public function buildForm(array $form, FormStateInterface $form_state, $options = NULL) {
    $query = \Drupal::entityQuery('node')
    ->condition('status', 1)
    ->condition('type', 'appel_d_offre')
    ->sort('created' , 'DESC')
    ->range(0, 50);
    $nids = $query->execute();
    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);
  #$nodes = node_load_multiple($nids);
  
  $nodes_array = array();
  foreach ($nodes as $node){
    $nodes_array[$node->id()] = $node->getTitle();
  }
  $form['news'] = array(
    '#type' => 'select',
    '#title' => t('Actualités'),
    '#description' => $this->t('Séléctionnez les actualités à envoyer dans la newslettrer'),
    '#options' =>  $nodes_array,

  );

    $form['#prefix'] = '<div id="modal_send_form">' . Newsletter::newsLetterHTML()."<br><br><br>";
    $form['#suffix'] = '<br><br></div>';
    
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
        '#button_type' => 'primary',
        /*'#ajax' => [
            'callback' => [$this, 'form_ajax_submit'],
            'method' => 'append',
            'effect' => 'fade'
        ]*/
    ];
    
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    return $form;
}
  /**
   * {@inheritdoc}
   */
public function submitForm(array &$form, FormStateInterface $form_state) {
  $this->configFactory->getEditable(Helper::SETTINGS)
  ->set('nidds_val', $form_state->getValue('news'))
  ->save();

    $niids = $form_state->getValue('news');
      $result = Newsletter::sendNewsLetter($niids);
      if($result){
        \Drupal::messenger()->addMessage("Newsletter a été bien envoyée aux destinataires");
      }
      $form_state->setRedirect('dardev_appel_offre.admin_synchroniser');
      parent::submitForm($form, $form_state);
}


  
  
  public function form_ajax_submit(array &$form, FormStateInterface $form_state) {
      $niids = $form_state->getValue('news');
      
      $this->configFactory->getEditable(Helper::SETTINGS)
      ->set('nidds_val', $form_state->getValue('news'))
      ->save();

    
      $result = Newsletter::sendNewsLetter($niids);
      \Drupal::messenger()->addMessage("Newsletter a été bien envoyée aux destinataires");
      $ajax_response = new AjaxResponse();
      $ajax_response->addCommand(new AppendCommand('#modal_send_form', "<span style=\"color:green\">Newsletter a été bien envoyée aux destinataires</span>"));
      parent::form_ajax_submit($form, $form_state);
    }
  
}