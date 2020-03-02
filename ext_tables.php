<?php
defined('TYPO3_MODE') || die();

$boot = function ($_EXTKEY) {
    if (TYPO3_MODE === 'BE') {
        $GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['Causal\\Restdoc\\Controller\\Pi1\\WizardIcon'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Controller/Pi1/WizardIcon.php';
    }
};

$boot('restdoc');
unset($boot);
