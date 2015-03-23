<?php
defined('TYPO3_MODE') or die();

$config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'Classes/Controller/Pi1/Pi1Controller.php', '_pi1', 'list_type', (bool)$config['cache_plugin_output']);

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('dd_googlesitemap')) {
	// Hook for integrating ReStructured documentation into the Google Sitemap (requires EXT:dd_googlesitemap)
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['dd_googlesitemap']['generateSitemapForPagesClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hook/TxDdgooglesitemapPages.php:Causal\\Restdoc\\Hook\\TxDdgooglesitemapPages';

	// Implement our own hook to slowly populate complete document structure
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['renderHook'][] = 'EXT:' . $_EXTKEY . '/Classes/Hook/TableOfContents.php:Causal\\Restdoc\\Hook\\TableOfContents';
}

// Register new TypoScript content object
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = array(
	0 => 'REST_METADATA',
	1 => 'EXT:restdoc/Classes/ContentObject/RestMetadataContentObject.php:Causal\\Restdoc\\ContentObject\\RestMetadataContentObject',
);

// RealURL auto-configuration
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$_EXTKEY] =
	'EXT:' . $_EXTKEY . '/Classes/Hook/TxRealurlAutoconf.php:Causal\\Restdoc\\Hook\\RealurlAutoconf->registerDefaultConfiguration';
