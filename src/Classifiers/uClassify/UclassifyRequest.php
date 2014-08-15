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

use nv\semtools\Common;

/**
 * uClassify Request
 *
 * Encapsulates uClassify request specifics
 *
 * @package nv\semtools\Classifiers\uClassify
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class UclassifyRequest extends Common\ApiRequestAbstract
{
    /**
     * The classifiers to use for classification
     *
     * @var string
     */
    private $classifiers;

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
     * @param array  $classifiers
     */
    public function __construct($textData, array $classifiers)
    {
        parent::__construct($textData);
        $this->responseFormat = 'xml';
        $this->apiVersion = '1.0';
        $this->classifiers = $classifiers;
        // @todo Enable multiple classifiers:
        // classifiers array, foreach through and classify by each entry
    }

    /**
     * Set API version
     *
     * @param $apiVersion
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * Get API version
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * Set classifier
     *
     * @param array $classifiers
     */
    public function setClassifiers(array $classifiers)
    {
        $this->classifiers = $classifiers;
    }

    /**
     * Get classifiers
     *
     * @return array|string
     */
    public function getClassifiers()
    {
        return $this->classifiers;
    }

    /**
     * @param $classifier
     *
     * @return $this
     */
    public function addClassifier($classifier)
    {
        if (! in_array($classifier, $this->classifiers)) {
            $this->classifiers[] = $classifier;
        }

        return $this;
    }

    public function removeClassifier($classifier)
    {
        if (in_array($classifier, $this->classifiers)) {
            unset($this->classifiers[$classifier]);
        }

        return $this;
    }

    /**
     * Set response format
     *
     * @param $responseFormat
     */
    public function setResponseFormat($responseFormat)
    {
        $this->responseFormat = $responseFormat;
    }

    /**
     * Get response format
     *
     * @return mixed
     */
    public function getResponseFormat()
    {
        return $this->responseFormat;
    }
}
