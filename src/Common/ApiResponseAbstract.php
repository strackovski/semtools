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
namespace nv\semtools;

use \nv\semtools;

/**
 * Class OpenCalaisReader
 *
 * @package nv\semtools
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
abstract class ApiResponse implements ResponseInterface
{
    /** @var string Response data*/
    private $response;

    public function __construct($responseData)
    {
        $this->response = $responseData;
    }

    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }
}
