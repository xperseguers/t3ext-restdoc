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

namespace Causal\Restdoc\Hooks;

/**
 * RealURL auto-configuration and segment decoder.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_restdoc
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class Realurl
{

    /**
     * Generates additional RealURL configuration and merges it with provided configuration.
     *
     * @param array $params
     * @param \tx_realurl_autoconfgen|object $pObj
     * @return array
     */
    public function registerDefaultConfiguration(array $params, $pObj)
    {
        $fixedPostVarsConfiguration = array();

        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['restdoc']);
        if (isset($settings['enable_slash_as_separator']) && (bool)$settings['enable_slash_as_separator']) {
            $fixedPostVarsConfiguration = $this->getFixedPostVarsConfiguration();
        }

        $defaultConfiguration = array_merge_recursive(
            $params['config'],
            $fixedPostVarsConfiguration,
            array(
                'postVarSets' => array(
                    '_DEFAULT' => array(
                        'chapter' => array(
                            array(
                                'GETvar' => 'tx_restdoc_pi1[doc]',
                            ),
                        ),
                    ),
                ),
            )
        );

        return $defaultConfiguration;
    }

    /**
     * Pre-process an URL and ensure access to source files of a reStructuredText
     * chapter is properly passed to RealURL for decoding by changing the .txt file
     * extension into .html.
     *
     * @param array $parameters
     * @return void
     */
    public function decodeSpURL_preProc(array $parameters)
    {
        $segments = explode('/', $parameters['URL']);
        if ($segments[1] === '_sources' && substr($parameters['URL'], -4) === '.txt') {
            $suffix = (bool)$parameters['pObj']->extConf['fileName']['acceptHTMLsuffix'] ? '.html' : '/';
            $parameters['URL'] = substr($parameters['URL'], 0, -4) . $suffix;
        }
    }

    /**
     * This methods will "eat" every remaining segment in the URL to make it part
     * of the requested document.
     *
     * @param array $parameters
     * @return string
     */
    public function decodeSpURL_getSequence(array $parameters)
    {
        $value = $parameters['value'];

        if ((bool)$parameters['decodeAlias']) {
            if (!empty($parameters['pathParts'])) {
                // Eat every remaining segment
                $value .= '/' . implode('/', $parameters['pathParts']);
                $parameters['pathParts'] = array();
            }
        }

        return $value;
    }

    /**
     * Generates a default "fixedPostVars" configuration for RealURL
     * based on pages containing a restdoc plugin.
     *
     * @return array
     */
    protected function getFixedPostVarsConfiguration()
    {
        $fixedPostVarsConfiguration = array();

        // Search pages with a restdoc plugin
        $databaseConnection = $this->getDatabaseConnection();
        $pages = $databaseConnection->exec_SELECTgetRows(
            'DISTINCT pid',
            'tt_content',
            'list_type=' . $databaseConnection->fullQuoteStr('restdoc_pi1', 'tt_content') .
            ' AND deleted=0 AND hidden=0',
            '',
            '',
            '',
            'pid'
        );
        $pages = array_keys($pages);

        if (!empty($pages)) {
            $fixedPostVarsConfiguration['fixedPostVars'] = array_fill_keys($pages, 'restdoc_advanced_url');
            $fixedPostVarsConfiguration['fixedPostVars']['restdoc_advanced_url'] = array(
                array(
                    'GETvar' => 'tx_restdoc_pi1[doc]',
                    'userFunc' => 'Causal\\Restdoc\\Hooks\\Realurl->decodeSpURL_getSequence',
                ),
            );
        }

        return $fixedPostVarsConfiguration;
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
