<?php
if (!defined('Auth_OpenID_VERSION')) exit;
/**
 * This concrete implementation of Auth_Yadis_XMLParser implements
 * the appropriate API for the 'dom' extension which is typically
 * packaged with PHP 5.  This class will be used whenever the 'dom'
 * extension is detected.  See the Auth_Yadis_XMLParser class for
 * details on this class's methods.
 */
require_once 'Auth/Yadis/XML.php';
class OpenID_ExXML extends Auth_Yadis_dom
{
    public function __construct()
    {
        // Call parent constructor
        $this->Auth_Yadis_dom();
    }

    public static function setXMLParser()
    {
        if (extension_loaded('dom')) {
            $p = new self();
            Auth_Yadis_setDefaultParser($p);
        }
    }

    /**
     * Patch of libxml bug
     * 
     * @author oov
     * @license BSD
     * @see http://blog.oovch.net/article/96574155.html
     * 
     * @param string $xpath
     * @param unknown_type $node
     * @return array $r
     */
    public function evalXPath($xpath, $node = null)
    {
        $r = array();
        switch ($xpath)
        {
        case '/xrd:Expires':
            if ( $this->doc->hasChildNodes() )
            {
                $nodes = $this->doc->childNodes;
                for( $i = 0, $len = $nodes->length; $i < $len; $i++ )
                {
                    $n = $nodes->item($i);
                    if ( $n->nodeName == 'xrd:Expires' || $n->localName == 'Expires' )
                        $r[] = $n;
                }
            }
            break;
        case '/xrds:XRDS[1]':
            if ( $this->doc->hasChildNodes() )
            {
                $nodes = $this->doc->childNodes;
                for( $i = 0, $len = $nodes->length; $i < $len; $i++ )
                {
                    $n = $nodes->item($i);
                    if ( $n->nodeName == 'xrds:XRDS' || $n->localName == 'XRDS' )
                    {
                        $r[] = $n;
                        break;
                    }
                }
            }
            break;
        case '/xrds:XRDS[1]/xrd:XRD':
            $xrds = null;
            if ( $this->doc->hasChildNodes() )
            {
                $nodes = $this->doc->childNodes;
                for( $i = 0, $len = $nodes->length; $i < $len; $i++ )
                {
                    $n = $nodes->item($i);
                    if ( $n->nodeName == 'xrds:XRDS' || $n->localName == 'XRDS' )
                    {
                        $xrds = $n;
                        break;
                    }
                }
            }

            if ( $xrds !== null && $xrds->hasChildNodes() )
            {
                $nodes = $xrds->childNodes;
                for( $i = 0, $len = $nodes->length; $i < $len; $i++ )
                {
                    $n = $nodes->item($i);
                    if ( $n->nodeName == 'xrd:XRD' || $n->localName == 'XRD' )
                        $r[] = $n;
                }
            }
            break;

        default:
            if ( $node == null )
                $node = $this->doc;

            list( , $xpathLocal ) = explode( ':', $xpath );

            $nodes = $node->childNodes;
            for( $i = 0, $len = $nodes->length; $i < $len; $i++ )
            {
                $n = $nodes->item($i);
                if ( $n->nodeName == $xpath || $n->localName == $xpathLocal )
                    $r[] = $n;
            }
            break;
        }
        return $r;
    }
}