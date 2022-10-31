<?php

namespace Drupal\vtmodules\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an News block.
 *
 * @Block(
 *   id = "vtnews_block",
 *   admin_label = @Translation("News"),
 *   category = @Translation("VT News Block")
 * )
 */
class VTNewsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $db = \Drupal::database();
    $vtQueryNews = $db->select('node_field_data', 'n');
    $vtQueryNews->fields('n', array('nid'));
    $vtQueryNews->condition('n.type', 'news');
    $vtQueryNews->condition('n.status', 1);
    $vtQueryNews->orderBy('n.created', 'DESC');
    $vtIdsNews = $vtQueryNews->execute()->fetchAll();

    foreach ($vtIdsNews as $key => $node) {
      $details = \Drupal::entityTypeManager()->getStorage('node')->load($node->nid);

      $date = $details->getCreatedTime();
    // Here you can use drupal's format_date() function, or some custom PHP date formatting.
      $response['date'] = \Drupal::service('date.formatter')->format($date, '$format');// enter date format in $format.

      $options = ['absolute' => TRUE];
      $url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $node->nid], $options);
      $nodeurl = $url->toString();

      $response['nid'] = $details->nid->value;
      $response['url'] = $nodeurl;
      $response['title'] = $details->title->value;
      $response['category'] = $details->field_news_category->value;
      $response['description'] = $details->body->value;
      $response['image'] = $details->field_news_->entity->getFileUri();

      $responses[] = $response;
    }
    // var_dump($responses);
    return [
      '#theme' => 'vtnews_block',
      '#responses' => $responses,
    ];
  }

}
