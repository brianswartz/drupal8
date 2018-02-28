<?php

namespace Drupal\opengraph;

use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OpenGraphMetaService {

  /**
   *
   */
  private $node;

  /**
   *
   */
  private $metaTags;


  /**
   *
   */
  public function __construct() {
  }

  /**
   *
   */
  public static function create(ContainerInterface $container) {

    return new static(
    );
  }

  /**
   *
   */
  public function createOpenGraphMetaTags(&$page) {

    // load the associated node.
    $nid = \Drupal::routeMatch()->getRawParameter('node');
    if (empty($nid)) return;
    $this->node = Node::load($nid); 

    // load the meta tag data.
    $this->_loadMetaTagData();

    // append the meta tags.
    $this->_appendMetaTags($page);
  }

  /**
   *
   */
  private function _loadMetaTagData() {

    // create the array.
    $this->metaTags = array();

    // get the node information.
    $this->metaTags['url'] = Url::fromRoute('entity.node.canonical', ['node' => $this->node->id()])->toString();
    $this->metaTags['title'] = $this->node->getTitle();
    $this->metaTags['type'] = 'article';
    $this->metaTags['description'] = 'This is the description that should show up!!!';
    $this->metaTags['image'] = file_create_url($this->node->get('field_image')->entity->getFileUri());
  }

  /**
   *
   */
  private function _appendMetaTags(&$page) {

    // create the array.
    $tags = array();

    // create the attributes.
    $tags['opengraph_og_title'] = array('#tag' => 'meta', '#attributes' => array('name' => 'og:title', 'content' => $this->metaTags['title']));
    $tags['opengraph_og_type'] = array('#tag' => 'meta', '#attributes' => array('name' => 'og:type', 'content' => $this->metaTags['type']));
    $tags['opengraph_og_image'] = array('#tag' => 'meta', '#attributes' => array('name' => 'og:image', 'content' => $this->metaTags['image']));
    $tags['opengraph_og_url'] = array('#tag' => 'meta', '#attributes' => array('name' => 'og:url', 'content' => $this->metaTags['url']));
    $tags['opengraph_og_description'] = array('#tag' => 'meta', '#attributes' => array('name' => 'og:description', 'content' => $this->metaTags['description']));
    $tags['opengraph_twitter_card'] = array('#tag' => 'meta', '#attributes' => array('name' => 'twitter:card', 'content' => 'summary_large_image'));
    $tags['opengraph_twitter_site'] = array('#tag' => 'meta', '#attributes' => array('name' => 'twitter:site', 'content' => '@ahla'));
    $tags['opengraph_twitter_creator'] = array('#tag' => 'meta', '#attributes' => array('name' => 'twitter:creator', 'content' => '@ahla'));
    $tags['opengraph_twitter_title'] = array('#tag' => 'meta', '#attributes' => array('name' => 'twitter:title', 'content' => $this->metaTags['title']));
    $tags['opengraph_twitter_description'] = array('#tag' => 'meta', '#attributes' => array('name' => 'twitter:description', 'content' => $this->metaTags['description']));
    $tags['opengraph_twitter_image'] = array('#tag' => 'meta', '#attributes' => array('name' => 'twitter:image', 'content' => $this->metaTags['image']));
    

    // append the attributes to the page.
    foreach ($tags as $key => $value) {
      $page['#attached']['html_head'][] = array($value, $key);
    }
  }
}
