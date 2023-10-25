<?php

defined('TYPO3_MODE') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'LLL:EXT:restdoc/Resources/Private/Language/locallang_db.xlf:tt_content.list_type_pi1',
        'restdoc_pi1'
    ],
    'list_type',
    'restdoc'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['restdoc_pi1'] = 'layout,pages,recursive';

// Register the FlexForms
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['restdoc_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('restdoc_pi1', 'FILE:EXT:restdoc/Configuration/FlexForms/flexform_pi1.xml');
