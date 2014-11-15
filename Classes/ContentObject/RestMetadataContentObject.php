<?php
namespace Causal\Restdoc\ContentObject;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2014 Xavier Perseguers <xavier@causal.ch>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

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
class RestMetadataContentObject {

	/** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
	protected $cObj;

	/**
	 * Rendering the cObject, REST_METADATA.
	 *
	 * @param string $name: name of the cObject ('REST_METADATA')
	 * @param array $conf: array of TypoScript properties
	 * @param string $TSkey: TS key set to this cObject
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $pObj
	 * @return string
	 */
	public function cObjGetSingleExt($name, array $conf, $TSkey, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $pObj) {
		$this->cObj = $pObj;
		$this->applyStdWrap($conf, 'path');

		// TODO: Add support for FAL

		$output = '';
		$data = RestHelper::getMetadata(PATH_site . $conf['path']);
		if ($data) {
			/** @var $contentObj \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
			$contentObj = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
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
	protected function applyStdWrap(array &$conf, $baseKey) {
		if (isset($conf[$baseKey . '.'])) {
			$conf[$baseKey] = $this->cObj->stdWrap($conf[$baseKey], $conf[$baseKey . '.']);
			unset($conf[$baseKey . '.']);
		}
	}

}
