<?php

namespace Drupal\enolve_ajax_forms\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * @Block(
 *   id = "tags_ajax_form",
 *   admin_label = @Translation("Tags and Categories Ajax Form Block"),
 *  )
 */

class TagsFormBlock extends BlockBase{
  
  public function build() {

    $params = $this->getConfiguration();
    
    $return_array = \Drupal::formBuilder()->getForm('Drupal\enolve_ajax_forms\Form\AjaxTagsForm', $params);
    
    $return_array['#attached'] = [ 'library' => ['enolve_ajax_forms/enolve-ajax-forms']];
    
    return $return_array;
    
  }
  
}