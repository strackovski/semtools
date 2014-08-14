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
 * uClassify Response
 *
 * Encapsulates uClassify response specifics
 *
 * @package nv\semtools\Classifiers\uClassify
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class UclassifyResponse extends semtools\Common\ApiResponseAbstract
{
    /**
     * Get classification data as PHP array
     *
     * @return array
     * @throws \nv\semtools\Exception\ServiceReaderException
     */
    public function getClassification()
    {
        $xml = simplexml_load_string($this->response);
        $result = array();

        if ($xml instanceof \SimpleXMLElement) {
            if ((string) $xml->status->attributes()->success == 'true') {
                foreach ($xml->readCalls->classify as $classify) {
                    foreach ($classify->classification->class as $class) {
                        $result[strtolower($class->attributes()->className)] = (float) $class->attributes()->p;
                    }
                }
                return $result;
            }
            throw new semtools\Exception\ServiceReaderException($xml->status->attributes()->statusCode);
        }
        throw new semtools\Exception\ServiceReaderException('Failed parsing XML response.');
    }
}
