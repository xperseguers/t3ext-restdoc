<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_restdoc_pi1.php', '_pi1', 'list_type', FALSE);

// RealURL auto-configuration
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_restdoc_realurl.php:tx_restdoc_realurl->addRestdocConfig';
?>