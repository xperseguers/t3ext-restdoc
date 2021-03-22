<?php
defined('TYPO3_MODE') || die();

// Initialize static extension templates
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'restdoc',
    'Configuration/TypoScript/',
    'Sphinx Documentation Viewer: Base'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'restdoc',
    'Configuration/TypoScript/Bootstrap4/',
    'Sphinx Documentation Viewer: Bootstrap 4.x'
);
