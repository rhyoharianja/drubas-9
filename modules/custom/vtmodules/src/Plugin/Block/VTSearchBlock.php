<?php

namespace Drupal\vtmodules\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an Search block.
 *
 * @Block(
 *   id = "vtsearch_block",
 *   admin_label = @Translation("Search Banner"),
 *   category = @Translation("VT Search Banner Block")
 * )
 */
class VTSearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    return [
        '#theme' => 'vtsearch_block',
        '#responses' => [
            'title' => 'Welcome To Vox Teneo',
            'description' => 'We create effective solution'
        ]
    ];
  }

}
