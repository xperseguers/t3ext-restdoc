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
 * RealURL auto-configuration.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_restdoc
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class RealurlAutoconf {

	/**
	 * Generates additional RealURL configuration and merges it with provided configuration.
	 *
	 * @param array $params
	 * @param \tx_realurl_autoconfgen $pObj
	 * @return array
	 */
	public function registerDefaultConfiguration(array $params, \tx_realurl_autoconfgen $pObj) {
		return array_merge_recursive($params['config'], array(
			'postVarSets' => array(
				'_DEFAULT' => array(
					'chapter' => array(
						array(
							'GETvar' => 'tx_restdoc_pi1[doc]',
						),
					),
				),
			),
		));
	}

}
