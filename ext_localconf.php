<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'Classes/Controller/Pi1/class.tx_restdoc_pi1.php', '_pi1', 'list_type', FALSE);

if (t3lib_extMgm::isLoaded('dd_googlesitemap')) {
	// Hook for integrating ReStructured documentation into the Google Sitemap (requires EXT:dd_googlesitemap)
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['dd_googlesitemap']['generateSitemapForPagesClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hook/TxDdgooglesitemapPages.php:Tx_Restdoc_Hook_TxDdgooglesitemapPages';

	// Implement our own hook to slowly populate complete document structure
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['renderHook'][] = 'EXT:' . $_EXTKEY . '/Classes/Hook/TableOfContents.php:Tx_Restdoc_Hook_TableOfContents';
}

// Register new TypoScript content object
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = array(
	0 => 'REST_METADATA',
	1 => 'EXT:restdoc/Classes/ContentObject/RestMetadataContentObject.php:Tx_Restdoc_ContentObject_RestMetadataContentObject',
);

// RealURL auto-configuration
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Classes/Hook/TxRealurlAutoconf.php:Tx_Restdoc_Hook_RealurlAutoconf->registerDefaultConfiguration';
?>