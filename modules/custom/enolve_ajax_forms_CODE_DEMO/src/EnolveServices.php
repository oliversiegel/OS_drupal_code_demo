<?php

namespace Drupal\enolve_ajax_forms;

use \Drupal\node\Entity\Node;
use \Drupal\taxonomy\Entity\Term;
use Drupal\views\Views;
use Drupal\message\Entity\Message;

class EnolveServices{
  

  

  public function getTermsFieldArray($vid,$terms,$existing = []){
    
    $brand_new_terms_to_add = [];
    $terms_to_add = [];
    
    $existing_array = [];
    
    foreach( $existing as $t){
      $existing_array[] = $t['target_id'];
    }
    
    foreach($terms['existing'] as $ts){
      $t = intval($ts);
      
      if($t && !in_array($t,$existing_array)){
        $existing_array[] = $t;
        $terms_to_add[] = $t;
      }
      
    }
    
    foreach($terms['new'] as $new_term_name){
      
      $new_tid = $this->createNewTerm($new_term_name, $vid, []);
      $terms_to_add[] = $new_tid;
      
    }
    
    foreach($terms_to_add as $t_add){
      $existing[] = [ 'target_id' => $t_add ];
    }    
    
    return $existing;
    
  }
  
  public function createNewTerm($term_name, $vocab, array $parent = []){
    
    $user_id = \Drupal::currentUser()->id();
    
    $new_term = Term::create([
      'name' => $term_name,
      'vid' => $vocab,
      'parent' => $parent,
      'field_created_by' => [
        ['target_id' => $user_id]
      ]
    ]);

      // Save the taxonomy term.
      $new_term->save();

      // Return the taxonomy term id.
      return $new_term->id();
  }

  
  public function addTaxonomyToNode($node_id,$terms,$vid){
    $field = 'field_category';
    $label = 'Tags and Categories';
    
    return $this->addTermsToNode($node_id,$terms,$field,$vid,$label);
  }
  
  public function addTermsToNode($node_id,$terms,$field,$vid,$label){
    
    $node = Node::load($node_id);
    
    $existing_orig = $node->get($field)->getValue();
    
    $existing = $this->getTermsFieldArray($vid,$terms,$existing_orig);
    
    $terms_to_add_count = count($existing) - count($existing_orig);
    
    $user_id = \Drupal::currentUser()->id();
    
    $node->setNewRevision(TRUE);
    $node->revision_log = 'Added '.$terms_to_add_count.' '.$label.'.';
    $node->setRevisionCreationTime( time() );
    $node->setRevisionUserId($user_id);
    
    $node->$field = $existing;
    
    $node->save();
    
    $notification = Message::create(['template' => 'notification', 'uid' => $user_id]);
    //$message->set('field_node_ref', $node);
    $notification->save();
    
    
    $node = Node::load($node_id);
    
    return $node->get($field)->getValue();
  }  
  
  public function getTagView($nid){
    
    $node = Node::load($nid);
    $render_array = $node->field_category->view();
    $output = \Drupal::service('renderer')->renderRoot($render_array);
    return $output;
  }  
  
  public function getTeaserView($nid, $wrapped = false){
    
    $node = Node::load($nid);
    $view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
    $render_array = $view_builder->view($node, 'teaser');
    $output = \Drupal::service('renderer')->renderRoot($render_array);
    
    if($wrapped){
      $output = '<div class="views-row">'.$output.'</div>';
    }
    
    return $output;
    
  }
  
  public function getTagIdsAsArrayFromNode($nid,$field){
    
    $node = Node::load($nid);
    
    $existing = $node->get($field)->getValue();
    $return_array = [];
    
    foreach( $existing as $t){
      $return_array[] = $t['target_id'];
    }
    
    return $return_array;
  }
  
  public function getEnolveEntityReferenceForm($params){
    
    $placeholder = $params['placeholder'];
    $type = $params['ajax_form_type'];
    $nid = $params['origin_nid'];
    $search_type = $type;
    $input_name = $type;
    $form_type = 'enolve-tagging-form';
    
    if($type=='entry'){
      $form_type = 'enolve-entry-form';
      
      if(array_key_exists('type_to_add',$params)){
        $search_type = $params['type_to_add'];
      }
      
      $input_name = $params['input_name'];
    }
    
    $search_url = \Drupal\Core\Url::fromRoute('enolve_ajax_forms.entity_reference_search', ['search_type' => $search_type])->setAbsolute(1);
    $url = $search_url->toString();

    $container_class='';
    if($params['standalone']){
      $container_class='standalone';
    }

    $form['element_container_start_'.$type] = [
      '#markup' => '<div 
                class="enolve-ajax-element-container '.$form_type.' '.$container_class.'" 
                id="'.$input_name.'_textfield_wrapper_'.$nid.'" 
                data-tagtype="'.$search_type.'" 
                data-containerid="'.$type.'_container_'.$nid.'"
              >'
    ];
    
    $form['add-'.$input_name.'-textfield-'.$nid] = [
      //'#title' => t('Title'),
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['enolve-ajax-form-element'],
        'placeholder' => [$placeholder],
        'data-inputname' => ['add_'.$input_name.'_'.$nid],
        'data-submitname' => [$input_name],
        'autocomplete' => ['off'],
        'size' => ['1'],
        'data-search-url' => [$url],
        'data-origin_nid' => [$nid]
      ],  
    ];
    if($type=='entry'){
      $form['submit_name'] = [
        '#type' => 'hidden',
        '#value' => $input_name
      ];
      $form['type_to_add'] = [
        '#type' => 'hidden',
        '#value' => $search_type
      ];
    }

    $form['search_result_container_'.$type] = [
      '#markup' => '<div class="enolve-ajax-search-results" id="'.$type.'_search_results_'.$nid.'"></div>'
    ];
    
    $form['element_container_end_'.$type] = [
      '#markup' => '</div>'
    ];

    if($params['standalone']){

      $form['nid'] = [
        '#type' => 'hidden',
        '#value' => $nid
      ];

      $form['cancel_button'] = [
        '#type' => 'button',
        '#value' => 'Cancel',
        '#attributes' => [
          'class' => ['cancel_adding']
        ],
      ];

      $form['ajax_submit'] = [
        '#type' => 'button',
        '#value' => 'Save',
        '#ajax' => [
          'callback' => '::add'.$type,
        ],
      ];
      
      if($type=='entry'){
        $form['ajax_add_new'] = [
          '#type' => 'button',
          '#value' => 'Show Add New Form',
          '#ajax' => [
            'callback' => '::getNewFormAjax',
          ],
          '#attributes' => [
            'class' => ['hidden','add_new_entry_button']
          ],
        ];
      }
    
    }
    
    return $form;
  }
  
}