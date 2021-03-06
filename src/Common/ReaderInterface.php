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

namespace nv\semtools\Common;

/**
 * Reder Interface
 *
 * Contract for reader classes
 *
 * @package nv\semtools\Common
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
interface ReaderInterface
{
    /**
     * Execute API call as defined in the request object
     *
     * @param RequestInterface $request Request parameters
     *
     * @return mixed
     */
    public function read(RequestInterface $request);
}
