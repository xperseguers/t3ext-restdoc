<?php
defined('TYPO3') || die();

(static function (string $_EXTKEY) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $_EXTKEY,
        'Pi1',
        [
            \Causal\Restdoc\Controller\Pi1Controller::class => 'main',
        ],
        []
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '@import \'EXT:restdoc/Configuration/TsConfig/Page/Mod/Wizards/NewContentElement.tsconfig\''
    );

    // Register new TypoScript content object
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = [
        0 => 'REST_METADATA',
        1 => \Causal\Restdoc\ContentObject\RestMetadataContentObject::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['enhancers']['RestdocPlugin'] = \Causal\Restdoc\Routing\Enhancer\RestdocPluginEnhancer::class;
})('restdoc');
