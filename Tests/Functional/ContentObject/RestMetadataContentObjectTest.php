<?php
namespace Causal\Restdoc\Tests\Functional\ContentObject;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2014 Xavier Perseguers <xavier@causal.ch>
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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Testcase for class Tx_Restdoc_ContentObject_RestMetadataContentObject.
 */
class RestMetadataContentObjectTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/** @var string */
	protected $fixturePath;

	/** @var array */
	protected $backupCObjTypeAndClass;

	/** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
	protected $contentObject;

	public function setUp() {
		$this->fixturePath = substr(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('restdoc') . 'Tests/Functional/Fixtures/_build/json/', strlen(PATH_site));

		$this->backupCObjTypeAndClass = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'];
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = array(
			0 => 'REST_METADATA',
			1 => 'EXT:restdoc/Classes/ContentObject/RestMetadataContentObject.php:Tx_Restdoc_ContentObject_RestMetadataContentObject',
		);

		$this->contentObject = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
		$this->contentObject->start(array());

		$GLOBALS['TT'] =  GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TimeTracker\\NullTimeTracker');
		if ($GLOBALS['TSFE'] === NULL) {
			$GLOBALS['TSFE'] = new \stdClass();
		}
		$GLOBALS['TSFE']->cObjectDepthCounter += 3;
	}

	public function tearDown() {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'] = $this->backupCObjTypeAndClass;
		unset($this->fixturePath);
		unset($this->backupCObjTypeAndClass);
		unset($this->contentObject);
	}

	/**
	 * @test
	 */
	public function canExtractProject() {
		$config = array(
			'path' => $this->fixturePath,
			'cObject' => 'TEXT',
			'cObject.' => array(
				'field' => 'project',
			),
		);
		$value = $this->contentObject->cObjGetSingle('REST_METADATA', $config);
		$expected = 'Test Project';

		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 */
	public function canProcessCopyrightWithDynamicPath() {
		$pathParts = explode('/', $this->fixturePath, 2);
		$config = array(
			'path' => $pathParts[0],
			'path.' => array(
				'wrap' => '|/' . $pathParts[1],
			),
			'cObject' => 'TEXT',
			'cObject.' => array(
				'field' => 'copyright',
				'noTrimWrap' => '|© ||'
			),
		);
		$value = $this->contentObject->cObjGetSingle('REST_METADATA', $config);
		$expected = '© 2013, Xavier Perseguers';

		$this->assertEquals($expected, $value);
	}

}
