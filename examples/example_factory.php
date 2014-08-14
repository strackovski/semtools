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
 * semtools factory example
 *
 * http://www.nv3.org/semtools/api
 *
 */

$content = 'The preRemove event occurs for a given entity before the respective EntityManager remove operation for that entity is executed. It is not called for a DQL DELETE statement.';

// Classification
$classifierOptions = array(
    'type' => 'classifier',
    'provider' => 'uClassify',
    'api_key' => 'YOUR_API_KEY',
    'options' => array()
);

$classifier = \nv\semtools\Factory\SemtoolsFactory::create($classifierOptions);
$classifierRequest = new uClassify\UclassifyRequest(
    'My happy text',
    'prfekt/Myers Briggs Attitude'
);
$classifierResponse = $classifier->read($classifierRequest);
echo $classifierResponse->getResponse();


// Annotation
$annotatorOptions = array(
    'type' => 'annotator',
    'provider' => 'OpenCalais',
    'api_key' => 'YOUR_API_KEY',
    'options' => array()
);

$annotator = \nv\semtools\Factory\SemtoolsFactory::create($annotatorOptions);
$annotatorRequest = new OpenCalais\OpenCalaisRequest($content);
$annotatorResponse = $annotator->read($annotatorRequest);

// To get the raw response
$annotatorResponse->getResponse();

// To get the response parsed to php array
print_r($annotatorResponse->getEntities());
