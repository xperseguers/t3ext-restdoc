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
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

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

    /**
     * @var ContentObjectRenderer
     */
    protected $cObj;

    /**
     * Rendering the cObject, REST_METADATA.
     *
     * @param string $name name of the cObject ('REST_METADATA')
     * @param array $conf array of TypoScript properties
     * @param string $TSkey TS key set to this cObject
     * @param ContentObjectRenderer $pObj
     * @return string
     */
    public function cObjGetSingleExt(string $name, array $conf, string $TSkey, ContentObjectRenderer $pObj): string
    {
        $this->cObj = $pObj;
        $this->applyStdWrap($conf, 'path');

        // TODO: Add support for FAL

        $output = '';
        $typo3Branch = class_exists(\TYPO3\CMS\Core\Information\Typo3Version::class)
            ? (new \TYPO3\CMS\Core\Information\Typo3Version())->getBranch()
            : TYPO3_branch;
        $pathSite = version_compare($typo3Branch, '9.0', '<')
            ? PATH_site
            : Environment::getPublicPath() . '/';
        $data = RestHelper::getMetadata($pathSite . $conf['path']);
        if ($data) {
            /** @var ContentObjectRenderer $contentObj */
            $contentObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
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
     */
    protected function applyStdWrap(array &$conf, string $baseKey): void
    {
        if (isset($conf[$baseKey . '.'])) {
            $conf[$baseKey] = $this->cObj->stdWrap($conf[$baseKey], $conf[$baseKey . '.']);
            unset($conf[$baseKey . '.']);
        }
    }

}
