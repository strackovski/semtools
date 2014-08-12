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
 * Class OpenCalaisRequest
 *
 * Encapsulates OpenCalais request specifics
 *
 * @package nv\semtools\Annotators\OpenCalais
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class OpenCalaisRequest extends semtools\Common\ApiRequestAbstract
{
    /**
     * License ID provided by API provider
     *
     * @var string Calais license ID
     */
    private $licenseId;

    /**
     * Format of the input content
     *
     * @var string Content type
     */
    private $contentType;

    /**
     * Format of the returned results
     *
     * @var string Response format
     */
    private $outputFormat;

    /**
     * Indicates whether output will include SocialTags
     *
     * @var bool
     */
    private $enableSocialTags;

    /**
     * Indicates whether output will include Generic Relation extractions
     *
     * @var bool
     */
    private $enableGenericRelations;

    /**
     * Indicates whether entire XML/RDF document is saved in the Calais Linked Data repository
     *
     * @var bool
     */
    private $docRDFaccessible;

    /**
     * Indicates whether the extracted metadata can be distributed
     *
     * @var bool
     */
    private $allowDistribution;

    /**
     * Indicates whether future searches can be performed on the extracted metadata
     *
     * @var bool
     */
    private $allowSearch;

    /**
     * Indicates whether the extracted metadata will include relevance score for each unique entity
     *
     * @var bool Whether to calculate relevance score or not
     */
    private $calculateRelevanceScore;

    /**
     * Constructor
     *
     * @param string $textData
     */
    public function __construct($textData)
    {
        parent::__construct($textData);
        $this->contentType = 'text/raw';
        $this->outputFormat = 'text/simple';
        $this->enableGenericRelations = true;
        $this->enableSocialTags = true;
        $this->calculateRelevanceScore = true;
        $this->docRDFaccessible = false;
        $this->allowDistribution = false;
        $this->allowSearch = false;
    }

    /**
     * Generate XML request string from parameters
     *
     * @return string
     */
    public function generateXMLRequestString()
    {
        $enabledMetadataTypes = array();
        if ($this->enableSocialTags) {
            $enabledMetadataTypes[] = 'GenericRelations';
        }
        if ($this->enableGenericRelations) {
            $enabledMetadataTypes[] = 'SocialTags';
        }

        $xml = '<c:params xmlns:c="http://s.opencalais.com/1/pred/" ';
        $xml .= 'xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">';
        $xml .= '<c:processingDirectives ';
        $xml .= 'c:contentType="' . $this->contentType . '" ';
        $xml .= 'c:enableMetadataType="' . implode(',', $enabledMetadataTypes) . '" ';
        $xml .= 'c:outputFormat="' . $this->outputFormat . '" ';
        $xml .= 'c:docRDFaccessible="' . ($this->docRDFaccessible ? 'true' : 'false') . '" ';
        $xml .= '></c:processingDirectives>';
        $xml .= '<c:userDirectives ';
        $xml .= 'c:allowDistribution="' . ($this->allowDistribution ? 'true' : 'false') . '" ';
        $xml .= 'c:allowSearch="' . ($this->allowSearch ? 'true' : 'false') . '" ';
        $xml .= '></c:userDirectives>';
        $xml .= '<c:externalMetadata></c:externalMetadata>';
        $xml .= '</c:params>';

        return $xml;
    }

    /**
     * Set output format
     *
     * @param string $outputFormat
     *
     * @throws \InvalidArgumentException if provided output format is illegal
     */
    public function setOutputFormat($outputFormat)
    {
        $allowedTypes = array(
            'xml/rdf',
            'text/simple',
            'text/microformats',
            'application/json',
            'text/n3'
        );

        if (!in_array(strtolower($outputFormat), $allowedTypes)) {
            throw new \InvalidArgumentException(
                "Illegal output format, allowed output formats are " . implode(', ', $allowedTypes)
            );
        }

        $this->outputFormat = $outputFormat;
    }

    /**
     * Set request data content type
     *
     * @param string $contentType
     *
     * @throws \InvalidArgumentException if provided content type is illegal
     * @return $this
     */
    public function setContentType($contentType)
    {
        $allowedTypes = array(
            'text/html',
            'text/xml',
            'text/htmlraw',
            'text/raw'
        );

        if (!in_array(strtolower($contentType), $allowedTypes)) {
            throw new \InvalidArgumentException(
                "Illegal content type, allowed content types are " . implode(', ', $allowedTypes)
            );
        }
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get output format
     *
     * @return string
     */
    public function getOutputFormat()
    {
        return $this->outputFormat;
    }

    /**
     * Enable/disable relevance calculation
     *
     * @param boolean $calculateRelevanceScore
     */
    public function setCalculateRelevanceScore($calculateRelevanceScore)
    {
        $this->calculateRelevanceScore = $calculateRelevanceScore;
    }

    /**
     * Get calculate relevance score
     *
     * @return boolean
     */
    public function getCalculateRelevanceScore()
    {
        return $this->calculateRelevanceScore;
    }

    /**
     * Get request data content type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param boolean $enableGenericRelations
     */
    public function setEnableGenericRelations($enableGenericRelations)
    {
        $this->enableGenericRelations = $enableGenericRelations;
    }

    /**
     * @return boolean
     */
    public function getEnableGenericRelations()
    {
        return $this->enableGenericRelations;
    }

    /**
     * @param boolean $enableSocialTags
     */
    public function setEnableSocialTags($enableSocialTags)
    {
        $this->enableSocialTags = $enableSocialTags;
    }

    /**
     * @return boolean
     */
    public function getEnableSocialTags()
    {
        return $this->enableSocialTags;
    }

    /**
     * Set license ID
     *
     * @param string $licenseId
     */
    public function setLicenseId($licenseId)
    {
        $this->licenseId = $licenseId;
    }

    /**
     * Get license ID
     *
     * @return string
     */
    public function getLicenseId()
    {
        return $this->licenseId;
    }

    /**
     * @param boolean $allowDistribution
     */
    public function setAllowDistribution($allowDistribution)
    {
        $this->allowDistribution = $allowDistribution;
    }

    /**
     * @return boolean
     */
    public function getAllowDistribution()
    {
        return $this->allowDistribution;
    }

    /**
     * @param boolean $allowSearch
     */
    public function setAllowSearch($allowSearch)
    {
        $this->allowSearch = $allowSearch;
    }

    /**
     * @return boolean
     */
    public function getAllowSearch()
    {
        return $this->allowSearch;
    }

    /**
     * @param boolean $docRDFaccessible
     */
    public function setDocRDFaccessible($docRDFaccessible)
    {
        $this->docRDFaccessible = $docRDFaccessible;
    }

    /**
     * @return boolean
     */
    public function getDocRDFaccessible()
    {
        return $this->docRDFaccessible;
    }
}
