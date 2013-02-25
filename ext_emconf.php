<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "restdoc".
 *
 * Auto generated 16-02-2013 14:27
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'reST Documentation Viewer',
	'description' => 'Integrates a reStructuredText documentation (generated as JSON) into a TYPO3 website.',
	'category' => 'plugin',
	'author' => 'Xavier Perseguers',
	'author_company' => 'Causal Sàrl',
	'author_email' => 'xavier@causal.ch',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.1.0-dev',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-6.0.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:12:{s:9:"ChangeLog";s:4:"d9cd";s:12:"ext_icon.gif";s:4:"d53d";s:17:"ext_localconf.php";s:4:"38c6";s:14:"ext_tables.php";s:4:"0029";s:16:"locallang_db.xml";s:4:"a3d5";s:14:"doc/manual.sxw";s:4:"4c81";s:34:"hooks/class.tx_restdoc_realurl.php";s:4:"8e08";s:28:"pi1/class.tx_restdoc_pi1.php";s:4:"417f";s:16:"pi1/flexform.xml";s:4:"1fcc";s:17:"pi1/locallang.xml";s:4:"2dab";s:20:"static/constants.txt";s:4:"656f";s:16:"static/setup.txt";s:4:"56c8";}',
	'suggests' => array(
	),
);

?>