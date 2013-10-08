<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "restdoc".
 *
 * Auto generated 04-06-2013 14:19
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Sphinx/reStructuredText Documentation Viewer',
	'description' => 'Seamlessly embeds Sphinx/reStructuredText-based documentation into your TYPO3 website. Instead of publishing your various manual, in-house documents, guides, references, ... solely as PDF, render them as JSON and use this extension to show them as part of your website to enhance the overall user experience and Search Engine Optimization (SEO). Lets you merge the chapter structure with the breadcrumb menu and much more. Documentation styles automatically inherit from your corporate design.',
	'category' => 'plugin',
	'author' => 'Xavier Perseguers (Causal)',
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
	'version' => '1.3.1-dev',
	'constraints' => array(
		'depends' => array(
			'php' => '5.3.3-0.0.0',
			'typo3' => '4.5.0-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:116:{s:9:"ChangeLog";s:4:"d5f4";s:16:"ext_autoload.php";s:4:"b656";s:12:"ext_icon.gif";s:4:"406f";s:17:"ext_localconf.php";s:4:"80c3";s:14:"ext_tables.php";s:4:"06ff";s:14:"ext_tables.sql";s:4:"ee7a";s:51:"Classes/ContentObject/RestMetadataContentObject.php";s:4:"a292";s:47:"Classes/Controller/Pi1/class.tx_restdoc_pi1.php";s:4:"b1b4";s:55:"Classes/Controller/Pi1/class.tx_restdoc_pi1_wizicon.php";s:4:"ed68";s:32:"Classes/Hook/TableOfContents.php";s:4:"11fc";s:39:"Classes/Hook/TxDdgooglesitemapPages.php";s:4:"9264";s:34:"Classes/Hook/TxRealurlAutoconf.php";s:4:"0fd2";s:29:"Classes/Reader/SphinxJson.php";s:4:"4836";s:44:"Classes/Utility/class.tx_restdoc_utility.php";s:4:"bbca";s:26:"Classes/Utility/Helper.php";s:4:"2b8a";s:40:"Configuration/FlexForms/flexform_pi1.xml";s:4:"6a45";s:38:"Configuration/TypoScript/constants.txt";s:4:"830c";s:34:"Configuration/TypoScript/setup.txt";s:4:"9c71";s:26:"Documentation/Includes.txt";s:4:"8069";s:23:"Documentation/Index.rst";s:4:"85d4";s:26:"Documentation/Settings.yml";s:4:"cb96";s:38:"Documentation/Administration/Index.rst";s:4:"2a2d";s:58:"Documentation/Administration/InstallingExtension/Index.rst";s:4:"e092";s:62:"Documentation/Administration/TypoScriptConfiguration/Index.rst";s:4:"3a1b";s:33:"Documentation/ChangeLog/Index.rst";s:4:"304e";s:37:"Documentation/Configuration/Index.rst";s:4:"2b2d";s:50:"Documentation/Configuration/RestMetadata/Index.rst";s:4:"630f";s:50:"Documentation/Configuration/TxRestdocPi1/Index.rst";s:4:"18bf";s:55:"Documentation/Configuration/TxRestdocPi1Setup/Index.rst";s:4:"e4c9";s:35:"Documentation/Development/Index.rst";s:4:"06ed";s:53:"Documentation/Development/Hooks/MakeMenuArrayHook.rst";s:4:"1a5c";s:55:"Documentation/Development/Hooks/QuickNavigationHook.rst";s:4:"eeeb";s:46:"Documentation/Development/Hooks/RenderHook.rst";s:4:"2fca";s:50:"Documentation/Development/Hooks/SearchFormHook.rst";s:4:"7390";s:39:"Documentation/Images/plugin_options.png";s:4:"3b58";s:36:"Documentation/Introduction/Index.rst";s:4:"3796";s:49:"Documentation/Introduction/Screenshots/Images.txt";s:4:"848a";s:48:"Documentation/Introduction/Screenshots/Index.rst";s:4:"cd6e";s:49:"Documentation/Introduction/WhatDoesItDo/Index.rst";s:4:"cae3";s:37:"Documentation/KnownProblems/Index.rst";s:4:"763e";s:32:"Documentation/ToDoList/Index.rst";s:4:"813d";s:34:"Documentation/UserManual/Index.rst";s:4:"3de9";s:62:"Documentation/UserManual/GeneratingDocumentationJson/Index.rst";s:4:"fbca";s:49:"Documentation/UserManual/PluginOptions/Images.txt";s:4:"2781";s:48:"Documentation/UserManual/PluginOptions/Index.rst";s:4:"f0c5";s:47:"Documentation/UserManual/Requirements/Index.rst";s:4:"ba2e";s:43:"Resources/Private/Examples/center-images.ts";s:4:"7392";s:36:"Resources/Private/Examples/footer.ts";s:4:"bb9a";s:40:"Resources/Private/Language/locallang.xlf";s:4:"10b8";s:40:"Resources/Private/Language/locallang.xml";s:4:"5a7b";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"6e60";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"894c";s:40:"Resources/Public/Icons/pi1_ce_wizard.png";s:4:"7240";s:39:"Resources/Public/JavaScript/doctools.js";s:4:"5ff5";s:45:"Resources/Public/JavaScript/searchtools.12.js";s:4:"9d3a";s:42:"Resources/Public/JavaScript/searchtools.js";s:4:"494b";s:41:"Resources/Public/JavaScript/underscore.js";s:4:"b538";s:64:"Tests/Functional/ContentObject/RestMetadataContentObjectTest.php";s:4:"d6b0";s:33:"Tests/Functional/Fixtures/conf.py";s:4:"53f0";s:35:"Tests/Functional/Fixtures/index.rst";s:4:"3ee6";s:35:"Tests/Functional/Fixtures/intro.rst";s:4:"a407";s:34:"Tests/Functional/Fixtures/Makefile";s:4:"5b13";s:60:"Tests/Functional/Fixtures/_build/doctrees/environment.pickle";s:4:"62cf";s:55:"Tests/Functional/Fixtures/_build/doctrees/index.doctree";s:4:"8027";s:55:"Tests/Functional/Fixtures/_build/doctrees/intro.doctree";s:4:"e026";s:68:"Tests/Functional/Fixtures/_build/doctrees/subdirectory/index.doctree";s:4:"e392";s:51:"Tests/Functional/Fixtures/_build/html/genindex.html";s:4:"72b3";s:48:"Tests/Functional/Fixtures/_build/html/index.html";s:4:"7125";s:48:"Tests/Functional/Fixtures/_build/html/intro.html";s:4:"76b7";s:49:"Tests/Functional/Fixtures/_build/html/objects.inv";s:4:"eb3e";s:49:"Tests/Functional/Fixtures/_build/html/search.html";s:4:"c11e";s:52:"Tests/Functional/Fixtures/_build/html/searchindex.js";s:4:"da49";s:56:"Tests/Functional/Fixtures/_build/html/_sources/index.txt";s:4:"3ee6";s:56:"Tests/Functional/Fixtures/_build/html/_sources/intro.txt";s:4:"a407";s:69:"Tests/Functional/Fixtures/_build/html/_sources/subdirectory/index.txt";s:4:"9e5f";s:61:"Tests/Functional/Fixtures/_build/html/_static/ajax-loader.gif";s:4:"ae66";s:55:"Tests/Functional/Fixtures/_build/html/_static/basic.css";s:4:"e750";s:64:"Tests/Functional/Fixtures/_build/html/_static/comment-bright.png";s:4:"0c85";s:63:"Tests/Functional/Fixtures/_build/html/_static/comment-close.png";s:4:"2635";s:57:"Tests/Functional/Fixtures/_build/html/_static/comment.png";s:4:"882e";s:57:"Tests/Functional/Fixtures/_build/html/_static/default.css";s:4:"9085";s:57:"Tests/Functional/Fixtures/_build/html/_static/doctools.js";s:4:"5ff5";s:62:"Tests/Functional/Fixtures/_build/html/_static/down-pressed.png";s:4:"ebe8";s:54:"Tests/Functional/Fixtures/_build/html/_static/down.png";s:4:"f6f3";s:54:"Tests/Functional/Fixtures/_build/html/_static/file.png";s:4:"6587";s:55:"Tests/Functional/Fixtures/_build/html/_static/jquery.js";s:4:"1009";s:55:"Tests/Functional/Fixtures/_build/html/_static/minus.png";s:4:"8d57";s:54:"Tests/Functional/Fixtures/_build/html/_static/plus.png";s:4:"0125";s:58:"Tests/Functional/Fixtures/_build/html/_static/pygments.css";s:4:"3fe3";s:60:"Tests/Functional/Fixtures/_build/html/_static/searchtools.js";s:4:"d550";s:56:"Tests/Functional/Fixtures/_build/html/_static/sidebar.js";s:4:"521d";s:59:"Tests/Functional/Fixtures/_build/html/_static/underscore.js";s:4:"db5b";s:60:"Tests/Functional/Fixtures/_build/html/_static/up-pressed.png";s:4:"8ea9";s:52:"Tests/Functional/Fixtures/_build/html/_static/up.png";s:4:"ecc3";s:59:"Tests/Functional/Fixtures/_build/html/_static/websupport.js";s:4:"9e61";s:61:"Tests/Functional/Fixtures/_build/html/subdirectory/index.html";s:4:"9d16";s:56:"Tests/Functional/Fixtures/_build/json/environment.pickle";s:4:"2c0a";s:52:"Tests/Functional/Fixtures/_build/json/genindex.fjson";s:4:"bb93";s:56:"Tests/Functional/Fixtures/_build/json/globalcontext.json";s:4:"7c63";s:49:"Tests/Functional/Fixtures/_build/json/index.fjson";s:4:"39c2";s:49:"Tests/Functional/Fixtures/_build/json/intro.fjson";s:4:"d3fd";s:48:"Tests/Functional/Fixtures/_build/json/last_build";s:4:"d41d";s:49:"Tests/Functional/Fixtures/_build/json/objects.inv";s:4:"231a";s:50:"Tests/Functional/Fixtures/_build/json/search.fjson";s:4:"ac00";s:54:"Tests/Functional/Fixtures/_build/json/searchindex.json";s:4:"08cf";s:56:"Tests/Functional/Fixtures/_build/json/_sources/index.txt";s:4:"3ee6";s:56:"Tests/Functional/Fixtures/_build/json/_sources/intro.txt";s:4:"a407";s:69:"Tests/Functional/Fixtures/_build/json/_sources/subdirectory/index.txt";s:4:"9e5f";s:58:"Tests/Functional/Fixtures/_build/json/_static/pygments.css";s:4:"3fe3";s:62:"Tests/Functional/Fixtures/_build/json/subdirectory/index.fjson";s:4:"712d";s:48:"Tests/Functional/Fixtures/subdirectory/index.rst";s:4:"9e5f";s:42:"Tests/Functional/Reader/SphinxJsonTest.php";s:4:"19b0";s:33:"Tests/Unit/Utility/HelperTest.php";s:4:"c5c4";s:14:"doc/manual.sxw";s:4:"948a";s:20:"static/constants.txt";s:4:"665b";s:16:"static/setup.txt";s:4:"463c";}',
	'suggests' => array(
	),
);

?>