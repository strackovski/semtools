semtools: toolbox for content classification and enrichment
======================================================================

Provides read access to public semantic API services. Version 0.0.3
supports classification using the uClassify.com service and annotation
(enrichment) using Reuters OpenCalais API.

Features
--------

* uClassify API reader enables use of all available public classifiers.
* OpenCalais API reader enables use of the powerful enrichment service from Reuters.

How-to & Examples
------------------

Below is a basic example, also available in examples/index.php file. For detailed options,
please see the [API documentation](www.nv3.org/semtools/api).

### Classification

```PHP
use nv\semtools\Classifiers\uClassify;

header('Content-type: text/xml');

// Instantiate the reader with your API key (provided by uClassify)
$classifier = new uClassify\UclassifyReader('0wYctA3XvxGfiH8xpFjigyPHkNs');

// Create a new request with the text to be classified and the classifier to use
$classifierRequest = new uClassify\UclassifyRequest(
    'My happy text',
    'prfekt/Myers Briggs Attitude'
);

// Execute and return the request
$classifierResponse = $classifier->read($classifierRequest);
echo $classifierResponse->getResponse();
```

Prints:
```XML
<?xml version="1.0" encoding="UTF-8" ?>
<uclassify xmlns="http://api.uclassify.com/1/ResponseSchema" version="1.01">
	<status success="true" statusCode="2000"/>
	<readCalls>
	<classify id="cls1">
		<classification textCoverage="1">
			<class className="Extraversion" p="0.99998"/>
			<class className="Introversion" p="2.0456e-005"/>
		</classification>
	</classify>
	</readCalls>
</uclassify>
```

### Annotation

```PHP
use nv\semtools\Annotators\OpenCalais;

// The content to process
$content = '';
// Instantiate a new annotator instance with your API key (provided by OpenCalais)
$annotator = new OpenCalais\OpenCalaisReader('asubyt3ptak743yc8jq4hfn7');

// Create a new request with passing the content as parameter
$annotatorRequest = new OpenCalais\OpenCalaisRequest($content);

// Execute request, recieve response
$annotatorResponse = $annotator->read($annotatorRequest);

// To get the raw response
$annotatorResponse->getResponse();

// To get the response parsed to php array
print_r($annotatorResponse->getEntities());
```

Prints:
```PHP
...
```

Check out [OpenCalais homepage](www.opencalais.com) for details on different response formats.

License
----------
Copyright 2014 Vladimir Stračkovski. MIT license. For more information please see
the license file included in this project.



