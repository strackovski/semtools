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

namespace nv\semtools\Annotators\OpenCalais;

use nv\semtools;
use nv\semtools\Exception\ServiceReaderException;

/**
 * Class OpenCalaisResponse
 *
 * Encapsulates OpenCalais response specifics
 *
 * @package nv\semtools\Annotators\OpenCalais
 * @author Vladimir Stračkovski <vlado@nv3.org>
 * @todo Refactor to Interpreter classes
 */
class OpenCalaisResponse extends semtools\Common\ApiResponseAbstract
{
    /**
     * Get entities from response data according to response format
     *
     * @throws \nv\semtools\Exception\ServiceReaderException
     * @return array of entities
     */
    protected function init()
    {
        $result = array();

        switch (strtolower($this->request->getResponseFormat())) {
            case 'xml/rdf':
                break;

            case 'text/microformats':
                break;

            case 'application/json':
                // @todo Parse array !
                $result = json_decode($this->responseRaw, 1);
                if (array_key_exists('doc', $result)) {
                    unset($result['doc']);
                }
                // $result = json_encode($this->responseRaw);
                break;

            case 'text/simple':
                $lines = explode("\n", $this->responseRaw);
                foreach ($lines as $line) {
                    if (strpos($line, '-->') === 0) {
                        break;
                    } elseif (strpos($line, '<!--') !== 0) {
                        $parts = explode(':', $line);
                        $type = $parts[0];
                        if (count($parts) > 1) {
                            $result = explode(',', $parts[1]);
                            foreach ($result as $entity) {
                                if (strlen(trim($entity)) > 0) {
                                    $result[$type][] = trim($entity);
                                }
                            }
                        }
                    }
                }

                if (strpos($this->response, '<SocialTag ') !== false) {
                    preg_match_all('/<SocialTag [^>]*>([^<]*)<originalValue>/', $this->response, $matches);
                    if (is_array($matches) && is_array($matches[1]) && count($matches[1]) > 0) {
                        foreach ($matches[1] as $tag) {
                            $result['SocialTag'][] = trim($tag);
                        }
                    }
                }
                break;

            case 'text/n3':
                break;

            default:
                throw new ServiceReaderException('Unsupported response type.');
                break;
        }

        return $result;
    }
}
