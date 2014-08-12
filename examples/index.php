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
include dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use nv\semtools\Classifiers\uClassify;
use nv\semtools\Annotators\OpenCalais;

/*
 *
 * semtools basic example
 *
 * http://www.nv3.org/semtools/api
 *
 */

$content = '';

// Instantiate the reader with your API key (provided by uClassify)
$classifier = new uClassify\UclassifyReader('YOUR_API_KEY');

// Create a new request with the text to be classified and the classifier to use
$classifierRequest = new uClassify\UclassifyRequest(
    'My happy text',
    'prfekt/Myers Briggs Attitude'
);

// Execute and return the request
$classifierResponse = $classifier->read($classifierRequest);
echo $classifierResponse->getResponse();

// Annotation
$annotator = new OpenCalais\OpenCalaisReader('YOUR_API_KEY');
$annotatorRequest = new OpenCalais\OpenCalaisRequest($content);
$annotatorResponse = $annotator->read($annotatorRequest);

// To get the raw response
$annotatorResponse->getResponse();

// To get the response parsed to php array
print_r($annotatorResponse->getEntities());
