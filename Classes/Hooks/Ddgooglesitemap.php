<?php
namespace Causal\Restdoc\Hooks;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Integration of the ReStructured documentation into a Google sitemap.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_restdoc
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class Ddgooglesitemap
{

    /** @var array */
    protected $documentationPlugins;

    /**
     * Inserts the documentation structure in the sitemap of current page.
     *
     * @param array $params
     * @return void
     */
    public function postProcessPageInfo(array $params)
    {
        $this->initializeDocumentationPlugins($params['pageInfo']['uid']);

        foreach ($this->documentationPlugins as $documentationPlugin) {
            $this->renderDocumentationSitemap($documentationPlugin, $params);
        }
    }

    /**
     * Renders the documentation structure associated to a restdoc plugin.
     *
     * @param array $documentationPlugin
     * @param array $params
     * @return void
     */
    protected function renderDocumentationSitemap(array $documentationPlugin, array $params)
    {
        $documentList = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'lastmod, title, url',
            'tx_restdoc_toc',
            'pid=' . intval($documentationPlugin['pid'])
        );

        while (!empty($documentList) && $params['generatedItemCount'] - $params['offset'] <= $params['limit']) {
            $documentInfo = array_shift($documentList);
            if ($params['generatedItemCount'] >= $params['offset']) {
                echo $params['renderer']->renderEntry(
                    str_replace('&', '&amp;', $documentInfo['url']),
                    $documentInfo['title'],
                    $GLOBALS['EXEC_TIME'],
                    $this->getChangeFrequency($documentInfo),
                    '' /* keywords */,
                    5 /* priority */
                );
            }
            $params['generatedItemCount']++;
        }
    }

    /**
     * Returns the change frequency of a document.
     *
     * @param array $documentInfo
     * @return string
     * @see tx_ddgooglesitemap_pages::getChangeFrequency()
     */
    protected function getChangeFrequency(array $documentInfo)
    {
        $timeValues = GeneralUtility::intExplode(',', $documentInfo['lastmod'], true);
        $timeValues[] = $GLOBALS['EXEC_TIME'];
        sort($timeValues, SORT_NUMERIC);
        $sum = 0;
        for ($i = count($timeValues) - 1; $i > 0; $i--) {
            $sum += ($timeValues[$i] - $timeValues[$i - 1]);
        }
        $average = ($sum / (count($timeValues) - 1));
        return ($average >= 180 * 24 * 60 * 60 ? 'yearly' :
            ($average <= 24 * 60 * 60 ? 'daily' :
                ($average <= 60 * 60 ? 'hourly' :
                    ($average <= 14 * 24 * 60 * 60 ? 'weekly' : 'monthly'))));
    }

    /**
     * Initializes the list of tx_restdoc_pi1 plugins publicly accessible on
     * a given page.
     *
     * @param integer $uid A page uid
     * @return void
     */
    protected function initializeDocumentationPlugins($uid)
    {
        $plugins = $this->getDatabaseConnection()->exec_SELECTgetRows(
            '*',
            'tt_content',
            'pid=' . intval($uid) .
            ' AND CType=\'list\' AND list_type=\'restdoc_pi1\'' .
            $GLOBALS['TSFE']->sys_page->enableFields('tt_content', 0)
        );
        $this->documentationPlugins = $plugins;
    }

    /**
     * Returns the database connection.
     *
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

}
