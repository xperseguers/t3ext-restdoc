<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with TYPO3 source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Causal\Restdoc\ContentObject;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Causal\Restdoc\Utility\RestHelper;

/**
 * Implementation of the REST_METADATA content object.
 *
 * @category    Content Objects
 * @package     TYPO3
 * @subpackage  tx_restdoc
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class RestMetadataContentObject
{

    /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
    protected $cObj;

    /**
     * Rendering the cObject, REST_METADATA.
     *
     * @param string $name name of the cObject ('REST_METADATA')
     * @param array $conf array of TypoScript properties
     * @param string $TSkey TS key set to this cObject
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $pObj
     * @return string
     */
    public function cObjGetSingleExt($name, array $conf, $TSkey, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $pObj)
    {
        $this->cObj = $pObj;
        $this->applyStdWrap($conf, 'path');

        // TODO: Add support for FAL

        $output = '';
        $pathSite = version_compare(TYPO3_version, '9.0', '<')
            ? PATH_site
            : Environment::getPublicPath();
        $data = RestHelper::getMetadata($pathSite . $conf['path']);
        if ($data) {
            /** @var $contentObj \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
            $contentObj = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
            $contentObj->start($data);
            $output = $contentObj->stdWrap('', $conf);
        }

        return $output;
    }

    /**
     * Applies stdWrap to a given key in a configuration array.
     *
     * @param array &$conf
     * @param string $baseKey
     * @return void
     */
    protected function applyStdWrap(array &$conf, $baseKey)
    {
        if (isset($conf[$baseKey . '.'])) {
            $conf[$baseKey] = $this->cObj->stdWrap($conf[$baseKey], $conf[$baseKey . '.']);
            unset($conf[$baseKey . '.']);
        }
    }

}
