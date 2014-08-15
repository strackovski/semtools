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
use nv\semtools\Common\ApiResponseAbstract;
use nv\semtools\Exception\ServiceReaderException;

/**
 * uClassify Response
 *
 * Encapsulates uClassify response specifics
 *
 * @package nv\semtools\Classifiers\uClassify
 * @author Vladimir Stračkovski <vlado@nv3.org>
 * @todo Refactor to Interpreter classes
 */
class UclassifyResponse extends ApiResponseAbstract
{
    /**
     * Iterate through response data and aggregate each classifier's response
     *
     * @return mixed|string
     * @throws \nv\semtools\Exception\ServiceReaderException
     */
    protected function init()
    {
        if ($this->request->getResponseFormat() == 'xml') {
            $aggregated = new \SimpleXMLElement('<response/>');
            foreach ($this->responseRaw as $name => $response) {
                $child = $aggregated->addChild('classification');
                $child->addAttribute('classifier', $name);
                $xml = simplexml_load_string($response);

                if (! $xml instanceof \SimpleXMLElement) {
                    throw new ServiceReaderException('Failed parsing XML response.');
                }

                if ((string) $xml->status->attributes()->success !== 'true') {
                    throw new ServiceReaderException($xml->status->attributes()->statusCode);
                }

                foreach ($xml->readCalls->classify as $classify) {
                    $child->addAttribute('textCoverage', $classify->classification->attributes()->textCoverage);
                    foreach ($classify->classification->class as $class) {
                        $this->simplexmlImportXml($child, $class->asXML());
                    }
                }
            }

            return $aggregated->asXML();
        }

        if ($this->request->getResponseFormat() == 'json') {
            $aggregated = array();
            foreach ($this->responseRaw as $name => $response) {
                $current = json_decode($response, 1);

                if (! array_key_exists('success', $current)) {
                    throw new ServiceReaderException('Unable to complete request');
                }

                if ($current['success'] !== true) {
                    throw new ServiceReaderException($current['statusCode'] . ' ' . $current['errorMessage']);
                }

                array_key_exists('textCoverage', $current) ? '' : $current['textCoverage'] = null;
                array_key_exists('cls1', $current) ? '' : $current['cls1'] = null;

                $aggregated[] = array(
                    'classifier' => $name,
                    'textCoverage' => $current['textCoverage'],
                    'classes' => $current['cls1']
                );
            }

            return json_encode($aggregated);
        }

        return $this->responseRaw;
    }
}
