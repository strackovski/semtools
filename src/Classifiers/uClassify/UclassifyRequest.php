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
namespace nv\semtools\Classifiers\uClassify;

use nv\semtools;

/**
 * uClassify Request
 *
 * Encapsulates uClassify request specifics
 *
 * @package nv\semtools\Classifiers\uClassify
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class UclassifyRequest extends semtools\Common\ApiRequestAbstract
{
    /**
     * The classifier to use for classification
     *
     * @var string
     */
    private $classifier;

    /**
     * Response format
     *
     * @var string
     */
    private $responseFormat;

    /**
     * API version
     *
     * @var string
     */
    private $apiVersion;

    /**
     * Constructor
     *
     * @param string $textData Text to classify
     */
    public function __construct($textData)
    {
        parent::__construct($textData);
        $this->responseFormat = 'xml';
    }

    /**
     * @param $apiVersion
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * @return mixed
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param $classifier
     */
    public function setClassifier($classifier)
    {
        $this->classifier = $classifier;
    }

    /**
     * @return mixed
     */
    public function getClassifier()
    {
        return $this->classifier;
    }

    /**
     * @param $responseFormat
     */
    public function setResponseFormat($responseFormat)
    {
        $this->responseFormat = $responseFormat;
    }

    /**
     * @return mixed
     */
    public function getResponseFormat()
    {
        return $this->responseFormat;
    }
}
