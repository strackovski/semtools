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

namespace nv\semtools\Annotators\OpenCalais;

use nv\semtools\Exception;
use nv\semtools\Common;

/**
 * Class OpenCalaisReader
 *
 * Provides read access to Reuters OpenCalais API service.
 *
 * Calais is a rapidly growing toolkit of capabilities that allow you to
 * readily incorporate state-of-the-art semantic functionality within your blog,
 * content management system, website or application.
 *
 * @link at http://www.opencalais.com/
 * @package nv\semtools\Annotators\OpenCalais
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class OpenCalaisReader extends Common\ApiReaderAbstract
{
    /**
     * Request
     *
     * @var \nv\semtools\Annotators\OpenCalais\OpenCalaisRequest
     */
    protected $request;

    /**
     * Constructor
     *
     * @param $apiKey
     *
     * @throws Exception\ServiceReaderException
     */
    public function __construct($apiKey)
    {
        if (empty($apiKey)) {
            throw new Exception\ServiceReaderException('API key cannot be empty.');
        }
        $this->apiKey = $apiKey;
        $this->apiEndpoint = 'http://api.opencalais.com/enlighten/rest/';
        $this->apiQueryStringRequestFormat = 'licenseID=%s&content=%s&paramsXML=%s';
    }

    /**
     * Read API
     *
     * @param Common\RequestInterface $request
     *
     * @throws \InvalidArgumentException
     * @return OpenCalaisResponse
     */
    public function read(Common\RequestInterface $request)
    {
        if (!$request instanceof OpenCalaisRequest) {
            throw new \InvalidArgumentException("Expected instance of OpenCalaisRequest, got ".get_class($request));
        }
        $this->request = $request;

        return $this->executeRequest();
    }

    /**
     * Execute read request
     *
     * @throws Exception\ServiceReaderException
     * @return OpenCalaisResponse
     */
    protected function executeRequest()
    {
        $options  = 'licenseID='  . urlencode($this->apiKey);
        $options .= '&paramsXML=' . urlencode($this->request->generateXMLRequestString());
        $options .= '&content='   . urlencode($this->request->getTextData());

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->getApiEndpoint());
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $options);
        curl_setopt($ch, CURLOPT_POST, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        if (strpos($response, "<Exception>") !== false) {
            preg_match("/<Exception\>(.*)<\/Exception>/mu", $response, $matches);
            throw new Exception\ServiceReaderException($matches[1]);
        }

        return new OpenCalaisResponse($response);
    }
}
