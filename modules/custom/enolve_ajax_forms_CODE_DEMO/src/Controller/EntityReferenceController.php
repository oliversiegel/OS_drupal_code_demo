<?php

namespace Drupal\enolve_ajax_forms\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
// use Drupal\node\NodeInterface;
// use Drupal\node\Entity\Node;
use Drupal\Core\Entity;
use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;

use \Drupal\node\Entity\Node;

class EntityReferenceController{
  
  private $search_string = '';
  private $ids_to_exclude = [];
  private $origin_nid;
  private $configuration;
  private $entityTypeManager;
  private $entityRepository;
  
  public function search($search_type) {
    
    if(!empty($_POST['search_string'])){
      $this->search_string = $_POST['search_string'];
    }
    
    if(!empty($_POST['origin_nid'])){
      $this->origin_nid = intval( $_POST['origin_nid'] );
    }
    
    if(!empty($_POST['id_to_exclude'])){
      foreach($_POST['id_to_exclude'] as $i){
        
        $this->ids_to_exclude[] = intval($i);
        
      }
    }
    
    $method = 'find_'.$search_type;
    
    if( strlen( $this->search_string ) >= 1 && method_exists($this, $method)){
      
      $results = $this->{$method}();

    }
    else
    {
      $results = [];
    }
    
    return new JsonResponse( $results );
  
  }
  
  public function find_tags(){
    
    return $this->query_taxonomy('tags');

  }
  
  public function find_entry(){
    return $this->query_entries('all');
    
  }
  
  
  public function query_taxonomy($vid){
    
    $query = \Drupal::entityQuery('taxonomy_term'); 
    $query->condition('vid', $vid);
    
    if(!empty($this->origin_nid)){
      $this->get_tag_ids_of_node($vid);
    }
    
    if(!empty($this->ids_to_exclude)){
      $query->condition('tid', $this->ids_to_exclude, 'NOT IN');
    }
    
    
    
    $query->condition('name', $this->search_string, 'CONTAINS');
    
    $query->range(0, 12);
    $term_ids = $query->execute();
    $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($term_ids);
    
    $result = [];
    
    foreach($terms as $term){
      $result[] = [
        'id' => $term->id(),
        'title' => $term->getName()
      ];
    }

    return $result;
    
  }
  
  public function get_tag_ids_of_node($vid){
    
    if($vid == 'tags'){
      $field = 'field_category';  
      //still need to ensure taxonomy id and field name are streamlined to make this scalable
    }
    
    $enolve_service = \Drupal::service('enolve_ajax_forms.services');
    $existing = $enolve_service->getTagIdsAsArrayFromNode($this->origin_nid,$field);
    
    $this->ids_to_exclude = array_merge($this->ids_to_exclude, $existing);
    
  }
  


}