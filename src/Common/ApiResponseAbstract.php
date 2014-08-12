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

    /**
     * Constructor
     *
     * @param mixed $responseData API response data
     */
    public function __construct($responseData)
    {
        $this->response = $responseData;
    }

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
}
