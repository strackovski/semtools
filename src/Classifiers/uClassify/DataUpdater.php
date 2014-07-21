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

use nv\semtools\Classifiers\uClassify\UclassifyReader;

/**
 * Class DataUpdater
 *
 * Provides classifier-list update capability for uClassify readers.
 *
 * @package nv\semtools\Classifiers\uClassify
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class DataUpdater
{
    /**
     * Compare classifier list to source list at http://www.uclassify.com/browse and update if necessary.
     *
     * @param UclassifyReader $reader
     *
     * @throws \Exception
     * @internal param $ nv\semtools\Classifiers\uClassify\UclassifyReader; $reader
     * @return array Array of classifiers
     */
    public function update(UclassifyReader $reader)
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadHTML(file_get_contents('http://www.uclassify.com/browse'));

        $xp = new \DOMXPath($dom);
        $className = 'classifierInfo';
        $classifiers = $xp->query("//*[contains(@class, '$className')]");
        $results = array();

        foreach ($classifiers as $classifier) {
            $divs = $classifier->getElementsByTagName('div');

            if (!$divs instanceof \DOMNodeList) {
                throw new \Exception('Expected DOMNodeList');
            }

            foreach ($divs as $div) {
                if ($div instanceof \DOMElement) {
                    if ($div->getAttribute('class') === 'classifierInfoName') {
                        $classifierNS = $div->getElementsByTagName('div')->length >= 1 ?
                            $div->getElementsByTagName('div')
                                ->item(1)
                                ->getElementsByTagName('a')
                                ->item(0)
                                ->nodeValue
                            : false;

                        $classifierName = $div->getElementsByTagName('h3')->length == 1 ?
                            $div->getElementsByTagName('h3')
                                ->item(0)
                                ->nodeValue
                            : false;

                        if (strlen($classifierNS) > 0 and strlen($classifierName) > 0) {
                            array_key_exists($classifierNS, $results) ?
                                $results[$classifierNS][] = $classifierName :
                                $results[$classifierNS] = array($classifierName);
                        }
                    }
                }
            }
        }

        return $results === $reader->getClassifiers() ? $results : $reader->setClassifiers($results);
    }
}
