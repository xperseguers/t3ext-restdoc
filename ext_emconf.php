<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "restdoc".
 *
 * Auto generated 07-03-2013 16:31
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
	'version' => '1.2.0-dev',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-6.1.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:18:{s:9:"ChangeLog";s:4:"d554";s:16:"ext_autoload.php";s:4:"752c";s:12:"ext_icon.gif";s:4:"d53d";s:17:"ext_localconf.php";s:4:"f7cf";s:14:"ext_tables.php";s:4:"0029";s:14:"ext_tables.sql";s:4:"11cb";s:16:"locallang_db.xml";s:4:"d980";s:14:"doc/manual.sxw";s:4:"9ea7";s:25:"examples/center-images.ts";s:4:"7392";s:42:"hooks/class.tx_restdoc_ddgooglesitemap.php";s:4:"08cf";s:34:"hooks/class.tx_restdoc_realurl.php";s:4:"8e08";s:30:"hooks/class.tx_restdoc_toc.php";s:4:"78cb";s:32:"lib/class.tx_restdoc_utility.php";s:4:"12e4";s:28:"pi1/class.tx_restdoc_pi1.php";s:4:"3071";s:16:"pi1/flexform.xml";s:4:"3234";s:17:"pi1/locallang.xml";s:4:"2fea";s:20:"static/constants.txt";s:4:"7837";s:16:"static/setup.txt";s:4:"e74a";}',
	'suggests' => array(
	),
);

?>