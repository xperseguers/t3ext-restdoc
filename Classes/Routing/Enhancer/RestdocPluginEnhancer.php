<?php
declare(strict_types=1);

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

namespace Causal\Restdoc\Routing\Enhancer;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Routing\Aspect\StaticMappableAspectInterface;
use TYPO3\CMS\Core\Routing\Enhancer\PluginEnhancer;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Routing\Route;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RestdocPluginEnhancer
 */
class RestdocPluginEnhancer extends PluginEnhancer
{

    /** @noinspection MagicMethodsValidityInspection */
    public function __construct(array $configuration)
    {
        // Set defaults as if fully configured like with PluginEnhancer
        $configuration['namespace'] = 'tx_restdoc_pi1';
        if (!isset($configuration['routePath'])) {
            $configuration['routePath'] = '/{doc}';
            $configuration['requirements']['doc'] = '.+';
        }
        if (empty($configuration['limitToPages'])) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tt_content');
            $rows = $queryBuilder
                ->selectLiteral('DISTINCT ' . $queryBuilder->quoteIdentifier('pid'))
                ->from('tt_content')
                ->where(
                    $queryBuilder->expr()->eq('CType', $queryBuilder->quote('list')),
                    $queryBuilder->expr()->eq('list_type', $queryBuilder->quote('restdoc_pi1')),
                    $queryBuilder->expr()->eq('sys_language_uid', 0)
                )
                ->execute()
                ->fetchAll();
            $pages = [];
            foreach ($rows as $row) {
                $pages[] = $row['pid'];
            }
            $configuration['limitToPages'] = $pages;
            trigger_error('limitToPages has not been configured for Routing Enhancer RestdocPlugin, auto-configuring to: ' . json_encode($pages), E_USER_DEPRECATED);
        }

        $this->configuration = $configuration;
        $this->namespace = $this->configuration['namespace'] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function buildResult(Route $route, array $results, array $remainingQueryParameters = []): PageArguments
    {
        $variableProcessor = $this->getVariableProcessor();
        // determine those parameters that have been processed
        $parameters = array_intersect_key(
            $results,
            array_flip($route->compile()->getPathVariables())
        );
        // strip of those that where not processed (internals like _route, etc.)
        $internals = array_diff_key($results, $parameters);
        $matchedVariableNames = array_keys($parameters);

        $staticMappers = $route->filterAspects([StaticMappableAspectInterface::class], $matchedVariableNames);
        $dynamicCandidates = array_diff_key($parameters, $staticMappers, ['tx_restdoc_pi1__doc' => '']);

        // all route arguments
        $routeArguments = $this->inflateParameters($parameters, $internals);
        // dynamic arguments, that don't have a static mapper
        $dynamicArguments = $variableProcessor
            ->inflateNamespaceParameters($dynamicCandidates, $this->namespace);
        // static arguments, that don't appear in dynamic arguments
        $staticArguments = ArrayUtility::arrayDiffAssocRecursive($routeArguments, $dynamicArguments);

        $page = $route->getOption('_page');
        $pageId = (int)(isset($page['t3ver_oid']) && $page['t3ver_oid'] > 0 ? $page['t3ver_oid'] : $page['uid']);
        $pageId = (int)($page['l10n_parent'] > 0 ? $page['l10n_parent'] : $pageId);
        // See PageSlugCandidateProvider where this is added.
        if ($page['MPvar'] ?? '') {
            $routeArguments['MP'] = $page['MPvar'];
        }
        $type = $this->resolveType($route, $remainingQueryParameters);
        return new PageArguments($pageId, $type, $routeArguments, $staticArguments, $remainingQueryParameters);
    }
}
