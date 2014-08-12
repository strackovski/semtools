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

use nv\semtools;

/**
 * Base ApiRequest Class
 *
 * @package nv\semtools\Common
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
abstract class ApiRequestAbstract implements semtools\Common\RequestInterface
{
    /**
     * The text to analyze
     *
     * @var string
     */
    protected $textData;

    /**
     * Constructor
     *
     * @param string $textData The text to analyze
     */
    public function __construct($textData)
    {
        $this->textData = $textData;
    }

    /**
     * Get text data
     *
     * @return string
     */
    public function getTextData()
    {
        return $this->textData;
    }

    /**
     * Set text data
     *
     * @param $data
     *
     * @return mixed
     */
    public function setTextData($data)
    {
        return $this->textData = $data;
    }
}
