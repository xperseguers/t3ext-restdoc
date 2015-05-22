<?php
namespace Causal\Restdoc\Hook;

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

/**
 * RealURL auto-configuration and segment decoder.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_restdoc
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class Realurl {

	/**
	 * Generates additional RealURL configuration and merges it with provided configuration.
	 *
	 * @param array $params
	 * @param \tx_realurl_autoconfgen $pObj
	 * @return array
	 */
	public function registerDefaultConfiguration(array $params, \tx_realurl_autoconfgen $pObj) {
		$fixedPostVarsConfiguration = array();

		$settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['restdoc']);
		if (isset($settings['enable_slash_as_separator']) && (bool)$settings['enable_slash_as_separator']) {
			$fixedPostVarsConfiguration = $this->getFixedPostVarsConfiguration();
		}

		$defaultConfiguration = array_merge_recursive(
			$params['config'],
			$fixedPostVarsConfiguration,
			array(
				'postVarSets' => array(
					'_DEFAULT' => array(
						'chapter' => array(
							array(
								'GETvar' => 'tx_restdoc_pi1[doc]',
							),
						),
					),
				),
			)
		);

		return $defaultConfiguration;
	}

	/**
	 * This methods will "eat" every remaining segment in the URL to make it part
	 * of the requested document.
	 *
	 * @param array $parameters
	 * @return void
	 */
	public function decodeSpURL_getSequence(array $parameters) {
		$value = $parameters['value'];

		if ((bool)$parameters['decodeAlias']) {
			if (!empty($parameters['pathParts'])) {
				// Eat every remaining segment
				$value .= '/' . implode('/', $parameters['pathParts']);
				$parameters['pathParts'] = array();
			}
		}

		return $value;
	}

	/**
	 * Generates a default "fixedPostVars" configuration for RealURL
	 * based on leaf pages containing a restdoc plugin.
	 *
	 * @return array
	 */
	protected function getFixedPostVarsConfiguration() {
		$fixedPostVarsConfiguration = array();

		// Search (leaf) pages with a restdoc plugin
		$databaseConnection = $this->getDatabaseConnection();
		$pages = $databaseConnection->exec_SELECTgetRows(
			'DISTINCT pid',
			'tt_content',
			'list_type=' . $databaseConnection->fullQuoteStr('restdoc_pi1', 'tt_content') .
			' AND deleted=0 AND hidden=0',
			'',
			'',
			'',
			'pid'
		);
		$pages = array_keys($pages);

		if (!empty($pages)) {
			$leafPages = $databaseConnection->exec_SELECTgetRows(
				'uid',
				'pages',
				'uid IN (' . implode(',', $pages) . ')' .
					' AND deleted=0 AND hidden=0' .
					' AND uid NOT IN (' .
						$databaseConnection->SELECTquery('pid', 'pages', 'deleted=0 AND hidden=0') .
					')',
				'',
				'',
				'',
				'uid'
			);
			$leafPages = array_keys($leafPages);

			if (!empty($leafPages)) {
				$fixedPostVarsConfiguration['fixedPostVars'] = array_fill_keys($leafPages, 'restdoc_advanced_url');
				$fixedPostVarsConfiguration['fixedPostVars']['restdoc_advanced_url'] = array(
					array(
						'GETvar' => 'tx_restdoc_pi1[doc]',
						'userFunc' => 'Causal\\Restdoc\\Hook\\Realurl->decodeSpURL_getSequence',
					),
				);
			}
		}

		return $fixedPostVarsConfiguration;
	}

	/**
	 * Returns the database connection.
	 *
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

}
