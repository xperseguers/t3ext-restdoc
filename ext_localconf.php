<?php
defined('TYPO3_MODE') or die();

$config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'Classes/Controller/Pi1/Pi1Controller.php', '_pi1', 'list_type', (bool)$config['cache_plugin_output']);

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('dd_googlesitemap')) {
	// Hook for integrating ReStructured documentation into the Google Sitemap (requires EXT:dd_googlesitemap)
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['dd_googlesitemap']['generateSitemapForPagesClass'][] = 'Causal\\Restdoc\\Hooks\\TxDdgooglesitemap';

	// Implement our own hook to slowly populate complete document structure
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['renderHook'][] = 'Causal\\Restdoc\\Hooks\\TableOfContents';
}

// Register new TypoScript content object
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = array(
	0 => 'REST_METADATA',
	1 => 'Causal\\Restdoc\\ContentObject\\RestMetadataContentObject',
);

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
	// RealURL auto-configuration
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$_EXTKEY] =
		'Causal\\Restdoc\\Hooks\\Realurl->registerDefaultConfiguration';

	// RealURL is seriously broken when there is no existing configuration and it
	// should be auto-configured since registering the hook below will have the
	// effect of preventing the auto-configuration to take place and every page will
	// be encoded with the uid as segment only!
	if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'])) {
		$message = 'Extension ' . $_EXTKEY . ' is loaded after "realurl". ';
		$message .= 'Hooks into "realurl" have been disabled to prevent breaking it completely ';
		$message .= '(please see https://forge.typo3.org/issues/67121 for details). ';
		$message .= 'To fix this issue, you will have to manually edit file typo3conf/PackageStates.php ';
		$message .= 'and move the "realurl" block BEFORE the "' . $_EXTKEY . '" one.';
		\TYPO3\CMS\Core\Utility\GeneralUtility::sysLog(
			$message,
			$_EXTKEY,
			\TYPO3\CMS\Core\Utility\GeneralUtility::SYSLOG_SEVERITY_ERROR
		);
	} else {
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['decodeSpURL_preProc'][] =
			'Causal\\Restdoc\\Hooks\\Realurl->decodeSpURL_preProc';
	}
}
