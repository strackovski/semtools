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
namespace nv\semtools\Extractors\MediaWiki;

use \nv\semtools;

/**
 * Class WikipediaReader
 *
 * A simple wrapper for the MediaWiki API enables basic content retrieval
 * from Wikipedia articles.
 *
 * @package nv\semtools\Extractors\MediaWiki
 * @author Vladimir Stračkovski <vlado@nv3.org>
 * @todo Array generation
 */
class WikipediaReader extends semtools\ApiReader
{
    protected $apiUrl = 'http://en.wikipedia.org/w/api.php?';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = 'http://en.wikipedia.org/w/api.php?';

    }

    public function buildRequest($text, array $options, $responseFormat)
    {

    }

    public function extract($val)
    {
        $wikiQry = "action=query&prop=extracts&format=json&exlimit=1&titles={$val}";

        $ch = curl_init(); // create curl resource
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $wikiQry); // set url
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string
        $output = curl_exec($ch); // $output contains the output string
        curl_close($ch); // close curl resource to free up system resources

        $html = null;
        $j = json_decode($output, 1);
        foreach ($j['query']['pages'] as $page) {
            $html = $page['extract'];
        };

        $doc = new \DOMDocument();
        $doc->loadHTML($html);

        // create document fragment - creates empty DOMNode
        $frag = $doc->createDocumentFragment();
        // create initial list - inserts OL to empty DOMNode (frag)
        $frag->appendChild($doc->createElement('ol'));

        // The first child of this node. If there is no such node, this returns NULL. HEAD JE OL
        $head = &$frag->firstChild;

        $result = [];
        //aktualenArray = result;

        $xpath = new \DOMXPath($doc);
        $last = 1;

        // get all H1, H2, …, H6 elements
        foreach ($xpath->query('//*[self::h2 or self::h3 or self::h4 or self::h5 or self::h6]') as $headline) {
            // get level of current headline
            sscanf($headline->tagName, 'h%u', $curr);

            // move head reference if necessary
            if ($curr < $last) {
                // move upwards
                for ($i=$curr; $i<$last; $i++) {
                    $head = &$head->parentNode->parentNode;
                }
                // check if $result has array
            } elseif ($curr > $last && $head->lastChild) { //if aktualenArray is not empty
                // move downwards, create new lists
                for ($i=$last; $i<$curr; $i++) {
                    //aktualenArray[aktualenArray[count]-1] ->
                    $head->lastChild->appendChild($doc->createElement('ol'));
                    $head = &$head->lastChild->lastChild;
                }
            }
            $last = $curr;

            // add list item
            $li = $doc->createElement('li', $headline->nodeValue);
            $head->appendChild($li);

            // add array item
            $result[] = array($curr => $headline->nodeValue);


        }

        // append fragment to document
        $doc->getElementsByTagName('body')->item(0)->appendChild($frag);

        // echo markup
        // echo $doc->saveHTML();

        echo '<pre>';

        print_r($result);

        echo '</pre>';

    }
}
