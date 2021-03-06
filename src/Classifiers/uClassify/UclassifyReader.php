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

use nv\semtools\Common\ApiReaderAbstract;
use nv\semtools\Common\RequestInterface;
use nv\semtools\Classifiers\uClassify;
use nv\semtools\Exception;

/**
 * Class UclassifyReader
 *
 * A wrapper for the uClassify API enables text classification using
 * any of the available classifiers provided on uClassifiy.com.
 *
 * @link http://www.uclassify.com/browse
 * @package nv\semtools\Classifiers\uClassify
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class UclassifyReader extends ApiReaderAbstract
{
    /**
     * Version of the API to use
     *
     * @var string
     */
    protected $apiVersion;

    /**
     * List of valid classifiers and their namespaces
     *
     * @var array
     */
    protected $classifiers;

    /**
     * Keeps list of classifiers in sync with provider
     *
     * @var \nv\semtools\Classifiers\uClassify\DataUpdater
     */
    protected $apiUpdater;

    /**
     * Request
     *
     * @var \nv\semtools\Classifiers\uClassify\UclassifyRequest
     */
    protected $request;

    /**
     * Debug mode enables auto-update, so construction may take longer to finish
     *
     * @var bool
     */
    protected $debug;

    /**
     * Constructor
     *
     * @param string $api_key  Your API service key
     * @param bool   $debug    Debug mode
     */
    public function __construct($api_key, $debug = false)
    {
        $this->debug = $debug;
        $this->apiKey = $api_key;
        $this->apiVersion = '1.01';
        $this->apiQueryStringRequestFormat = '%s%s/%s/ClassifyText?readkey=%s&text=%s&version=%s%s';
        $this->apiEndpoint = 'http://uclassify.com/browse/';
        $this->apiUpdater = new uClassify\DataUpdater();

        $this->classifiers = array(
            'uClassify' => array(
                'Sentiment',
                'Text Language',
                'Topics',
                'Ageanalyzer',
                'Genderanalyzer'
            ),
            'prfekt' => array(
                'Mood',
                'Myers Briggs Judging Function',
                'Myers Briggs Attitude',
                'Myers Briggs Lifestyle',
                'Myers Briggs Judging Percieving Function',
                'Tonality'
            )
        );

        if ($this->debug) {
            $this->apiUpdater->update($this);
        }
    }

    /**
     * Read API
     *
     * @param \nv\semtools\Common\RequestInterface $request
     *
     * @return uClassify\UclassifyResponse
     * @throws \InvalidArgumentException
     */
    public function read(RequestInterface $request)
    {
        if (!$request instanceof UclassifyRequest) {
            throw new \InvalidArgumentException("Expected instance of UclassifyRequest, got ".get_class($request));
        }
        $this->request = $request;

        return $this->executeRequest();
    }

    /**
     * Execute read request
     *
     * @throws Exception\ServiceReaderException
     * @return mixed|UclassifyResponse
     */
    protected function executeRequest()
    {
        // @todo encode response add to array, pass to response

        $result = array();

        foreach ($this->request->getClassifiers() as $classifierString) {
            $classifier = explode('/', $classifierString);

            if (! $this->classifierExists($classifierString)) {
                throw new Exception\ServiceReaderException("Classifier {$classifierString} does not exist");
            }

            $queryURL = sprintf(
                $this->apiQueryStringRequestFormat,
                $this->apiEndpoint,
                rawurlencode($classifier[0]),
                rawurlencode($classifier[1]),
                urlencode($this->apiKey),
                urlencode($this->request->getTextData()),
                $this->apiVersion,
                strtolower($this->request->getResponseFormat()) !== 'xml' ?
                "&output={$this->request->getResponseFormat()}" : null
            );

            if (!is_callable('curl_init')) {
                throw new Exception\ServiceReaderException('cURL not found.');
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $queryURL);
            $response = curl_exec($ch);
            curl_close($ch);

            if (strpos($response, "<Exception>") !== false) {
                preg_match("/<Exception\>(.*)<\/Exception>/mu", $response, $matches);
                throw new Exception\ServiceReaderException($matches[1]);
            }

            $result[$classifier[1]] = $response;
        }
        return new UclassifyResponse($result, $this->request);
    }

    /**
     * Verify if the requested classifier exists
     *
     * @param string $classifier Classifier name, including provider namespace: namespace/classifier
     *
     * @return array|bool Array with namespace and classifier if it exists, false if it doesn't
     * @throws \Exception
     */
    private function classifierExists($classifier)
    {
        if (strpos($classifier, '/') !== false) {
            $classifierArray = array_map('strtolower', explode('/', $classifier));
            $testArray = array_change_key_case($this->classifiers);

            // Remap classifiers list to lowercase before validation
            foreach ($testArray as $key => $values) {
                $testArray [$key] = array_map('strtolower', $values);
            }

            if (!array_key_exists($classifierArray[0], $testArray)) {
                throw new \Exception("Classifier namespace {$classifierArray[0]} does not exist in Classifiers list");
            }

            if (!in_array($classifierArray[1], $testArray[$classifierArray[0]])) {
                throw new \Exception("Classifier {$classifierArray[1]} does not exist in Classifiers list");
            }

            return array($classifierArray[0], $classifierArray[1]);
        }

        return false;
    }

    /**
     * Return all registered classifiers
     *
     * @return array Array of classifiers
     */
    public function getClassifiers()
    {
        return $this->classifiers;
    }

    /**
     * Update classifiers
     *
     * @param array $classifiers
     *
     * @return bool
     */
    public function setClassifiers(array $classifiers)
    {
        $this->classifiers = $classifiers;
        return $this->classifiers === $classifiers ? true : false;
    }
}
