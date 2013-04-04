<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Xavier Perseguers <xavier@causal.ch>
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

/**
 * RealURL auto-configuration.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_restdoc
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_Restdoc_Hook_RealurlAutoconf {

	/**
	 * Generates additional RealURL configuration and merges it with provided configuration.
	 *
	 * @param array $params
	 * @param tx_realurl_autoconfgen $pObj
	 * @return array
	 */
	public function registerDefaultConfiguration(array $params, tx_realurl_autoconfgen $pObj) {
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

?>