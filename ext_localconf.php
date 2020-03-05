<?php
defined('TYPO3_MODE') || die();

$boot = function ($_EXTKEY) {
    if (version_compare(TYPO3_version, '9.0', '<')) {
        $config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
    } else {
        $config = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get($_EXTKEY);
    }

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'Classes/Controller/Pi1/Pi1Controller.php', '_pi1', 'list_type', (bool)$config['cache_plugin_output']);

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

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('dd_googlesitemap')) {
        // Hook for integrating ReStructured documentation into the Google Sitemap (requires EXT:dd_googlesitemap)
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['dd_googlesitemap']['generateSitemapForPagesClass'][] = 'Causal\\Restdoc\\Hooks\\TxDdgooglesitemap';

        // Implement our own hook to slowly populate complete document structure
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['renderHook'][] = 'Causal\\Restdoc\\Hooks\\TableOfContents';
    }

    // Register new TypoScript content object
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = [
        0 => 'REST_METADATA',
        1 => 'Causal\\Restdoc\\ContentObject\\RestMetadataContentObject',
    ];

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
        // RealURL auto-configuration
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$_EXTKEY] =
            'Causal\\Restdoc\\Hooks\\Realurl->registerDefaultConfiguration';

        // Hook to grant access to reStructuredText source files, works only for EXT:realurl 1.x
        if (is_dir(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('realurl') . 'doc')) {
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['decodeSpURL_preProc'][] =
                'Causal\\Restdoc\\Hooks\\Realurl->decodeSpURL_preProc';
        }
    }
};

$boot('restdoc');
unset($boot);
