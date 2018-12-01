<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Advancedactivity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Advancedactivity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Advancedactivity_LinkController extends Core_Controller_Action_Standard
{
  public function init()
  {
    $this->_helper->contextSwitch
      ->addActionContext('create', 'json')
      //->addActionContext('preview', 'json')
      ->initContext();
  }

  public function createAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams('core_link', null, 'create')->isValid() ) return;
    
    // Make form
    $this->view->form = $form = new Core_Form_Link_Create();
    $translate        = Zend_Registry::get('Zend_Translate');

    // Check method
    if( !$this->getRequest()->isPost() )
    {
      $this->view->status = false;
      $this->view->error = $translate->_('Invalid method');
      return;
    }

    // Check data
    if( !$form->isValid($this->getRequest()->getPost()) )
    {
      $this->view->status = false;
      $this->view->error = $translate->_('Invalid data');
    }

    // Process
    $viewer = Engine_Api::_()->user()->getViewer();
    
    $table = Engine_Api::_()->getDbtable('links', 'core');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      $link = Engine_Api::_()->getApi('links', 'advancedactivity')->createLink($viewer, $form->getValues());

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    $this->view->status   = true;
    $this->view->message  = $translate->_('Link created');
    $this->view->identity = $link->getIdentity();
  }
  
    public function previewAction()
  {
    if( !$this->_helper->requireUser()->isValid() )
      return;
    if( !$this->_helper->requireAuth()->setAuthParams('core_link', null, 'create')->isValid() )
      return;

    // clean URL for html code
    $uri = trim(strip_tags($this->_getParam('uri')));
    $displayUri = $uri;
    $info = parse_url($displayUri);
    if( !empty($info['path']) ) {
      $displayUri = str_replace($info['path'], urldecode($info['path']), $displayUri);
    }
    $this->view->url = Engine_String::convertUtf8($displayUri);
    $this->view->title = '';
    $this->view->description = '';
    $this->view->thumb = null;
    $this->view->imageCount = 0;
    $this->view->images = array();
    try {
      $config = Engine_Api::_()->getApi('settings', 'core')->core_iframely;
      if( !empty($config['host']) && $config['host'] != 'none' ) {
        $this->_getFromIframely($config, $uri);
      } else {
        $this->_getFromClientRequest($uri);
      }
    } catch( Exception $e ) {
      throw $e;
    }
    $this->view->title = Engine_String::convertUtf8($this->view->title);
    $this->view->description = Engine_String::convertUtf8($this->view->description);
  }

  protected function _getFromIframely($config, $uri)
  {
    $iframely = Engine_Iframely::factory($config)->get($uri);
    $images = array();
    if( !empty($iframely['links']['thumbnail']) ) {
      $images[] = $iframely['links']['thumbnail'][0]['href'];
    }
    if( !empty($iframely['meta']['title']) ) {
      $this->view->title = $iframely['meta']['title'];
    }
    if( !empty($iframely['meta']['description']) ) {
      $this->view->description = $iframely['meta']['description'];
    }
    $this->view->imageCount = count($images);
    $this->view->images = $images;
    $allowRichHtmlTyes = array(
      'player',
      'image',
      'reader',
      'survey',
      'file'
    );
    $typeOfContent = array_intersect(array_keys($iframely['links']), $allowRichHtmlTyes);
    if( $typeOfContent && !empty($iframely['html']) ) {
      $this->view->richHtml = $iframely['html'];
    } else if( $this->view->imageCount < 1 ) {
      $this->_getFromClientRequest($uri);
    }
  }

  protected function _getFromClientRequest($uri)
  {
    $info = parse_url($uri);
    if( !empty($info['path']) ) {
      $path = urldecode($info['path']);
      foreach( explode('/', $info['path']) as $path ) {
        $paths[] = urlencode($path);
      }
      $uri = str_replace($info['path'], join('/', $paths), $uri);
    }
    $client = new Zend_Http_Client($uri, array(
      'maxredirects' => 2,
      'timeout' => 10,
    ));
    // Try to mimic the requesting user's UA
    $client->setHeaders(array(
      'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
      'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      'X-Powered-By' => 'Zend Framework'
    ));
    $response = $client->request();
    // Get content-type
    list($contentType) = explode(';', $response->getHeader('content-type'));
    $this->view->contentType = $contentType;
    // Handling based on content-type
    switch( strtolower($contentType) ) {
      // Images
      case 'image/gif':
      case 'image/jpeg':
      case 'image/jpg':
      case 'image/tif': // Might not work
      case 'image/xbm':
      case 'image/xpm':
      case 'image/png':
      case 'image/bmp': // Might not work
        $this->_previewImage($uri, $response);
        break;
      // HTML
      case '':
      case 'text/html':
        $this->_previewHtml($uri, $response);
        break;
      // Plain text
      case 'text/plain':
        $this->_previewText($uri, $response);
        break;
      // Unknown
      default:
        break;
    }
  }

  protected function _previewImage($uri, Zend_Http_Response $response)
  {
    $this->view->imageCount = 1;
    $this->view->images = array($uri);
  }

  protected function _previewText($uri, Zend_Http_Response $response)
  {
    $body = $response->getBody();
    if( preg_match('/charset=([a-zA-Z0-9-_]+)/i', $response->getHeader('content-type'), $matches) ||
      preg_match('/charset=([a-zA-Z0-9-_]+)/i', $response->getBody(), $matches) ) {
      $charset = trim($matches[1]);
    } else {
      $charset = 'UTF-8';
    }
    // Reduce whitespace
    $body = preg_replace('/[\n\r\t\v ]+/', ' ', $body);
    $this->view->title = substr($body, 0, 63);
    $this->view->description = substr($body, 0, 255);
  }

  protected function _previewHtml($uri, Zend_Http_Response $response)
  {
    $body = $response->getBody();
    $body = trim($body);
    if( preg_match('/charset=([a-zA-Z0-9-_]+)/i', $response->getHeader('content-type'), $matches) ||
      preg_match('/charset=([a-zA-Z0-9-_]+)/i', $response->getBody(), $matches) ) {
      $this->view->charset = $charset = trim($matches[1]);
    } else {
      $this->view->charset = $charset = 'UTF-8';
    }
    if( function_exists('mb_convert_encoding') ) {
      $body = mb_convert_encoding($body, 'HTML-ENTITIES', $charset);
    }
    // Get DOM
    if( class_exists('DOMDocument') ) {
      $dom = new Zend_Dom_Query($body);
    } else {
      $dom = null; // Maybe add b/c later
    }
    $title = null;
    if( $dom ) {
      $titleList = $dom->query('title');
      if( count($titleList) > 0 ) {
        $title = trim($titleList->current()->textContent);
      }
    }
    $this->view->title = $title;
    $description = null;
    if( $dom ) {
      $descriptionList = $dom->queryXpath("//meta[@name='description']");
      // Why are they using caps? -_-
      if( count($descriptionList) == 0 ) {
        $descriptionList = $dom->queryXpath("//meta[@name='Description']");
      }
      // Try to get description which is set under og tag
      if( count($descriptionList) == 0 ) {
        $descriptionList = $dom->queryXpath("//meta[@property='og:description']");
      }
      if( count($descriptionList) > 0 ) {
        $description = trim($descriptionList->current()->getAttribute('content'));
      }
    }
    $this->view->description = $description;
    $thumb = null;
    if( $dom ) {
      $thumbList = $dom->queryXpath("//meta[@property='og:image']");
      $attributeType = 'content';
      if( count($thumbList) == 0 ) {
        $thumbList = $dom->queryXpath("//link[@rel='image_src']");
        $attributeType = 'href';
      }
      if( count($thumbList) > 0 ) {
        $thumb = $thumbList->current()->getAttribute($attributeType);
      }
    }
    $this->view->thumb = $thumb;
    $medium = null;
    if( $dom ) {
      $mediumList = $dom->queryXpath("//meta[@name='medium']");
      if( count($mediumList) > 0 ) {
        $medium = $mediumList->current()->getAttribute('content');
      }
    }
    $this->view->medium = $medium;
    // Get baseUrl and baseHref to parse . paths
    $baseUrlInfo = parse_url($uri);
    $baseUrl = null;
    $baseHostUrl = null;
    $baseUrlScheme = $baseUrlInfo['scheme'];
    $baseUrlHost = $baseUrlInfo['host'];
    if( $dom ) {
      $baseUrlList = $dom->query('base');
      if( $baseUrlList && count($baseUrlList) > 0 && $baseUrlList->current()->getAttribute('href') ) {
        $baseUrl = $baseUrlList->current()->getAttribute('href');
        $baseUrlInfo = parse_url($baseUrl);
        if( !isset($baseUrlInfo['scheme']) || empty($baseUrlInfo['scheme']) ) {
          $baseUrlInfo['scheme'] = $baseUrlScheme;
        }
        if( !isset($baseUrlInfo['host']) || empty($baseUrlInfo['host']) ) {
          $baseUrlInfo['host'] = $baseUrlHost;
        }
        $baseHostUrl = $baseUrlInfo['scheme'] . '://' . $baseUrlInfo['host'] . '/';
      }
    }
    if( !$baseUrl ) {
      $baseHostUrl = $baseUrlInfo['scheme'] . '://' . $baseUrlInfo['host'] . '/';
      if( empty($baseUrlInfo['path']) ) {
        $baseUrl = $baseHostUrl;
      } else {
        $baseUrl = explode('/', $baseUrlInfo['path']);
        array_pop($baseUrl);
        $baseUrl = join('/', $baseUrl);
        $baseUrl = trim($baseUrl, '/');
        $baseUrl = $baseUrlInfo['scheme'] . '://' . $baseUrlInfo['host'] . '/' . $baseUrl . '/';
      }
    }
    $images = array();
    if( $thumb ) {
      $images[] = $thumb;
    }
    if( $dom ) {
      $imageQuery = $dom->query('img');
      foreach( $imageQuery as $image ) {
        $src = $image->getAttribute('src');
        // Ignore images that don't have a src
        if( !$src || false === ($srcInfo = @parse_url($src)) ) {
          continue;
        }
        $ext = ltrim(strrchr($src, '.'), '.');
        // Detect absolute url
        if( strpos($src, '/') === 0 ) {
          // If relative to root, add host
          $src = $baseHostUrl . ltrim($src, '/');
        } elseif( strpos($src, './') === 0 ) {
          // If relative to current path, add baseUrl
          $src = $baseUrl . substr($src, 2);
        } elseif( !empty($srcInfo['scheme']) && !empty($srcInfo['host']) ) {
          // Contians host and scheme, do nothing
        } elseif( empty($srcInfo['scheme']) && empty($srcInfo['host']) ) {
          // if not contains scheme or host, add base
          $src = $baseUrl . ltrim($src, '/');
        } elseif( empty($srcInfo['scheme']) && !empty($srcInfo['host']) ) {
          // if contains host, but not scheme, add scheme?
          $src = $baseUrlInfo['scheme'] . ltrim($src, '/');
        } else {
          // Just add base
          $src = $baseUrl . ltrim($src, '/');
        }

        if( !in_array($src, $images) ) {
          $images[] = $src;
        }
      }
    }
    // Unique
    $images = array_values(array_unique($images));
    // Truncate if greater than 20
    if( count($images) > 30 ) {
      array_splice($images, 30, count($images));
    }
    $this->view->imageCount = count($images);
    $this->view->images = $images;
  }
}
