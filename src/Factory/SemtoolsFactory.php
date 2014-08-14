<?php

namespace nv\semtools\Factory;

use \nv\semtools\Classifiers;
use \nv\semtools\Annotators;

/**
 * Class Semtools Factory
 *
 * Provides a static method to help with instantiating Reader objects.
 *
 * @package nv\semtools\Factory
 * @author Vladimir Strackovski <vlado@nv3.org>
 */
class SemtoolsFactory
{
    /**
     * Instantiate reader with predefined options
     *
     * @param array $options
     *         $options = array(
     *              'type' => 'classifier/annotator',
     *              'provider' => 'opencalais', // optional
     *              'api_key' => '',
     *              'options' => array() // optional
     *         );
     *
     * @throws \InvalidArgumentException
     * @return Annotators\OpenCalais\OpenCalaisReader|Classifiers\uClassify\UclassifyReader
     */
    public static function create(array $options)
    {
        // Default classifier and annotator classes
        $defaultClassifier = 'nv\semtools\Classifiers\uClassify\UclassifyReader';
        $defaultAnnotator = 'nv\semtools\Annotators\OpenCalais\OpenCalaisReader';

        // Currently supported types and providers
        $supportedProviders = array(
            'classifier' => array('uclassify'),
            'annotator' => array('opencalais')
        );

        if (! array_key_exists('type', $options)) {
            throw new \InvalidArgumentException(
                "Missing type: currently supported types are 'annotator' or 'classifier'"
            );
        }

        $options['type'] = strtolower($options['type']);

        if (! array_key_exists($options['type'], $supportedProviders)) {
            throw new \InvalidArgumentException("Invalid type {$options['type']}.");
        }

        // Return default provider object if provider not specified in options
        if (! array_key_exists('provider', $options)) {
            if ($options['type'] === 'classifier') {
                return new $defaultClassifier($options['api_key']);
            } elseif ($options['type'] === 'annotator') {
                return new $defaultAnnotator($options['api_key']);
            }
        } else {
            $options['provider'] = strtolower($options['provider']);

            if (! in_array($options['provider'], $supportedProviders[$options['type']])) {
                throw new \InvalidArgumentException(
                    "Provider '{$options['provider']}' invalid or not supported for type '{$options['type']}'"
                );
            }

            // Determine type and provider
            if ($options['type'] === 'classifier') {
                switch ($options['provider']) {
                    case 'uclassify':
                        return new Classifiers\uClassify\UclassifyReader($options['api_key']);
                    break;

                    default:
                        throw new \InvalidArgumentException(
                            'Invalid provider: currently supported classification providers are ' .
                            implode(', ', $supportedProviders['classifier'])
                        );
                    break;
                }
            } elseif ($options['type'] === 'annotator') {
                switch ($options['provider']) {
                    case 'opencalais':
                        return new Annotators\OpenCalais\OpenCalaisReader($options['api_key']);
                    break;

                    default:
                        throw new \InvalidArgumentException(
                            'Invalid provider: currently supported annotation providers are ' .
                            implode(', ', $supportedProviders['annotator'])
                        );
                    break;
                }
            }
        }
        throw new \InvalidArgumentException("Invalid parameters.");
    }
}
