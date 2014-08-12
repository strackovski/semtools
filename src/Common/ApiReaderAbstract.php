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

use nv\semtools\Common\ReaderInterface;
use nv\semtools\Common\RequestInterface;

/**
 * Class ApiReader
 *
 * Base type for classes that perform read requests to API services.
 *
 * @package nv\semtools\Common
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
abstract class ApiReaderAbstract implements ReaderInterface
{
    /**
     * The API key provided by the API service provider
     *
     * @var string API key
     */
    protected $apiKey;

    /**
     * API URL Endpoint
     *
     * @var string Base API URL
     */
    protected $apiEndpoint;

    /**
     * @var string API URL Format to use with sprintf
     */
    protected $apiQueryStringRequestFormat;

    /**
     * The request
     *
     * @var \nv\semtools\Common\RequestInterface
     */
    protected $request;

    /**
     * Read data
     *
     * @param \nv\semtools\Common\RequestInterface
     *
     * @return mixed
     */
    abstract public function read(RequestInterface $request);

    /**
     * Execute request
     *
     * @return mixed
     */
    abstract protected function executeRequest();

    /**
     * Set API key
     *
     * @param $key
     *
     * @return bool True on success, false on failure.
     */
    public function setApiKey($key)
    {
        $this->apiKey = $key;
        return $this->apiKey === $key ? true : false;
    }

    /**
     * Set API URL
     *
     * @param $url
     *
     * @return bool
     */
    public function setApiEndpoint($url)
    {
        $this->apiEndpoint = $url;
        return $this->apiEndpoint === $url ? true : false;
    }

    /**
     * Set API URL REST format
     * Used as format for sprintf to inject parameters into the URL string.
     *
     * @param $format
     *
     * @return bool
     */
    public function setApiQueryStringRequestFormat($format)
    {
        $this->apiQueryStringRequestFormat = $format;
        return $this->apiQueryStringRequestFormat === $format ? true : false;
    }

    /**
     * Get API REST URL format
     *
     * @return mixed
     */
    public function getApiQueryStringRequestFormat()
    {
        return $this->apiQueryStringRequestFormat;
    }

    /**
     * Get API URL
     *
     * @return mixed
     */
    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }
}
