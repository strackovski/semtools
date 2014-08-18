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
use nv\semtools\Factory\SemtoolsFactory;

/*
 *
 * semtools factory example
 *
 * http://www.nv3.org/semtools/api
 *
 */

$content = "";

// Classification
$classifierOptions = array(
    'type' => 'classifier',
    'provider' => 'uClassify',
    'api_key' => 'YOUR_API_KEY',
    'options' => array()
);

$classifier = SemtoolsFactory::create($classifierOptions);
$classifierRequest = new uClassify\UclassifyRequest(
    $content,
    array(
        'prfekt/Myers Briggs Attitude',
        'prfekt/Mood',
        'uclassify/Ageanalyzer'
    )
);
$classifierRequest->setResponseFormat('json');
$classifierResponse = $classifier->read($classifierRequest);

// Get classifier response
$classifierResponse->getResponse();

// Annotation
$annotatorOptions = array(
    'type' => 'annotator',
    'provider' => 'OpenCalais',
    'api_key' => 'YOUR_API_KEY',
    'options' => array()
);

$annotator = SemtoolsFactory::create($annotatorOptions);
$annotatorRequest = new OpenCalais\OpenCalaisRequest($content);
$annotatorRequest->setOutputFormat('xml/rdf');
$annotatorResponse = $annotator->read($annotatorRequest);

// Get annotator response
$annotatorResponse->getResponse();
