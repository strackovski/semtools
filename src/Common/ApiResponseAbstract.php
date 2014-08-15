<?php
/**
 * This file belongs to semtools project
 *
 * @copyright 2014 Vladimir Stračkovski
 * @license   The MIT License (MIT) <http://choosealicense.com/licenses/mit/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit the link above.
 */

namespace nv\semtools\Common;

use nv\semtools;

/**
 * Base ApiResponse Class
 *
 * @package nv\semtools\Common
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
abstract class ApiResponseAbstract implements ResponseInterface
{
    /**
     * The response returned by API
     *
     * @var string Response data
     */
    protected $response;

    protected $responseRaw;

    /**
     * @var null
     */
    protected $request;

    /**
     * Constructor
     *
     * @param mixed $responseData API response data
     * @param   $request
     */
    public function __construct($responseData, ApiRequestAbstract $request = null)
    {
        $this->responseRaw = $responseData;
        $this->request = $request;
        $this->response = $this->init();
    }

    /**
     * A concrete Response class should implement an initialization method
     * to perform provider specific response parsing.
     *
     * @return mixed
     */
    abstract protected function init();

    /**
     * Set response
     *
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Get response
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function getResponseRaw()
    {
        return $this->responseRaw;
    }

    /**
     * @param \SimpleXMLElement $parent
     * @param                   $xml
     * @param bool              $before
     *
     * @return bool
     */
    protected function simplexmlImportXml(\SimpleXMLElement $parent, $xml, $before = false)
    {
        $xml = (string)$xml;
        if ($nodata = !strlen($xml) or $parent[0] == null) {
            return $nodata;
        }

        $node = dom_import_simplexml($parent);
        $fragment = $node->ownerDocument->createDocumentFragment();
        $fragment->appendXML($xml);

        if ($before) {
            return (bool)$node->parentNode->insertBefore($fragment, $node);
        }

        return (bool)$node->appendChild($fragment);
    }
}
