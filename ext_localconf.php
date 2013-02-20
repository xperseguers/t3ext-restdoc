<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_restdoc_pi1.php', '_pi1', 'list_type', FALSE);

if (t3lib_extMgm::isLoaded('dd_googlesitemap')) {
	// Hook for integrating ReStructured documentation into the Google Sitemap (requires EXT:dd_googlesitemap)
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['dd_googlesitemap']['generateSitemapForPagesClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_restdoc_ddgooglesitemap.php:tx_restdoc_ddgooglesitemap';

	// Implement our own hook to slowly populate complete document structure
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['renderHook'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_restdoc_toc.php:tx_restdoc_toc';
}

// RealURL auto-configuration
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_restdoc_realurl.php:tx_restdoc_realurl->addRestdocConfig';
?>