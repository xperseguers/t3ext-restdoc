<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "restdoc".
 *
 * Auto generated 22-04-2014 22:02
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Sphinx Documentation Viewer Plugin',
    'description' => 'Seamlessly embeds Sphinx/reStructuredText-based documentation into your TYPO3 website. Instead of publishing your various manual, in-house documents, guides, references, ... solely as PDF, render them as JSON and use this extension to show them as part of your website to enhance the overall user experience and Search Engine Optimization (SEO). Lets you merge the chapter structure with the breadcrumb menu and much more. Documentation styles automatically inherit from your corporate design.',
    'category' => 'plugin',
    'author' => 'Xavier Perseguers (Causal)',
    'author_company' => 'Causal Sàrl',
    'author_email' => 'xavier@causal.ch',
    'shy' => '',
    'dependencies' => '',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'version' => '1.7.0-dev',
    'constraints' => [
        'depends' => [
            'php' => '7.2.0-7.4.99',
            'typo3' => '8.7.0-10.3.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'realurl' => '',
        ],
    ],
    '_md5_values_when_last_written' => '',
    'suggests' => [],
    'autoload' => [
        'psr-4' => ['Causal\\Restdoc\\' => 'Classes']
    ],
];
