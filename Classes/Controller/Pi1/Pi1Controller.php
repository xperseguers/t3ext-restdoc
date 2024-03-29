<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with TYPO3 source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Causal\Restdoc\Controller\Pi1;

use Causal\Restdoc\Reader\SphinxJson;
use Causal\Restdoc\Utility\RestHelper;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

/**
 * Plugin 'Sphinx Documentation Viewer Plugin' for the 'restdoc' extension.
 *
 * @category    Plugin
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class Pi1Controller extends AbstractPlugin
{
    public $prefixId = 'tx_restdoc_pi1';
    public $scriptRelPath = 'Classes/Controller/Pi1/Pi1Controller.php';
    public $extKey = 'restdoc';
    public $pi_checkCHash;

    /**
     * @var string
     */
    protected static $defaultFile = 'index';

    /**
     * @var array
     */
    public $renderingConfig = [];

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * Current chapter information as static to be accessible from
     * TypoScript when coming back to generate menu entries
     *
     * @var array
     */
    protected static $current = [];

    /**
     * @var SphinxJson
     */
    protected static $sphinxReader;

    /**
     * Pi1Controller constructor.
     */
    public function __construct()
    {
        $this->settings = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get($this->extKey);
        $this->pi_checkCHash = true;

        parent::__construct();
    }

    /**
     * The main method of the Plugin.
     *
     * @param string $content The plugin content
     * @param array $conf The plugin configuration
     * @return string The content that is displayed on the website
     * @throws \RuntimeException
     */
    public function main(string $content, array $conf): string
    {
        $this->init($conf);
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();

        $storage = self::$sphinxReader->getStorage();
        if ($storage !== null) {
            $storageConfiguration = self::$sphinxReader->getStorage()->getConfiguration();
            $basePath = rtrim($storageConfiguration['basePath'], '/') . '/';
        } else {
            // FAL is not used
            trigger_error('FAL is not used, please upgrade your plugin configuration.', E_USER_DEPRECATED);
            $basePath = Environment::getPublicPath() . '/';
        }

        $documentRoot = $basePath . rtrim($this->conf['path'], '/') . '/';
        $document = self::$defaultFile . '/';
        if (isset($this->piVars['doc']) && strpos($this->piVars['doc'], '..') === false) {
            $document = rtrim($this->piVars['doc'], '/') . '/';
        }

        // Sources are requested, if allowed and available, return them
        if ($this->conf['publishSources'] && GeneralUtility::isFirstPartOfStr($document, '_sources/')) {
            $sourceFile = rtrim($document, '/');
            $lastDot = strrpos($sourceFile, '.');
            if ($lastDot === false || $lastDot < strrpos($sourceFile, '/')) {
                // ".txt" extension has been removed by \Causal\Restdoc\Hooks\Realurl::decodeSpURL_preProc()
                $sourceFile .= '.txt';
            }
            // Security check
            if (substr($sourceFile, -4) === '.txt' && strpos(realpath($documentRoot . $sourceFile), realpath($documentRoot)) === 0) {
                // Will exit program normally
                RestHelper::showSources($documentRoot . $sourceFile);
            }
        }

        self::$sphinxReader
            ->setPath($documentRoot)
            ->setDocument($document)
            ->setKeepPermanentLinks((int)$this->conf['showPermanentLink'] !== 0)
            ->setDefaultFile($this->conf['defaultFile'])
            // TODO: only for TOC, BREADCRUMB, ... ? (question's context is when generating the general index)
            ->enableDefaultDocumentFallback();

        try {
            if (!self::$sphinxReader->load()) {
                throw new \RuntimeException('Document failed to load', 1365166377);
            }
        } catch (\RuntimeException $e) {
            return $e->getMessage();
        }

        $skipDefaultWrap = false;

        self::$current = [
            'path' => $this->conf['path'],
        ];

        if (self::$sphinxReader->getIndexEntries() === null) {
            switch ($this->conf['mode']) {
                case 'TOC':
                    $this->renderingConfig = $this->conf['setup.']['TOC.'];
                    $output = $this->cObj->cObjGetSingle($this->renderingConfig['renderObj'], $this->renderingConfig['renderObj.']);
                    break;
                case 'MASTER_TOC':
                    $this->renderingConfig = $this->conf['setup.']['MASTER_TOC.'];
                    $output = $this->cObj->cObjGetSingle($this->renderingConfig['renderObj'], $this->renderingConfig['renderObj.']);
                    break;
                case 'RECENT':
                    $this->renderingConfig = $this->conf['setup.']['RECENT.'];
                    $output = $this->cObj->cObjGetSingle($this->renderingConfig['renderObj'], $this->renderingConfig['renderObj.']);
                    break;
                case 'BODY':
                    if ($this->conf['advertiseSphinx']) {
                        $this->advertiseSphinx();
                    }
                    $output = $this->generateBody();
                    break;
                case 'TITLE':
                    $output = self::$sphinxReader->getTitle();
                    $skipDefaultWrap = true;
                    break;
                case 'QUICK_NAVIGATION':
                    $output = $this->generateQuickNavigation();
                    break;
                case 'BREADCRUMB':
                    $this->renderingConfig = $this->conf['setup.']['BREADCRUMB.'];
                    $output = $this->cObj->cObjGetSingle($this->renderingConfig['renderObj'], $this->renderingConfig['renderObj.']);
                    break;
                case 'REFERENCES':
                    $output = $this->generateReferences();
                    break;
                case 'FILENAME':
                    $output = self::$sphinxReader->getJsonFilename();
                    $skipDefaultWrap = true;
                    break;
                case 'SEARCH':
                    $output = $this->generateSearchForm();
                    break;
                default:
                    $output = '';
                    break;
            }
        } else {
            switch ($this->conf['mode']) {
                case 'BODY':
                    if ($this->conf['advertiseSphinx']) {
                        $this->advertiseSphinx();
                    }
                    // Generating output for the general index
                    $output = $this->generateIndex($documentRoot, $document);
                    break;
                case 'TITLE':
                    $output = $this->pi_getLL('index_title', 'Index');
                    $skipDefaultWrap = true;
                    break;
                case 'FILENAME':
                    $output = self::$sphinxReader->getJsonFilename();
                    $skipDefaultWrap = true;
                    break;
                default:
                    // Generating TOC, ... for the root document instead
                    $this->piVars['doc'] = '';
                    return $this->main('', $conf);
            }
        }

        // Hook for post-processing the output
        foreach (($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['renderHook'] ?? []) as $classRef) {
            $hookObject = GeneralUtility::makeInstance($classRef);
            $params = [
                'mode' => $this->conf['mode'],
                'documentRoot' => $documentRoot,
                'document' => $document,
                'output' => &$output,
                'config' => $this->conf,
                'pObj' => $this,
            ];
            if (is_callable([$hookObject, 'postProcessOutput'])) {
                $hookObject->postProcessOutput($params);
            }
        }

        // Wrap the whole result, with baseWrap if defined, else with standard pi_wrapInBaseClass() call
        if (isset($this->conf['baseWrap.'])) {
            $output = $this->cObj->stdWrap($output, $this->conf['baseWrap.']);
        } elseif (!$skipDefaultWrap) {
            $output = $this->pi_wrapInBaseClass($output);
        }

        return $output;
    }

    /**
     * Returns the default file.
     *
     * @return string
     */
    public function getDefaultFile(): string
    {
        return self::$defaultFile;
    }

    /**
     * Returns the Sphinx Reader.
     *
     * @return SphinxJson
     */
    public function getSphinxReader(): SphinxJson
    {
        return self::$sphinxReader;
    }

    /**
     * Generates the array for rendering the reST menu in TypoScript.
     *
     * @param string $content
     * @param array $conf
     * @return array
     */
    public function makeMenuArray(string $content, array $conf): array
    {
        $data = [];
        $type = $conf['userFunc.']['type'] ?? 'menu';

        $storage = self::$sphinxReader->getStorage();
        if ($storage !== null) {
            $storageConfiguration = self::$sphinxReader->getStorage()->getConfiguration();
            $basePath = rtrim($storageConfiguration['basePath'], '/') . '/';
        } else {
            // FAL is not used
            trigger_error('FAL is not used, please upgrade your plugin configuration.', E_USER_DEPRECATED);
            $basePath = Environment::getPublicPath() . '/';
        }

        $documentRoot = self::$sphinxReader->getPath();
        $document = self::$sphinxReader->getDocument();
        $prependParentItems = [];

        switch ($type) {
            case 'menu':
                $toc = self::$sphinxReader->getTableOfContents([$this, 'getLink']);
                $data = $toc ? RestHelper::getMenuData(RestHelper::xmlstr_to_array($toc)) : [];

                // Mark the first entry as 'active'
                $data[0]['ITEM_STATE'] = 'CUR';
                break;

            case 'master_menu':
                $masterToc = self::$sphinxReader->getMasterTableOfContents();
                $data = $masterToc ? RestHelper::getMenuData(RestHelper::xmlstr_to_array($masterToc)) : [];
                RestHelper::processMasterTableOfContents($data, null, [$this, 'getLink']);
                break;

            case 'previous':
                $previousDocument = self::$sphinxReader->getPreviousDocument();
                if ($previousDocument !== null) {
                    $absolute = RestHelper::relativeToAbsolute($documentRoot . $document, '../' . $previousDocument['link']);
                    $link = $this->getLink(substr($absolute, strlen($documentRoot)));
                    $data[] = [
                        'title' => $previousDocument['title'],
                        '_OVERRIDE_HREF' => $link,
                    ];
                }
                break;

            case 'next':
                $nextDocument = self::$sphinxReader->getNextDocument();
                if ($nextDocument !== null) {
                    if ($document === $this->getDefaultFile() . '/' && strpos($nextDocument['link'], '../') !== 0) {
                        $nextDocumentPath = $documentRoot;
                    } else {
                        $nextDocumentPath = $documentRoot . $document;
                    }
                    $absolute = RestHelper::relativeToAbsolute($nextDocumentPath, '../' . $nextDocument['link']);
                    $link = $this->getLink(substr($absolute, strlen($documentRoot)));
                    $data[] = [
                        'title' => $nextDocument['title'],
                        '_OVERRIDE_HREF' => $link,
                    ];
                }
                break;

            case 'rootline_breadcrumb':
                $rootline = $rootLine = GeneralUtility::makeInstance(RootlineUtility::class, $GLOBALS['TSFE']->id)->get();
                foreach (array_reverse($rootLine) as $page) {
                    $prependParentItems[] = [
                        'title' => $page['title'],
                        '_OVERRIDE_HREF' => $this->cObj->getTypoLink_URL($page['uid']),
                    ];
                }
                // BEWARE: NO "break" here as we want to continue to 'breadcrumb' below

                // no break
            case 'breadcrumb':
                $parentDocuments = self::$sphinxReader->getParentDocuments((bool)($conf['userFunc.']['showRoot'] ?? '0'));
                foreach ($parentDocuments as $parent) {
                    if ($parent['link'] === '/') {
                        $link = $this->cObj->getTypoLink_URL($GLOBALS['TSFE']->id);
                    } else {
                        $absolute = RestHelper::relativeToAbsolute($documentRoot . $document, '../' . $parent['link']);
                        $link = $this->getLink(substr($absolute, strlen($documentRoot)));
                    }
                    $data[] = [
                        'title' => $parent['title'],
                        '_OVERRIDE_HREF' => $link,
                    ];
                }
                // Add current page to breadcrumb menu
                $data[] = [
                    'title' => self::$sphinxReader->getTitle(),
                    '_OVERRIDE_HREF' => $this->getLink($document),
                    'ITEM_STATE' => 'CUR',
                ];
                if (!empty($prependParentItems)) {
                    $data = array_merge($prependParentItems, $data);
                    if (self::$sphinxReader->getDocument() === self::$sphinxReader->getDefaultFile() . '/') {
                        // We are at root, remove "current page" from the breadcrumb menu
                        $depth = count($data);
                        unset($data[$depth - 1]);
                        $data[$depth - 2]['ITEM_STATE'] = 'CUR';
                    }
                }
                break;

            case 'updated':
                $limit = \TYPO3\CMS\Core\Utility\MathUtility::forceIntegerInRange($conf['limit'], 0, 100);    // max number of items
                $maxAge = (int)$this->cObj->calc($conf['maxAge']);
                $sortField = 'crdate';

                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('tx_restdoc_toc');
                $conditions = [];

                if (!empty($conf['excludeChapters'])) {
                    $excludeChapters = array_map(
                        static function ($chapter) use ($queryBuilder) {
                            return $queryBuilder->quote($chapter);
                        },
                        GeneralUtility::trimExplode(',', $conf['excludeChapters'])
                    );
                    if (!empty($excludeChapters)) {
                        $conditions[] = $queryBuilder->expr()->notIn('document', $excludeChapters);
                    }
                }
                if ($maxAge > 0) {
                    $conditions[] = $queryBuilder->expr()->gt($sortField, $queryBuilder->createNamedParameter($GLOBALS['SIM_ACCESS_TIME'] - $maxAge, Connection::PARAM_INT));
                }
                // TODO: prefix root entries with the storage UID when using FAL, to prevent clashes with multiple
                //       directories with similar names
                $conditions[] = $queryBuilder->expr()->eq('root', $queryBuilder->createNamedParameter(substr($documentRoot, strlen($basePath)), Connection::PARAM_STR));

                $rows = $queryBuilder
                    ->select('*')
                    ->from('tx_restdoc_toc')
                    ->where(... $conditions)
                    ->orderBy($sortField, 'DESC')
                    ->setMaxResults($limit)
                    ->execute()
                    ->fetchAll();
                foreach ($rows as $row) {
                    $data[] = [
                        'title' => $row['title'] ?: '[no title]',
                        '_OVERRIDE_HREF' => $row['url'],
                        'SYS_LASTCHANGED' => $row[$sortField],
                    ];
                }
                break;
        }

        // Hook for post-processing the menu entries
        foreach (($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['makeMenuArrayHook'] ?? []) as $classRef) {
            $hookObject = GeneralUtility::makeInstance($classRef);
            $params = [
                'documentRoot' => $documentRoot,
                'document' => $document,
                'data' => &$data,
                'pObj' => $this,
            ];
            if (is_callable([$hookObject, 'postProcessMakeMenuArray'])) {
                $hookObject->postProcessTOC($params);
            }
        }

        return $data;
    }

    /**
     * Advertises Sphinx.
     */
    protected function advertiseSphinx(): void
    {
        $storage = self::$sphinxReader->getStorage();
        if ($storage !== null) {
            $storageConfiguration = self::$sphinxReader->getStorage()->getConfiguration();
            $basePath = rtrim($storageConfiguration['basePath'], '/') . '/';
        } else {
            // FAL is not used
            trigger_error('FAL is not used, please upgrade your plugin configuration.', E_USER_DEPRECATED);
            $basePath = Environment::getPublicPath() . '/';
        }
        $metadata = RestHelper::getMetadata($basePath . $this->conf['path']);
        if (!empty($metadata['release'])) {
            $version = $metadata['release'];
        } elseif (!empty($metadata['version'])) {
            $version = $metadata['version'];
        } else {
            $version = '1.0.0';
        }

        $urlRoot = str_replace('___PLACEHOLDER___', '', $this->getLink('___PLACEHOLDER___/', true, $this->conf['rootPage']));
        // Support for RealURL
        if (substr($urlRoot, -5) === '.html') {
            $urlRoot = substr($urlRoot, 0, -5);    // .html suffix is not a must have
        }
        // Trailing slash is however required
        $urlRoot = rtrim($urlRoot, '/') . '/';
        $hasSource = isset($metadata['has_source']) && $metadata['has_source'] && $this->conf['publishSources'];
        $hasSource = $hasSource ? 'true' : 'false';

        $GLOBALS['TSFE']->additionalJavaScript[$this->prefixId . '_sphinx'] = <<<JS
var DOCUMENTATION_OPTIONS = {
    URL_ROOT:    '$urlRoot',
    VERSION:     '$version',
    COLLAPSE_INDEX: false,
    FILE_SUFFIX: '',
    HAS_SOURCE:  $hasSource,
    SEPARATOR: '/'
};
JS;
    }

    /**
     * Generates the Quick Navigation.
     *
     * @return string
     */
    protected function generateQuickNavigation(): string
    {
        $this->renderingConfig = $this->conf['setup.']['QUICK_NAVIGATION.'];

        $documentRoot = self::$sphinxReader->getPath();
        $document = self::$sphinxReader->getDocument();
        $previousDocument = self::$sphinxReader->getPreviousDocument();
        $nextDocument = self::$sphinxReader->getNextDocument();
        $parentDocuments = self::$sphinxReader->getParentDocuments();

        $data = [];
        $data['home_title'] = $this->pi_getLL('home_title', 'Home');
        $data['home_uri'] = $this->getLink('');
        $data['home_uri_absolute'] = $this->getLink('', true);

        if ($previousDocument !== null) {
            $absolute = RestHelper::relativeToAbsolute($documentRoot . $document, '../' . $previousDocument['link']);
            $link = $this->getLink(substr($absolute, strlen($documentRoot)));
            $linkAbsolute = $this->getLink(substr($absolute, strlen($documentRoot)), true);

            $data['previous_title'] = $previousDocument['title'];
            $data['previous_uri'] = $link;
            $data['previous_uri_absolute'] = $linkAbsolute;
        }

        if ($nextDocument !== null) {
            if ($document === $this->getDefaultFile() . '/' && strpos($nextDocument['link'], '../') !== 0) {
                $nextDocumentPath = $documentRoot;
            } else {
                $nextDocumentPath = $documentRoot . $document;
            }
            $absolute = RestHelper::relativeToAbsolute($nextDocumentPath, '../' . $nextDocument['link']);
            $link = $this->getLink(substr($absolute, strlen($documentRoot)));
            $linkAbsolute = $this->getLink(substr($absolute, strlen($documentRoot)), true);

            $data['next_title'] = $nextDocument['title'];
            $data['next_uri'] = $link;
            $data['next_uri_absolute'] = $linkAbsolute;
        }

        if (!empty($parentDocuments)) {
            $parent = array_pop($parentDocuments);
            $absolute = RestHelper::relativeToAbsolute($documentRoot . $document, '../' . $parent['link']);
            $link = $this->getLink(substr($absolute, strlen($documentRoot)));
            $linkAbsolute = $this->getLink(substr($absolute, strlen($documentRoot)), true);

            $data['parent_title'] = $parent['title'];
            $data['parent_uri'] = $link;
            $data['parent_uri_absolute'] = $linkAbsolute;
        }

        if (is_file($documentRoot . 'genindex.fjson')) {
            $link = $this->getLink('genindex/');
            $linkAbsolute = $this->getLink('genindex/', true);

            $data['index_title'] = $this->pi_getLL('index_title', 'Index');
            $data['index_uri'] = $link;
            $data['index_uri_absolute'] = $linkAbsolute;
        }

        $data['has_previous'] = !empty($data['previous_uri']) ? 1 : 0;
        $data['has_next'] = !empty($data['next_uri']) ? 1 : 0;
        $data['has_parent'] = !empty($data['parent_uri']) ? 1 : 0;
        $data['has_index'] = !empty($data['index_uri']) ? 1 : 0;

        // Hook for post-processing the quick navigation
        foreach (($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['quickNavigationHook'] ?? []) as $classRef) {
            $hookObject = GeneralUtility::makeInstance($classRef);
            $params = [
                'documentRoot' => $documentRoot,
                'document' => $document,
                'data' => &$data,
                'pObj' => $this,
            ];
            if (is_callable([$hookObject, 'postProcessQUICK_NAVIGATION'])) {
                $hookObject->postProcessQUICK_NAVIGATION($params);
            }
        }

        if ($this->conf['addHeadPagination']) {
            $paginationPattern = '<link rel="%s" title="%s" href="%s" />';

            if ($data['has_parent']) {
                $GLOBALS['TSFE']->additionalHeaderData[$this->prefixId . '_parent'] = sprintf(
                    $paginationPattern,
                    'top',
                    htmlspecialchars($data['parent_title']),
                    str_replace('&', '&amp;', $data['parent_uri_absolute'])
                );
            }
            if ($data['has_previous']) {
                $GLOBALS['TSFE']->additionalHeaderData[$this->prefixId . '_previous'] = sprintf(
                    $paginationPattern,
                    'prev',
                    htmlspecialchars($data['previous_title']),
                    str_replace('&', '&amp;', $data['previous_uri_absolute'])
                );
            }
            if ($data['has_next']) {
                $GLOBALS['TSFE']->additionalHeaderData[$this->prefixId . '_next'] = sprintf(
                    $paginationPattern,
                    'next',
                    htmlspecialchars($data['next_title']),
                    str_replace('&', '&amp;', $data['next_uri_absolute'])
                );
            }
        }

        /** @var ContentObjectRenderer $contentObj */
        $contentObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObj->start($data);
        $output = $contentObj->cObjGetSingle($this->renderingConfig['renderObj'], $this->renderingConfig['renderObj.']);

        return $output;
    }

    /**
     * Generates the table of references.
     *
     * @return string
     */
    protected function generateReferences(): string
    {
        $output = [];
        $output[] = '<ul class="tx-restdoc-references">';

        $references = self::$sphinxReader->getReferences();
        foreach ($references as $chapter => $refs) {
            $referencesOutput = [];
            foreach ($refs as $reference) {
                if (!$reference['name']) {
                    continue;
                }
                $link = $this->getLink($reference['link'], false, $this->conf['rootPage']);
                $link = str_replace('&amp;', '&', $link);
                $link = str_replace('&', '&amp;', $link);

                $referencesOutput[] = '<dt><a href="' . $link . '">:ref:`' . htmlspecialchars($reference['name']) . '`</a></dt>';
                $referencesOutput[] = '<dd>' . htmlspecialchars($reference['title']) . '</dd>';
            }

            if ($referencesOutput) {
                $output[] = '<li>' . htmlspecialchars($chapter) . ' <dl>';
                $output = array_merge($output, $referencesOutput);
                $output[] = '</dl></li>';
            }
        }

        $output[] = '</tbody>';
        $output[] = '</table>';

        return implode(LF, $output);
    }

    /**
     * Generates the general index.
     *
     * @param string $documentRoot
     * @param string $document
     * @return string
     */
    protected function generateIndex(string $documentRoot, string $document): string
    {
        $linksCategories = [];
        $contentCategories = [];
        $indexEntries = self::$sphinxReader->getIndexEntries();

        foreach ($indexEntries as $indexGroup) {
            $category = $indexGroup[0];
            $anchor = 'tx-restdoc-index-' . htmlspecialchars($category);

            $conf = [
                $this->prefixId => [
                    'doc' => substr($document, 0, -1),
                ]
            ];
            $link = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', $conf);
            $link .= '#' . $anchor;

            $linksCategories[] = '<a href="' . $link . '"><strong>' . htmlspecialchars($category) . '</strong></a>';

            $contentCategory = '<h2 id="' . $anchor . '">' . htmlspecialchars($category) . '</h2>' . LF;
            $contentCategory .= '<div class="tx-restdoc-genindextable">' . LF;
            $contentCategory .= RestHelper::getIndexDefinitionList($documentRoot, $indexGroup[1], [$this, 'getLink']);
            $contentCategory .= '</div>' . LF;

            $contentCategories[] = $contentCategory;
        }

        $output = '<h1>' . htmlspecialchars($this->pi_getLL('index_title', 'Index')) . '</h1>' . LF;
        $output .= '<div class="tx-restdoc-genindex-jumpbox">' . implode(' | ', $linksCategories) . '</div>' . LF;
        $output .= implode(LF, $contentCategories);

        return $output;
    }

    /**
     * Generates the Body.
     *
     * @return string
     */
    protected function generateBody(): string
    {
        $this->renderingConfig = $this->conf['setup.']['BODY.'];

        $body = self::$sphinxReader->getBody(
            [$this, 'getLink'],
            [$this, 'processImage'],
            [$this, 'processTag']
        );

        if (!empty($this->renderingConfig['stdWrap.'])) {
            /** @var ContentObjectRenderer $contentObj */
            $contentObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $body = $contentObj->stdWrap($body, $this->renderingConfig['stdWrap.']);
        }

        return $body;
    }

    /**
     * Generates the search form.
     *
     * @return string
     */
    protected function generateSearchForm(): string
    {
        $searchIndexFile = self::$sphinxReader->getPath() . 'searchindex.json';
        if (!is_file($searchIndexFile)) {
            return 'ERROR: File ' . $this->conf['path'] . 'searchindex.json was not found.';
        }

        $config = [
            'jsLibs' => [
                'Resources/Public/JavaScript/underscore.js',
                'Resources/Public/JavaScript/doctools.js',
                'Resources/Public/JavaScript/searchtools.js',
            ],
            'jsInline' => '',
            'advertiseSphinx' => true,
        ];

        $searchIndexContent = file_get_contents($searchIndexFile);
        $config['jsInline'] = <<<JS
jQuery(function() { Search.setIndex($searchIndexContent); });
JS;

        // Hook for pre-processing the search form
        foreach (($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['searchFormHook'] ?? []) as $classRef) {
            $hookObject = GeneralUtility::makeInstance($classRef);
            $params = [
                'config' => &$config,
                'pObj' => $this,
            ];
            if (is_callable([$hookObject, 'preProcessSEARCH'])) {
                $hookObject->preProcessSEARCH($params);
            }
        }

        foreach ($config['jsLibs'] as $jsLib) {
            $this->includeJsFile($jsLib);
        }
        if ($config['advertiseSphinx']) {
            $this->advertiseSphinx();
        }
        if ($config['jsInline']) {
            $GLOBALS['TSFE']->additionalJavaScript[$this->extKey . '_search'] = $config['jsInline'];
        }

        $action = GeneralUtility::getIndpEnv('REQUEST_URI');
        $parameters = [];
        if (($pos = strpos($action, '?')) !== false) {
            $parameters = GeneralUtility::trimExplode('&', substr($action, $pos + 1));
            $action = substr($action, 0, $pos);
        }
        $hiddenFields = '';
        foreach ($parameters as $parameter) {
            [$key, $value] = explode('=', $parameter);
            if ($key === 'q') {
                continue;
            }
            $hiddenFields .= sprintf('<input type="hidden" name="%s" value="%s" />', $key, $value) . LF;
        }
        $searchPlaceholder = htmlspecialchars($this->pi_getLL('search_placeholder', 'search'));
        $searchAction = htmlspecialchars($this->pi_getLL('search_action', 'search'));

        return <<<HTML
<form action="$action" method="get">
$hiddenFields
<input type="search" name="q" value="" placeholder="$searchPlaceholder" />
<input type="submit" value="$searchAction" />
<span id="search-progress" style="padding-left: 10px"></span>
</form>

<div id="search-results">

</div>
HTML;
    }

    /**
     * Includes a JavaScript library in header.
     *
     * @param string $file
     */
    protected function includeJsFile(string $file): void
    {
        $pathSite = Environment::getPublicPath() . '/';
        $relativeFile = substr(ExtensionManagementUtility::extPath($this->extKey), strlen($pathSite)) . $file;
        $relativeFile = $this->cObj->typoLink_URL(['parameter' => $relativeFile]);
        $GLOBALS['TSFE']->additionalHeaderData[$relativeFile] = '<script type="text/javascript" src="' . $relativeFile . '"></script>';
    }

    /**
     * Generates a link to navigate within a reST documentation project.
     *
     * @param string $document Target document
     * @param bool $absolute Whether absolute URI should be generated
     * @param int $rootPage UID of the page showing the documentation
     * @return string
     * @internal This method is made public to be accessible from a lambda-function scope
     */
    public function getLink(string $document, bool $absolute = false, int $rootPage = 0): string
    {
        if (GeneralUtility::isFirstPartOfStr($document, 'mailto:')) {
            // This is an email address, not a document!
            $link = $this->cObj->typoLink('', [
                'parameter' => $document,
                'returnLast' => 'url',
            ]);
            return $link;
        }

        $urlParameters = [];
        $anchor = '';
        $additionalParameters = '';
        if ($document !== '') {
            if (($pos = strrpos($document, '#')) !== false) {
                $anchor = substr($document, $pos + 1);
                $document = substr($document, 0, $pos);
            }
            if (($pos = strrpos($document, '?')) !== false) {
                $additionalParameters = urldecode(substr($document, $pos + 1));
                $additionalParameters = '&' . str_replace('&amp;', '&', $additionalParameters);
                $document = substr($document, 0, $pos) . '/';
            }
            if (substr($document, -5) === '.html') {
                $document = substr($document, 0, -5) . '/';
            }
            $doc = substr($document, 0, -1);
            if (!empty($doc)) {
                $urlParameters = [
                    $this->prefixId => [
                        'doc' => $doc,
                    ]
                ];
            }
        }
        if (strpos($document, '_downloads/') === 0 || strpos($document, '_images/') === 0) {
            $basePath = self::$current['path'];
            $storage = self::$sphinxReader->getStorage();
            if ($storage !== null) {
                $storageConfiguration = self::$sphinxReader->getStorage()->getConfiguration();
                $basePath = rtrim($storageConfiguration['basePath'], '/') . '/' . $basePath;
            }
            $link = $this->cObj->typoLink_URL(['parameter' => rtrim($basePath, '/') . '/' . $document]);
        } else {
            $typolinkConfig = [
                'parameter' => $rootPage ?: $GLOBALS['TSFE']->id,
                'additionalParams' => '',
                'forceAbsoluteUrl' => $absolute ? 1 : 0,
                'forceAbsoluteUrl.' => [
                    'scheme' => GeneralUtility::getIndpEnv('TYPO3_SSL') ? 'https' : 'http',
                ],
                'returnLast' => 'url',
                'useCacheHash' => $this->pi_checkCHash,
            ];
            if ($urlParameters) {
                $typolinkConfig['additionalParams'] = GeneralUtility::implodeArrayForUrl('', $urlParameters);
            }
            // Prettier to have those additional parameters after the document itself
            $typolinkConfig['additionalParams'] .= $additionalParameters;
            $link = $this->cObj->typoLink('', $typolinkConfig);
            if ($anchor !== '') {
                $link .= '#' . $anchor;
            }
        }
        return $link;
    }

    /**
     * Processes an image.
     *
     * @param array $data
     * @return string
     * @internal This method is made public to be accessible from a lambda-function scope
     */
    public function processImage(array $data): string
    {
        /** @var ContentObjectRenderer $contentObj */
        $contentObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObj->start($data);

        return $contentObj->cObjGetSingle(
            $this->renderingConfig['image.']['renderObj'],
            $this->renderingConfig['image.']['renderObj.']
        );
    }

    /**
     * Processes a tag.
     *
     * @param string $tagName
     * @param string $raw
     * @param array $data
     * @return string
     * @internal This method is made public to be accessible from a lambda-function scope
     */
    public function processTag(string $tagName, string $raw, array $data): string
    {
        $tsKey = $tagName . '.';
        if (empty($this->renderingConfig[$tsKey]['renderObj.'])) {
            // Nothing to do
            return $raw;
        }

        /** @var ContentObjectRenderer $contentObj */
        $contentObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObj->start($data);

        $this->renderingConfig[$tsKey]['renderObj'];
        return $contentObj->cObjGetSingle(
            $this->renderingConfig[$tsKey]['renderObj'],
            $this->renderingConfig[$tsKey]['renderObj.']
        );
    }

    /**
     * Applies stdWrap to a given key in a configuration array.
     *
     * @param array &$conf
     * @param string $baseKey
     */
    protected function applyStdWrap(array &$conf, string $baseKey): void
    {
        if (isset($conf[$baseKey . '.'])) {
            $conf[$baseKey] = $this->cObj->stdWrap($conf[$baseKey], $conf[$baseKey . '.']);
            unset($conf[$baseKey . '.']);
        }
    }

    /**
     * This method performs various initializations.
     *
     * @param array $conf : Plugin configuration, as received by the main() method
     */
    protected function init(array $conf): void
    {
        $this->conf = $conf;

        // Apply stdWrap on a few TypoScript configuration options
        $this->applyStdWrap($this->conf, 'path');
        $this->applyStdWrap($this->conf, 'defaultFile');
        $this->applyStdWrap($this->conf, 'mode');
        $this->applyStdWrap($this->conf, 'rootPage');
        $this->applyStdWrap($this->conf, 'showPermanentLink');
        $this->applyStdWrap($this->conf, 'documentStructureMaxDocuments');
        $this->applyStdWrap($this->conf, 'advertiseSphinx');
        $this->applyStdWrap($this->conf, 'addHeadPagination');
        $this->applyStdWrap($this->conf, 'publishSources');

        // Load the flexform and loop on all its values to override TS setup values
        // Some properties use a different test (more strict than not empty) and yet some others no test at all
        // see http://wiki.typo3.org/index.php/Extension_Development,_using_Flexforms
        $this->pi_initPIflexForm(); // Init and get the flexform data of the plugin

        // Assign the flexform data to a local variable for easier access
        $piFlexForm = $this->cObj->data['pi_flexform'];

        if (is_array($piFlexForm['data'])) {
            $multiValueKeys = [];
            // Traverse the entire array based on the language
            // and assign each configuration option to $this->settings array...
            foreach ($piFlexForm['data'] as $sheet => $data) {
                foreach ($data as $lang => $value) {
                    /** @var array $value */
                    foreach ($value as $key => $val) {
                        $value = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
                        if (trim($value) !== '' && in_array($key, $multiValueKeys, true)) {
                            // Funny, FF contains a comma-separated list of key|value and
                            // we only want to have key...
                            $tempValues = explode(',', $value);
                            $tempKeys = [];
                            foreach ($tempValues as $tempValue) {
                                [$k, $_] = explode('|', $tempValue);
                                $tempKeys[] = $k;
                            }
                            $value = implode(',', $tempKeys);
                        }
                        if (!isset($this->conf[$key]) || trim($value) !== '') {
                            $this->conf[$key] = $value;
                        }
                    }
                }
            }
        }

        self::$sphinxReader = GeneralUtility::makeInstance(SphinxJson::class);

        // New format since TYPO3 v8
        if (preg_match('#^t3://#', $this->conf['path'])) {
            /** @var LinkService $linkService */
            $linkService = GeneralUtility::makeInstance(LinkService::class);
            $data = $linkService->resolveByStringRepresentation($this->conf['path']);
            if ($data['type'] === 'folder') {
                /** @var Folder $folder */
                $folder = $data['folder'];
                $this->conf['path'] = 'file:' . $folder->getCombinedIdentifier();
            }
        }

        if (preg_match('/^file:(\d+):(.*)$/', $this->conf['path'], $matches)) {
            /** @var StorageRepository $storageRepository */
            $storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
            /** @var ResourceStorage $storage */
            $storage = $storageRepository->findByUid((int)$matches[1]);
            $storageRecord = $storage->getStorageRecord();
            if ($storageRecord['driver'] === 'Local') {
                $this->conf['path'] = substr($matches[2], 1);
                self::$sphinxReader->setStorage($storage);
            } else {
                throw new \RuntimeException('Access to the documentation requires an unsupported driver: ' . $storageRecord['driver'], 1365688549);
            }
        }

        if (isset($this->conf['defaultFile'])) {
            self::$defaultFile = $this->conf['defaultFile'];
        }

        if (GeneralUtility::inList('REFERENCES,SEARCH', $this->conf['mode'])) {
            $this->conf['rootPage'] = (int)$this->conf['rootPage'];
        } else {
            $this->conf['rootPage'] = 0;
        }
    }

    /**
     * Loads the locallang file.
     *
     * @param string $languageFilePath
     */
    public function pi_loadLL($languageFilePath = '')
    {
        if (!$this->LOCAL_LANG_loaded && $this->scriptRelPath) {
            $basePath = 'EXT:' . $this->extKey . '/Resources/Private/Language/locallang.xlf';

            // Read the strings in the required charset (since TYPO3 4.2)
            /** @var LocalizationFactory $languageFactory */
            $languageFactory = GeneralUtility::makeInstance(LocalizationFactory::class);
            $this->LOCAL_LANG = $languageFactory->getParsedData($basePath, $this->LLkey, 'utf-8');
            if ($this->altLLkey) {
                $tempLOCAL_LANG = $languageFactory->getParsedData($basePath, $this->altLLkey);
                $this->LOCAL_LANG = array_merge(is_array($this->LOCAL_LANG) ? $this->LOCAL_LANG : [], $tempLOCAL_LANG);
                unset($tempLOCAL_LANG);
            }

            // Overlaying labels from TypoScript (including fictitious language keys for non-system languages!):
            $confLL = $this->conf['_LOCAL_LANG.'];
            if (is_array($confLL)) {
                foreach ($confLL as $k => $lA) {
                    if (is_array($lA)) {
                        $k = substr($k, 0, -1);
                        foreach ($lA as $llK => $llV) {
                            if (!is_array($llV)) {
                                // Internal structure is from XLIFF
                                $this->LOCAL_LANG[$k][$llK][0]['target'] = $llV;

                                // For labels coming from the TypoScript (database) the charset is assumed to be "forceCharset" and if that is not set, assumed to be that of the individual system languages
                                $this->LOCAL_LANG_charset[$k][$llK] = $GLOBALS['TYPO3_CONF_VARS']['BE']['forceCharset'] ?: $GLOBALS['TSFE']->csConvObj->charSetArray[$k];
                            }
                        }
                    }
                }
            }
        }
        $this->LOCAL_LANG_loaded = 1;
    }
}
