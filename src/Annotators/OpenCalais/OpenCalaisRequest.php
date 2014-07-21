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

use \nv\semtools;

/**
 * Class OpenCalaisReader
 *
 * @package nv\semtools
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class OpenCalaisRequest extends semtools\ApiRequest
{
    /** @var string Calais license ID */
    private $licenseId;

    /** @var string Content type */
    private $contentType;

    /** @var string Response format */
    private $acceptFormat;

    /** @var string The type of metadata to enable */
    private $enableMetadataType;

    /** @var bool Whether to calculate relevance score or not */
    private $calculateRelevanceScore;

    public function __construct($textData)
    {
        parent::__construct($textData);
        $this->contentType = 'text/raw';
        $this->acceptFormat = 'xml/rdf';
        $this->enableMetadataType = 'GenericRelations';
        $this->calculateRelevanceScore = true;
    }

    /**
     * @param string $acceptFormat
     */
    public function setAcceptFormat($acceptFormat)
    {
        $this->acceptFormat = $acceptFormat;
    }

    /**
     * @return string
     */
    public function getAcceptFormat()
    {
        return $this->acceptFormat;
    }

    /**
     * @param boolean $calculateRelevanceScore
     */
    public function setCalculateRelevanceScore($calculateRelevanceScore)
    {
        $this->calculateRelevanceScore = $calculateRelevanceScore;
    }

    /**
     * @return boolean
     */
    public function getCalculateRelevanceScore()
    {
        return $this->calculateRelevanceScore;
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $enableMetadataType
     */
    public function setEnableMetadataType($enableMetadataType)
    {
        $this->enableMetadataType = $enableMetadataType;
    }

    /**
     * @return string
     */
    public function getEnableMetadataType()
    {
        return $this->enableMetadataType;
    }

    /**
     * @param string $licenseId
     */
    public function setLicenseId($licenseId)
    {
        $this->licenseId = $licenseId;
    }

    /**
     * @return string
     */
    public function getLicenseId()
    {
        return $this->licenseId;
    }
}
