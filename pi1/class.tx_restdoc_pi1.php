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
 * Plugin 'reST Documentation Viewer' for the 'restdoc' extension.
 *
 * @category    Plugin
 * @package     TYPO3
 * @subpackage  tx_restdoc
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_restdoc_pi1 extends tslib_pibase {
	public $prefixId      = 'tx_restdoc_pi1';
	public $scriptRelPath = 'pi1/class.tx_restdoc_pi1.php';
	public $extKey        = 'restdoc';
	public $pi_checkCHash = TRUE;

	/**
	 * The main method of the Plugin.
	 *
	 * @param string $foo The plugin content
	 * @param array $conf The plugin configuration
	 * @return string The content that is displayed on the website
	 */
	public function main($foo, array $conf) {
		$this->init($conf);
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_USER_INT_obj = 1;    // USER_INT object

		$documentRoot = PATH_site . rtrim($this->conf['path'], '/') . '/';
		$document = 'index/';
		if (isset($this->piVars['doc']) && strpos($this->piVars['doc'], '..') === FALSE) {
			// TODO: Add further security checks here!
			$document = $this->piVars['doc'];
		}

		$jsonFile = substr($document, 0, strlen($document) - 1) . '.fjson';
		if (!is_file($documentRoot . $jsonFile)) {
			$jsonFile = 'index.fjson';
		}
		if (!is_file($documentRoot . $jsonFile)) {
			return 'Invalid path for the reST documentation: ' . $this->conf['path'];
		}

		$content = file_get_contents($documentRoot . $jsonFile);
		$content = json_decode($content, TRUE);

		if (isset($content['genindexentries'])) {
			return 'TODO: generate index';
		}

		switch ($this->conf['mode']) {
			case 'TOC':
				// Replace links in table of contents
				$toc = $this->replaceLinks($document, $content['toc']);
				// Remove empty sublevels
				$toc = preg_replace('#<ul>\s*</ul>#', '', $toc);
				$output = '<h2 class="title-format-1">Table of Contents</h2>';
				$output .= preg_replace('#^<ul>\s*<li>#', '<ul class="list m-r"><li class="current">', $toc);
				if (isset($content['prev'])) {
					$output .= '<h2 class="title-format-1">Previous topic</h2>';
					$link = $content['prev']['link'];
					$link = $this->relativeToAbsolute($document, $link);
					$link = $this->getLink($link);
					$output .= '<ul class="list m-r"><li><a href="' . $link . '">' . $content['prev']['title'] . '</a></li></ul>';
				}
				if (isset($content['next'])) {
					$output .= '<h2 class="title-format-1">Next topic</h2>';
					$link = $content['next']['link'];
					$link = $this->relativeToAbsolute($document, $link);
					$link = $this->getLink($link);
					$output .= '<ul class="list m-r"><li><a href="' . $link . '">' . $content['next']['title'] . '</a></li></ul>';
				}
				break;
			case 'BODY':
				// Remove permanent links in body
				$body = preg_replace('#<a class="headerlink" [^>]+>[^<]+</a>#', '', $content['body']);
				// Replace links in body
				$body = $this->replaceLinks($document, $body);
				// Replace images in body
				$body = $this->replaceImages($documentRoot . $document, $body);
				$output = $body;
				break;
			default:
				$output = '';
				break;
		}

		return $this->pi_wrapInBaseClass($output);
	}

	/**
	 * Converts a relative path to an absolute one.
	 *
	 * @param string $path
	 * @param string $reference
	 * @private This method is made public to be accessible from a lambda-function scope
	 */
	public function relativeToAbsolute($path, $reference) {
		$absolute = '';
		$path = rtrim($path, '/');
		$pathParts = explode('/', $path);
		$referenceParts = explode('/', $reference);
		$isRelative = FALSE;

		for ($i = 0; $i < count($referenceParts); $i++) {
			if ($referenceParts[$i] == '..' && count($pathParts) > 0) {
				$isRelative = TRUE;
				array_pop($pathParts);
			} else {
				if ($isRelative) {
					$absolute = implode('/', $pathParts) . '/';
				}
				$absolute .= implode('/', array_slice($referenceParts, $i));
				$absolute = ltrim($absolute, '/');
				break;
			}
		}

		return $absolute;
	}

	/**
	 * Generates a link to navigate within a reST documentation project.
	 *
	 * @param string $document Target document
	 * @private This method is made public to be accessible from a lambda-function scope
	 */
	public function getLink($document) {
		$conf = array();
		$anchor = '';
		if ($document !== '') {
			if (($pos = strrpos($document, '#')) !== FALSE) {
				$anchor = substr($document, $pos + 1);
				$document = substr($document, 0, $pos);
			}
			$conf = array(
				$this->prefixId => array(
					'doc' => $document,
				)
			);
		}
		if (substr($document, 0, 11) === '_downloads/') {
			$link = $this->cObj->typoLink_URL(array('parameter' => rtrim($this->conf['path'], '/') . '/' . $document));
		} else {
			$link = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $conf);
			if ($anchor !== '') {
				$link .= '#' . $anchor;
			}
		}
		return $link;
	}

	/**
	 * Replaces links in a reST document.
	 *
	 * @param string $document
	 * @param string $content
	 * @return string
	 */
	protected function replaceLinks($document, $content) {
		$plugin = $this;
		$ret = preg_replace_callback('#(<a .* href=")([^"]+)#', function($matches) use ($plugin, $document) {
			/** @var $plugin tx_restdoc_pi1 */
			$anchor = '';
			if (preg_match('#^[a-zA-Z]+://#', $matches[2])) {
				// External URL
				return $matches[0];
			} elseif ($matches[2]{0} === '#') {
				$anchor = $matches[2];
			}

			if ($anchor !== '') {
				$document .= $anchor;
			} else {
				$document = $plugin->relativeToAbsolute($document, $matches[2]);
			}
			return $matches[1] . $plugin->getLink($document);
		}, $content);
		return $ret;
	}

	/**
	 * Replaces images in a reST document.
	 *
	 * @param string $root
	 * @param string $content
	 * @return string
	 */
	protected function replaceImages($root, $content) {
		$plugin = $this;
		$ret = preg_replace_callback('#(<img .* src=")([^"]+)#', function($matches) use ($plugin, $root) {
			$image = '/' . $plugin->relativeToAbsolute(substr($root, 1), $matches[2]);
			$conf = array(
				'file' => substr($image, strlen(PATH_site)),
			);
			return $matches[1] . $plugin->cObj->IMG_RESOURCE($conf);
		}, $content);
		return $ret;
	}

	/**
	 * This method performs various initializations.
	 *
	 * @param array $conf: Plugin configuration, as received by the main() method
	 * @return void
	 */
	protected function init(array $conf) {
		$this->conf = $conf;

			// Load the flexform and loop on all its values to override TS setup values
			// Some properties use a different test (more strict than not empty) and yet some others no test at all
			// see http://wiki.typo3.org/index.php/Extension_Development,_using_Flexforms
		$this->pi_initPIflexForm(); // Init and get the flexform data of the plugin

			// Assign the flexform data to a local variable for easier access
		$piFlexForm = $this->cObj->data['pi_flexform'];

		if (is_array($piFlexForm['data'])) {
			$multiValueKeys = array();
				// Traverse the entire array based on the language
				// and assign each configuration option to $this->settings array...
			foreach ($piFlexForm['data'] as $sheet => $data) {
				foreach ($data as $lang => $value) {
					/** @var $value array */
					foreach ($value as $key => $val) {
						$value = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
						if (trim($value) !== '' && in_array($key, $multiValueKeys)) {
							// Funny, FF contains a comma-separated list of key|value and
							// we only want to have key...
							$tempValues = explode(',', $value);
							$tempKeys = array();
							foreach ($tempValues as $tempValue) {
								list($k, $v) = explode('|', $tempValue);
								$tempKeys[] = $k;
							}
							$value = implode(',', $tempKeys);
						}
						if (trim($value) !== '' || !isset($this->conf[$key])) {
							$this->conf[$key] = $value;
						}
					}
				}
			}
		}
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/restdoc/pi1/class.tx_restdoc_pi1.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/restdoc/pi1/class.tx_restdoc_pi1.php']);
}

?>