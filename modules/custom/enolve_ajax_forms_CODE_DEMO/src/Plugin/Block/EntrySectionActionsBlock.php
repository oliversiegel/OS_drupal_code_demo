<?php

namespace Drupal\enolve_ajax_forms\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'entry_section_actions_block' block.
 *
 * @Block(
 *   id = "entry_section_actions_block",
 *   admin_label = @Translation("Enolve Entry Section Actions Block"),
 *
 * )
 */
class EntrySectionActionsBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {

  $params = $this->getConfiguration();

    $keys = [
        'add_form',
        'count',
        'section_id',
        'load_more_button',
        'load_more_url',
        'all_button',
        'total_pages',
        'load_less_button',
        'add_button',
        'show_add_button',
        'show_add_form',
    ];

    $params = $this->getDefaultParams($keys);

    return $this->buildReturnArray($params,'enolve-entry-subsection-actions');
  }
  
  public function getDefaultParams($keys){
    
    $params = $this->getConfiguration();
    
    foreach($keys as $key){
      if(!array_key_exists($key,$params)){
        $params[$key] = NULL;
      }
    }
    
    return $params;
  }
  
  public function buildReturnArray($params,$theme){
    $return = [];
    $return['#theme'] = $theme;
    
    foreach($params as $key => $val){
      $return['#'.$key] = $val;
    }
    
    return $return;
    
  }
  
}