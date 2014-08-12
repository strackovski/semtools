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

/**
 * Class OpenCalaisResponse
 *
 * Encapsulates OpenCalais response specifics
 *
 * @package nv\semtools\Annotators\OpenCalais
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class OpenCalaisResponse extends semtools\Common\ApiResponseAbstract
{
    /**
     * Parse entities from response data
     *
     * @return array of entities
     */
    public function getEntities()
    {
        $entities = array();
        $lines = explode("\n", $this->response);
        foreach ($lines as $line) {
            if (strpos($line, '-->') === 0) {
                break;
            } elseif (strpos($line, '<!--') !== 0) {
                $parts = explode(':', $line);
                $type = $parts[0];
                $entities = explode(',', $parts[1]);
                foreach ($entities as $entity) {
                    if (strlen(trim($entity)) > 0) {
                        $entities[$type][] = trim($entity);
                    }
                }
            }
        }

        if (strpos($this->response, '<SocialTag ') !== false) {
            preg_match_all('/<SocialTag [^>]*>([^<]*)<originalValue>/', $this->response, $matches);
            if (is_array($matches) && is_array($matches[1]) && count($matches[1]) > 0) {
                foreach ($matches[1] as $tag) {
                    $entities['SocialTag'][] = trim($tag);
                }
            }
        }

        return $entities;
    }
}
