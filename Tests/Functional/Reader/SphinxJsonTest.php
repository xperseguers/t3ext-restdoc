<?php
namespace Causal\Restdoc\Tests\Functional\Reader;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Xavier Perseguers <xavier@causal.ch>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Testcase for class Tx_Restdoc_Reader_SphinxJson.
 */
class SphinxJsonTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/** @var string */
	protected $fixturePath;

	/** @var \Tx_Restdoc_Reader_SphinxJson */
	protected $sphinxReader;

	public function setUp() {
		$this->fixturePath = substr(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('restdoc') . 'Tests/Functional/Fixtures/_build/json/', strlen(PATH_site));
		$this->sphinxReader = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_Restdoc_Reader_SphinxJson');
	}

	public function tearDown() {
		unset($this->fixturePath);
		unset($this->sphinxReader);
	}

	/**
	 * @expectedException \RuntimeException
	 * @test
	 */
	public function pathIsMandatory() {
		// No path specified
		$this->sphinxReader->load();
	}

	/**
	 * @expectedException \RuntimeException
	 * @test
	 */
	public function documentIsMandatory() {
		$this->sphinxReader->setPath(PATH_site . $this->fixturePath);
		$this->sphinxReader->load();
	}

	/**
	 * @test
	 */
	public function canLoadExistingDocument() {
		$this->assertTrue($this->initializeReader('index/'));
	}

	/**
	 * @test
	 */
	public function canRetrieveListOfLabels() {
		$this->initializeReader('index/');
		$references = $this->sphinxReader->getReferences();

		$this->assertEquals('Welcome to Test Project\'s documentation!', $references['#']['start']['title']);
		$this->assertEquals('Introduction', $references['intro']['introduction']['title']);
		$this->assertEquals('Some Other Chapter', $references['subdirectory']['some_other_chapter']['title']);
	}

	/**
	 * Initializes the reader.
	 *
	 * @param string $document
	 * @return boolean
	 */
	protected function initializeReader($document) {
		$this->sphinxReader->setPath(PATH_site . $this->fixturePath);
		$this->sphinxReader->setDocument($document);
		return $this->sphinxReader->load();
	}

}

?>