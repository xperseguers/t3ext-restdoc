<?php
namespace Causal\Restdoc\Controller\Pi1;

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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class that adds the wizard icon.
 *
 * @category    Plugin
 * @package     TYPO3
 * @subpackage  tx_restdoc
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class WizardIcon {

	/**
	 * Processes the wizard items array.
	 *
	 * @param array $wizardItems The wizard items
	 * @return array Modified array with wizard items
	 */
	public function proc(array $wizardItems) {
		$LL = $this->includeLocalLang();

		$wizardItems['plugins_tx_restdoc_pi1'] = array(
			'icon'        => ExtensionManagementUtility::extRelPath('restdoc') . 'Resources/Public/Icons/pi1_ce_wizard.png',
			'title'       => $GLOBALS['LANG']->getLLL('pi1_title', $LL),
			'description' => $GLOBALS['LANG']->getLLL('pi1_plus_wiz_description', $LL),
			'params'      => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=restdoc_pi1'
		);

		return $wizardItems;
	}

	/**
	 * Reads the extension locallang.xml and returns the $LOCAL_LANG array found in that file.
	 *
	 * @return array The array with language labels
	 */
	protected function includeLocalLang() {
		$llFile = ExtensionManagementUtility::extPath('restdoc') . 'Resources/Private/Language/locallang.xlf';
		return $GLOBALS['LANG']->includeLLFile($llFile, FALSE);
	}

}
