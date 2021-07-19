<?php
namespace Drupal\api_key\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiKeyPageController
 * @package Drupal\api_key\Controller
 */
class ApiKeyPageController {

  /**
   * @return JsonResponse
   */
  public function index(Request $request) {
    $result = [];
    $nid = $request->attributes->get('_raw_variables')->get('nid');
    $api_key = $request->attributes->get('_raw_variables')->get('api_key');
    $config = \Drupal::config('system.site');
    $site_api_key = $config->get('siteapikey');
    // Validating the site key with request param
    if(strcmp($site_api_key, $api_key) !== 0){
      $result = [
        "error" => "Access Denied"
      ];
    }
    else {
      // Quering the page node with request param
      $query = \Drupal::entityQuery('node')
      ->condition('type', 'page')
      ->condition('nid', $nid);

      $validate_nid = $query->execute();
      // Checking the node exists
      if (sizeof($validate_nid)) {
        $node = \Drupal\node\Entity\Node::load($nid);
        // Printing the requested object on the screen
        $result = [
          "id" => $nid,
          "title" => $node->getTitle(),
          "type" => $node->getType(),
          "body" => $node->get('body')->getValue()
        ];
      }
      else {
        $result = [
          "error" => "Access Denied"
        ];
      }
    } 
    return new JsonResponse($result);
  }
}