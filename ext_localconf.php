<?php
defined('TYPO3_MODE') || die();

(static function (string $_EXTKEY) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'Classes/Controller/Pi1/Pi1Controller.php', '_pi1', 'list_type', true);

    /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'extensions-restdoc-wizard',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        [
            'source' => 'EXT:restdoc/Resources/Public/Icons/pi1_ce_wizard.png',
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:restdoc/Configuration/TsConfig/Page/Mod/Wizards/NewContentElement.tsconfig">'
    );

    // Register new TypoScript content object
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = [
        0 => 'REST_METADATA',
        1 => \Causal\Restdoc\ContentObject\RestMetadataContentObject::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['enhancers']['RestdocPlugin'] = \Causal\Restdoc\Routing\Enhancer\RestdocPluginEnhancer::class;
})('restdoc');
