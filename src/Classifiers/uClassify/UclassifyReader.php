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

    /** @var \nv\semtools\Classifiers\uClassify\UclassifyRequest */
    protected $request;

    /**
     * Constructor
     *
     * @param $api_key
     */
    public function __construct($api_key)
    {
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
        $this->apiUpdater->update($this);
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
        $classifier = explode('/', $this->request->getClassifier());

        if (!$this->classifierExists($this->request->getClassifier())) {
            throw new Exception\ServiceReaderException("Classifier {$this->request->getClassifier()} does not exist");
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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $queryURL);
        $response = curl_exec($ch);
        curl_close($ch);

        if (strpos($response, "<Exception>") !== false) {
            preg_match("/<Exception\>(.*)<\/Exception>/mu", $response, $matches);
            throw new Exception\ServiceReaderException($matches[1]);
        }

        return new UclassifyResponse($response);
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
            $classifierArray = explode('/', $classifier);

            if (!array_key_exists($classifierArray[0], $this->classifiers)) {
                throw new \Exception("Classifier namespace {$classifierArray[0]} does not exist in Classifiers list");
            }

            if (!in_array($classifierArray[1], $this->classifiers[$classifierArray[0]])) {
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
