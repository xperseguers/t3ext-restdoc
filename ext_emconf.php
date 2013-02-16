<?php

########################################################################
# Extension Manager/Repository config file for ext "restdoc".
#
# Auto generated 05-05-2012 20:34
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

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
	'version' => '1.0.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-6.0.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:12:{s:9:"ChangeLog";s:4:"6bbe";s:12:"ext_icon.gif";s:4:"d53d";s:17:"ext_localconf.php";s:4:"38c6";s:14:"ext_tables.php";s:4:"0029";s:16:"locallang_db.xml";s:4:"56b0";s:14:"doc/manual.sxw";s:4:"ae61";s:34:"hooks/class.tx_restdoc_realurl.php";s:4:"8e08";s:28:"pi1/class.tx_restdoc_pi1.php";s:4:"0f42";s:16:"pi1/flexform.xml";s:4:"1a46";s:17:"pi1/locallang.xml";s:4:"bb2c";s:20:"static/constants.txt";s:4:"d41d";s:16:"static/setup.txt";s:4:"18cf";}',
	'suggests' => array(
	),
);

?>