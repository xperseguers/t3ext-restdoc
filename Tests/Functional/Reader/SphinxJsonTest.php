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

namespace Causal\Restdoc\Tests\Functional\Reader;

use TYPO3\CMS\Core\Core\Environment;
use Causal\Restdoc\Reader\SphinxJson;

/**
 * Testcase for class \Causal\Restdoc\Reader\SphinxJson.
 */
class SphinxJsonTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /** @var string */
    protected $fixturePath;

    /** @var \Causal\Restdoc\Reader\SphinxJson */
    protected $sphinxReader;

    /** @var mixed */
    protected $buffer;

    public function setUp(): void
    {
        $typo3Branch = class_exists(\TYPO3\CMS\Core\Information\Typo3Version::class)
            ? (new \TYPO3\CMS\Core\Information\Typo3Version())->getBranch()
            : TYPO3_branch;
        $pathSite = version_compare($typo3Branch, '9.0', '<')
            ? PATH_site
            : Environment::getPublicPath() . '/';
        $this->fixturePath = substr(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('restdoc') . 'Tests/Functional/Fixtures/_build/json/', strlen($pathSite));
        $this->sphinxReader = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(SphinxJson::class);
    }

    public function tearDown(): void
    {
        unset($this->fixturePath, $this->sphinxReader, $this->buffer);
    }

    /**
     * @expectedException \RuntimeException
     * @test
     */
    public function pathIsMandatory(): void
    {
        // No path specified
        $this->sphinxReader->load();
    }

    /**
     * @expectedException \RuntimeException
     * @test
     */
    public function documentIsMandatory(): void
    {
        $typo3Branch = class_exists(\TYPO3\CMS\Core\Information\Typo3Version::class)
            ? (new \TYPO3\CMS\Core\Information\Typo3Version())->getBranch()
            : TYPO3_branch;
        $pathSite = version_compare($typo3Branch, '9.0', '<')
            ? PATH_site
            : Environment::getPublicPath() . '/';
        $this->sphinxReader->setPath($pathSite . $this->fixturePath);
        $this->sphinxReader->load();
    }

    /**
     * @test
     */
    public function canLoadExistingDocument(): void
    {
        $this->assertTrue($this->initializeReader('index/'));
    }

    /**
     * @test
     */
    public function canExtractTitle(): void
    {
        $this->initializeReader('intro/');
        $value = $this->sphinxReader->getTitle();
        $expected = 'Introduction';
        $this->assertEquals($expected, $value);
    }

    /**
     * @test
     */
    public function canExtractSourceName(): void
    {
        $this->initializeReader('intro/');
        $value = $this->sphinxReader->getSourceName();
        $expected = 'intro.txt';
        $this->assertEquals($expected, $value);
    }

    /**
     * @test
     */
    public function canExtractCurrentPageName(): void
    {
        $this->initializeReader('intro/');
        $value = $this->sphinxReader->getCurrentPageName();
        $expected = 'intro';
        $this->assertEquals($expected, $value);
    }

    /**
     * @test
     */
    public function canRetrieveListOfLabels(): void
    {
        $this->initializeReader('index/');
        $references = $this->sphinxReader->getReferences();

        $this->assertEquals('Welcome to Test Project\'s documentation!', $references['#']['start']['title']);
        $this->assertEquals('Introduction', $references['intro']['introduction']['title']);
        $this->assertEquals('Some Other Chapter', $references['subdirectory']['some_other_chapter']['title']);
    }

    /**
     * @test
     */
    public function canExtractTableOfContentsForIndex(): void
    {
        $this->initializeReader('index/');
        $this->buffer = array();

        $toc = $this->sphinxReader->getTableOfContents(array($this, 'getLink'));
        $this->assertNotSame($toc, '');

        $expectedLinks = array(
            'index/#',
            'index/#indices-and-tables',
        );
        $this->assertEquals($expectedLinks, $this->buffer);
    }

    /**
     * @test
     */
    public function canExtractTableOfContentsForChapterIntroduction(): void
    {
        $this->initializeReader('intro/');
        $this->buffer = array();

        $toc = $this->sphinxReader->getTableOfContents(array($this, 'getLink'));
        $this->assertNotSame($toc, '');

        $expectedLinks = array(
            'intro/#',
        );
        $this->assertEquals($expectedLinks, $this->buffer);
    }

    /**
     * @test
     */
    public function canExtractPreviousDocumentInSameDirectory(): void
    {
        $this->initializeReader('intro/');
        $value = $this->sphinxReader->getPreviousDocument();
        $expected = array(
            'link' => '../',
            'title' => 'Welcome to Test Project&#8217;s documentation!',
        );
        $this->assertEquals($expected, $value);
    }

    /**
     * @test
     */
    public function canExtractPreviousDocumentInOtherDirectory(): void
    {
        $this->initializeReader('subdirectory/index/');
        $value = $this->sphinxReader->getPreviousDocument();
        $expected = array(
            'link' => '../intro/',
            'title' => 'Introduction',
        );
        $this->assertEquals($expected, $value);
    }

    /**
     * @test
     */
    public function canExtractNextDocumentInSameDirectory(): void
    {
        $this->initializeReader('index/');
        $value = $this->sphinxReader->getNextDocument();
        $expected = array(
            'link' => 'intro/',
            'title' => 'Introduction',
        );
        $this->assertEquals($expected, $value);
    }

    /**
     * @test
     */
    public function canExtractNextDocumentInOtherDirectory(): void
    {
        $this->initializeReader('intro/');
        $value = $this->sphinxReader->getNextDocument();
        $expected = array(
            'link' => '../subdirectory/',
            'title' => 'Some other chapter',
        );
        $this->assertEquals($expected, $value);
    }

    /**
     * @test
     */
    public function canExtractIndexEntries(): void
    {
        $this->initializeReader('genindex/');
        $value = $this->sphinxReader->getIndexEntries();
        $this->assertIsArray($value, 'null instead of array');
        $this->assertTrue(count($value) > 0, 'Expected at least 1 index entry');
    }

    /**
     * Generates a link to navigate within a reST documentation project.
     *
     * @param string $document Target document
     * @param bool $absolute Whether absolute URI should be generated
     * @param int $rootPage UID of the page showing the documentation
     * @return string
     */
    public function getLink(string $document, bool $absolute = false, int $rootPage = 0): string
    {
        $this->buffer[] = $document;
        return $document;
    }

    /**
     * Initializes the reader.
     *
     * @param string $document
     * @return bool
     */
    protected function initializeReader(string $document): bool
    {
        $typo3Branch = class_exists(\TYPO3\CMS\Core\Information\Typo3Version::class)
            ? (new \TYPO3\CMS\Core\Information\Typo3Version())->getBranch()
            : TYPO3_branch;
        $pathSite = version_compare($typo3Branch, '9.0', '<')
            ? PATH_site
            : Environment::getPublicPath() . '/';
        $this->sphinxReader->setPath($pathSite . $this->fixturePath);
        $this->sphinxReader->setDocument($document);
        return $this->sphinxReader->load();
    }

}
