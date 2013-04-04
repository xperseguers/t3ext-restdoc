<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Xavier Perseguers <xavier@causal.ch>
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
 * Utility class.
 *
 * @category    Utility
 * @package     TYPO3
 * @subpackage  tx_restdoc
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 * @deprecated since 1.2.0, will be removed in 1.4.0. Please use Tx_Restdoc_Utility_Helper instead.
 */
final class tx_restdoc_utility {

	/**
	 * Returns Sphinx-related metadata.
	 *
	 * @param string $path
	 * @return array
	 * @deprecated since 1.2.0, will be removed in 1.4.0 - Use Tx_Restdoc_Utility_Helper::getMetadata()
	 */
	public static function getMetadata($path) {
		t3lib_div::logDeprecatedFunction();
		return Tx_Restdoc_Utility_Helper::getMetadata($path);
	}

	/**
	 * Returns a TYPO3-compatible list of menu entries.
	 *
	 * @param array $entries
	 * @return array
	 * @deprecated since 1.2.0, will be removed in 1.4.0 - Use Tx_Restdoc_Utility_Helper::getMenuData()
	 */
	public static function getMenuData(array $entries) {
		t3lib_div::logDeprecatedFunction();
		return Tx_Restdoc_Utility_Helper::getMenuData($entries);
	}

	/**
	 * Converts an XML string to a PHP array - useful to get a serializable value.
	 *
	 * @param string $xmlstr
	 * @return array
	 * @deprecated since 1.2.0, will be removed in 1.4.0 - Use Tx_Restdoc_Utility_Helper::xmlstr_to_array()
	 */
	public static function xmlstr_to_array($xmlstr) {
		t3lib_div::logDeprecatedFunction();
		return Tx_Restdoc_Utility_Helper::xmlstr_to_array($xmlstr);
	}

	/**
	 * Sends a given ReStructuredText document to the browser.
	 * One-way method: will exit program normally at the end.
	 *
	 * @param string $filename
	 * @return void Program will stop after calling this method
	 * @deprecated since 1.2.0, will be removed in 1.4.0 - Use Tx_Restdoc_Utility_Helper::showSources()
	 */
	public static function showSources($filename) {
		t3lib_div::logDeprecatedFunction();
		Tx_Restdoc_Utility_Helper::showSources($filename);
	}

	/**
	 * Converts a relative path to an absolute one.
	 *
	 * @param string $fullPath
	 * @param string $relative
	 * @return string
	 * @deprecated since 1.2.0, will be removed in 1.4.0 - Use Tx_Restdoc_Utility_Helper::relativeToAbsolute()
	 */
	public static function relativeToAbsolute($fullPath, $relative) {
		t3lib_div::logDeprecatedFunction();
		return Tx_Restdoc_Utility_Helper::relativeToAbsolute($fullPath, $relative);
	}

}

?>