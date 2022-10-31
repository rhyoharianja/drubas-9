<?php

namespace Drupal\vtmodules\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an Events block.
 *
 * @Block(
 *   id = "vtevents_block",
 *   admin_label = @Translation("Events"),
 *   category = @Translation("VT Events Block")
 * )
 */
class VTEventsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $db = \Drupal::database();
    $vtQueryEvent = $db->select('node_field_data', 'n');
    $vtQueryEvent->join('node__body', 'a', 'n.nid = a.entity_id');
    $vtQueryEvent->join('node__field_event_category', 'b', 'n.nid = b.entity_id');
    $vtQueryEvent->join('node__field_event_date', 'c', 'n.nid = c.entity_id');
    $vtQueryEvent->join('node__field_event_image', 'd', 'n.nid = d.entity_id');
    $vtQueryEvent->join('node__field_event_category', 'e', 'n.nid = e.entity_id');
    $vtQueryEvent->fields('n', array('nid'));
    $vtQueryEvent->fields('a', array('body_value'));
    $vtQueryEvent->fields('c', array('field_event_date_value'));
    $vtQueryEvent->fields('c', array('field_event_date_end_value'));
    $vtQueryEvent->condition('n.type', 'events');
    $vtQueryEvent->condition('n.status', 1);
    $vtQueryEvent->condition('field_event_date_value', date('Y-m-d'), '<=');
    $vtQueryEvent->condition('field_event_date_end_value', date('Y-m-d'), '>=');
    $vtQueryEvent->orderBy('n.created', 'DESC');
    $vtIdsEvent = $vtQueryEvent->execute()->fetchAll();

    foreach ($vtIdsEvent as $key => $node) {
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
      $response['category'] = $details->field_event_category->value;
      $response['description'] = $node->body_value;

      $response['image'] = $details->field_event_image->entity->getFileUri();

      $responses[] = $response;
    }

    // var_dump($responses);

    return [
      '#theme' => 'vtevents_block',
      '#responses' => $responses,
    ];
  }

}
