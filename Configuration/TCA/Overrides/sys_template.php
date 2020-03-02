<?php
defined('TYPO3_MODE') or die();

// Initialize static extension templates
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('restdoc', 'Configuration/TypoScript/', 'Sphinx Documentation Viewer Plugin');
