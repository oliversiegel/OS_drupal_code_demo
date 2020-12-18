<?php

namespace Drupal\enolve_ajax_forms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ReplaceCommand;

use \Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

class AjaxTagsForm extends FormBase{
  
  public function getFormId(){
    return 'ajaxtags_form';
  }
  
  public function buildForm( array $form, FormStateInterface $formstate, $params = NULL){

    $enolve_service = \Drupal::service('enolve_ajax_forms.services');
    $form = $enolve_service->getEnolveEntityReferenceForm($params);

    return $form;
  }
  
  public function addtags(array $form, FormStateInterface $form_state){

    $nid = $form_state->getValue('nid');
    $input_values = $form_state->getUserInput();
    $existing_to_add = [];
    $new_to_add = [];
    
    if(array_key_exists('existing_add_tags_'.$nid, $input_values)){
      $existing_to_add = $input_values['existing_add_tags_'.$nid];  
    }
    
    if(array_key_exists('new_add_tags_'.$nid, $input_values)){
      $new_to_add = $input_values['new_add_tags_'.$nid];
    }
    
    $to_add = [
      'new'=>$new_to_add,
      'existing'=>$existing_to_add,
    ];
    
    $enolve_service = \Drupal::service('enolve_ajax_forms.services');
    
    $updated_terms = $enolve_service->addTagsToNode($nid,$to_add);

    $content = $enolve_service->getTagView($nid);
    
    
    $response = new AjaxResponse();
    $selector = '#tags_container_'.$nid;
    
    //$response->addCommand(new AppendCommand($selector, $content));    
    //$response->addCommand(new HtmlCommand(  $selector, $content));
    $response->addCommand(new ReplaceCommand(  $selector, $content));
    $response->addCommand(new InvokeCommand('html','afterAjaxAddTag',[$nid,'tags_section']));

    return $response;
  }

  
  public function submitForm(array &$form, FormStateInterface $form_state){
    // $tid = $tag[0]['target_id'];
    // $term = Term::load($tid);
    // $name = $term->getName();
    // drupal_set_message($name);
  }
}