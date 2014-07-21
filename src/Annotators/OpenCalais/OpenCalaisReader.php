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

use \nv\semtools;

/**
 * Class OpenCalaisReader
 *
 * @package nv\semtools
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class OpenCalaisReader extends semtools\ApiReader
{
    /** @var \nv\semtools\Annotators\OpenCalais\OpenCalaisRequest */
    protected $request;

    /**
     * Constructor
     *
     * @param $api_key
     */
    public function __construct($api_key)
    {
        $this->apiKey = $api_key;
        $this->apiEndpoint = 'http://api.opencalais.com/tag/rs/enrich/';
        $this->apiQueryStringRequestFormat = 'licenseID=%s&content=%s&paramsXML=%s';
        $this->responseFormat = 'xml/rdf';
    }

    /**
     * @param semtools\RequestInterface $request
     *
     * @return mixed|OpenCalaisResponse
     * @throws \Exception
     */
    public function read(semtools\RequestInterface $request)
    {
        if (!$request instanceof OpenCalaisRequest) {
            throw new \Exception("Expected instance of OpenCalaisRequest, got ".get_class($request));
        }
        $this->request = $request;

        return $this->executeRequest();
    }

    /**
     * @return mixed|OpenCalaisResponse
     */
    protected function executeRequest()
    {
        $options = array(
            "x-calais-licenseID" => $this->apiKey,
            "Content-Type" => $this->request->getContentType(),
            "Accept" => $this->request->getAcceptFormat(),
            "enableMetadataType" => $this->request->getEnableMetadataType(),
            "calculateRelevanceScore" => $this->request->getCalculateRelevanceScore()
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->getApiEndpoint());
        curl_setopt($ch, CURLOPT_HTTPHEADER, $options);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request->getTextData());
        $response = curl_exec($ch);
        curl_close($ch);

        return new OpenCalaisResponse($response);
    }
}
