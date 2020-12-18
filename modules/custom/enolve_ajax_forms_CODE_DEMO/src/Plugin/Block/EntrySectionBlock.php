<?php

namespace Drupal\enolve_ajax_forms\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'entry_section_block' block.
 *
 * @Block(
 *   id = "entry_section_block",
 *   admin_label = @Translation("Enolve Entry Section Block"),
 *
 * )
 */
class EntrySectionBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {

  $params = $this->getConfiguration();

    $keys = [
      'id_attr',
      'container_class',
      'icon_1',
      'icon_2',
      'count',
      'label',
      'section_class',
      'section_id',
      'section_content',
      'section_actions',
    ];

    $params = $this->getDefaultParams($keys);

    return $this->buildReturnArray($params,'enolve-entry-subsection');
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