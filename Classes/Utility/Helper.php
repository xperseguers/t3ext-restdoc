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
 */
final class Tx_Restdoc_Utility_Helper {

	/**
	 * Returns Sphinx-related metadata.
	 *
	 * @param string $path Absolute path to the documentation
	 * @return array
	 */
	static public function getMetadata($path) {
		if (!is_dir($path)) {
			// Most probably a relative path has been provided
			$path = PATH_site . $path;
			t3lib_div::deprecationLog('EXT:restdoc - Tx_Restdoc_Utility_Helper::getMetadata() needs an absolute path as argument since 1.3.0. Support for relative path will be removed in 1.5.0.');
		}
		$documentRoot = rtrim($path, '/') . '/';
		$jsonFile = 'globalcontext.json';

		$data = array();
		if (is_file($documentRoot . $jsonFile)) {
			$content = file_get_contents($documentRoot . $jsonFile);
			$data = json_decode($content, TRUE);
		}
		return $data;
	}

	/**
	 * Returns a TYPO3-compatible list of menu entries.
	 *
	 * @param array $entries
	 * @return array
	 */
	static public function getMenuData(array $entries) {
		$menu = array();
		$entries = isset($entries['li'][0]) ? $entries['li'] : array($entries['li']);
		foreach ($entries as $entry) {
			$menuEntry = array(
				'title' => $entry['a']['@content'],
				'_OVERRIDE_HREF' => $entry['a']['@attributes']['href'],
			);
			if (isset($entry['ul'])) {
				$menuEntry['_SUB_MENU'] = self::getMenuData($entry['ul']);
			}

			$menu[] = $menuEntry;
		}

		return $menu;
	}

	/**
	 * Marks menu entries as ACTIVE or CURRENT and generate real links.
	 *
	 * @param array &$data
	 * @param string $currentDocument
	 * @param callback $callbackLinks Callback to generate Links in current context
	 * @return boolean
	 * @throws RuntimeException
	 */
	static public function processMasterTableOfContents(array &$data, $currentDocument, $callbackLinks) {
		$callableName = '';
		if (!is_callable($callbackLinks, FALSE, $callableName)) {
			throw new RuntimeException('Invalid callback for links: ' . $callableName, 1370013916);
		}

		$hasCurrent = FALSE;

		foreach ($data as &$menuEntry) {
			if (substr($menuEntry['_OVERRIDE_HREF'], 0, 3) === '../') {
				$menuEntry['_OVERRIDE_HREF'] = substr($menuEntry['_OVERRIDE_HREF'], 3);
			}
			if ($menuEntry['_OVERRIDE_HREF'] === $currentDocument) {
				$hasCurrent = TRUE;
				$menuEntry['ITEM_STATE'] = 'CUR';
			}
			$menuEntry['_OVERRIDE_HREF'] = call_user_func($callbackLinks, $menuEntry['_OVERRIDE_HREF']);
			if (isset($menuEntry['_SUB_MENU'])) {
				$hasChildCurrent = self::processMasterTableOfContents($menuEntry['_SUB_MENU'], $currentDocument, $callbackLinks);
				if ($hasChildCurrent) {
					$hasCurrent = TRUE;
					$menuEntry['ITEM_STATE'] = 'ACT';
				}
			}
		}

		return $hasCurrent;
	}

	/**
	 * Converts an XML string to a PHP array - useful to get a serializable value.
	 *
	 * @param string $xmlstr
	 * @return array
	 * @link https://github.com/gaarf/XML-string-to-PHP-array/blob/master/xmlstr_to_array.php
	 */
	static public function xmlstr_to_array($xmlstr) {
		$doc = new DOMDocument();
		$doc->loadXML($xmlstr);
		return self::domnode_to_array($doc->documentElement);
	}

	/**
	 * Sends a given ReStructuredText document to the browser.
	 * One-way method: will exit program normally at the end.
	 *
	 * @param string $filename
	 * @return void Program will stop after calling this method
	 */
	static public function showSources($filename) {
		if (!is_file($filename)) {
			t3lib_utility_Http::setResponseCodeAndExit(t3lib_utility_Http::HTTP_STATUS_404);
		}

		// Getting headers sent by the client.
		$headers = function_exists('apache_request_headers') ? apache_request_headers() : array();

		// Checking if the client is validating his cache and if it is current
		if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($filename))) {
			// Client's cache is current, so we just respond '304 Not Modified'
			t3lib_utility_Http::setResponseCodeAndExit(t3lib_utility_Http::HTTP_STATUS_304);
		} else {
			// Source is not cached or cache is outdated
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filename)) . ' GMT', TRUE, 200);
			header('Content-Length: ' . filesize($filename));
			header('Content-Type: text/plain; charset=utf-8');
			echo file_get_contents($filename);
			exit;
		}
	}

	/**
	 * Converts a relative path to an absolute one.
	 *
	 * @param string $fullPath
	 * @param string $relative
	 * @return string
	 */
	static public function relativeToAbsolute($fullPath, $relative) {
		$absolute = '';
		$fullPath = rtrim($fullPath, '/');
		$fullPathParts = explode('/', $fullPath);
		// We need an additional directory for parent paths to work (as we trimmed document name from $fullPath
		// in the caller method
		$fullPathParts[] = '';
		$relativeParts = explode('/', $relative);

		for ($i = 0; $i < count($relativeParts); $i++) {
			if ($relativeParts[$i] == '..' && count($fullPathParts) > 0) {
				array_pop($fullPathParts);
			} else {
				$absolute = implode('/', $fullPathParts) . '/';
				$absolute .= implode('/', array_slice($relativeParts, $i));
				break;
			}
		}

		return str_replace('//', '/', $absolute);
	}

	/**
	 * Returns an index definition list as HTML.
	 *
	 * @param string $documentRoot
	 * @param array $index
	 * @param callback $callbackLinks Callback to generate Links in current context
	 * @return string
	 * @throws RuntimeException
	 */
	static public function getIndexDefinitionList($documentRoot, array $index, $callbackLinks) {
		$callableName = '';
		if (!is_callable($callbackLinks, FALSE, $callableName)) {
			throw new RuntimeException('Invalid callback for links: ' . $callableName, 1369979755);
		}

		$output = '<dl>' . LF;
		foreach ($index as $dt) {
			$relativeLinks = array();
			for ($i = 0; $i < count($dt[1]); $i++) {
				if (!empty($dt[1][$i]) && t3lib_div::isFirstPartOfStr($dt[1][$i][1], '../')) {
					$relativeLinks[] = array(
						'title' => $dt[1][$i][0],
						'link'  => substr($dt[1][$i][1], 3),
					);
				} elseif ($i == 0 && !empty($dt[1][$i]) && is_array($dt[1][$i][0]) && t3lib_div::isFirstPartOfStr($dt[1][$i][0][1], '../')) {
					$relativeLinks[] = array(
						'title' => $dt[1][$i][0][0],
						'link'  => substr($dt[1][$i][0][1], 3),
					);
				} else {
					// No more entry links, we have subentries from now on
					break;
				}
			}
			// Remove category links from the list of subentries, first subentry is always a link, possibly empty
			for ($i = 0; $i < max(1, count($relativeLinks)); $i++) {
				array_shift($dt[1]);
			}

			$output .= '<dt>';
			if ($relativeLinks) {
				for ($i = 0; $i < count($relativeLinks); $i++) {
					if ($i == 0) {
						$title = htmlspecialchars($dt[0]);
					} else {
						$output .= ', ';
						$title = '[' . $i . ']';
					}
					if ($relativeLinks[$i]['title'] === 'main') {
						$title = '<strong>' . $title . '</strong>';
					}
					$link = call_user_func($callbackLinks, $relativeLinks[$i]['link']);
					$link = str_replace('&amp;', '&', $link);
					$link = str_replace('&', '&amp;', $link);

					$output .= '<a href="' . $link . '">' . $title . '</a>';
				}
			} else {
				$output .= htmlspecialchars($dt[0]);
			}
			$output .= '</dt>' . LF;

			if ($dt[1]) {
				$output .= '<dd>' . LF;
				foreach ($dt[1] as $term) {
					$output .= self::getIndexDefinitionList($documentRoot, $term, $callbackLinks);
				}
				$output .= '</dd>' . LF;
			}
		}
		$output .= '</dl>' . LF;

		return $output;
	}

	/**
	 * Converts a DOM node into an array.
	 *
	 * @param DOMNode $node
	 * @return array
	 */
	protected static function domnode_to_array(DOMNode $node) {
		$output = array();
		switch ($node->nodeType) {

			case XML_CDATA_SECTION_NODE:
			case XML_TEXT_NODE:
				$output = trim($node->textContent);
				break;

			case XML_ELEMENT_NODE:
				for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
					$child = $node->childNodes->item($i);
					$v = self::domnode_to_array($child);
					if (isset($child->tagName)) {
						if (!is_array($output)) {
							$output = array($output);
						}
						$t = $child->tagName;
						if (!isset($output[$t])) {
							$output[$t] = array();
						}
						$output[$t][] = $v;
					}
					elseif ($v || $v === '0') {
						if (is_array($output) && count($output) > 0) {	// e.g., <em> in the middle of a string
							$struct = $output;
							$output = '';
							foreach ($struct as $tag => $content) {
								if (is_numeric($tag)) {
									$output .= $content . ' ';
								} else {
									$output .= sprintf('<%s>%s</%s> ', $tag, $content[0], $tag);
								}
							}
							$output .= (string) $v;
							$output = trim($output);
						} else {
							$output = (string) $v;
						}
					}
				}
				if ($node->attributes->length && !is_array($output)) { // Has attributes but isn't an array
					$output = array('@content' => $output); // Change output into an array.
				}
				if (is_array($output)) {
					if ($node->attributes->length) {
						$a = array();
						foreach($node->attributes as $attrName => $attrNode) {
							$a[$attrName] = (string) $attrNode->value;
						}
						$output['@attributes'] = $a;
					}
					foreach ($output as $t => $v) {
						if (is_array($v) && count($v)==1 && $t !== '@attributes') {
							$output[$t] = $v[0];
						}
					}
				}
				break;
		}

		return $output;
	}

}
