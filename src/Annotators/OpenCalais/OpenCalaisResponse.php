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
        // @todo Extend this class for: OpenCalaisRdfResponse, OpenCalaisMicroformatsResponse, ...
        return $this->responseRaw;
    }
}
