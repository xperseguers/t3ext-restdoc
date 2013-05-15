<?php
namespace Causal\Restdoc\Tests\Functional\ContentObject;

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
 * Testcase for class Tx_Restdoc_ContentObject_RestMetadataContentObject.
 */
class RestMetadataContentObjectTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/** @var string */
	protected $temporaryFilename;

	/** @var array */
	protected $data;

	/** @var array */
	protected $backupCObjTypeAndClass;

	/** @var \tslib_cObj */
	protected $contentObject;

	public function setUp() {
		$this->temporaryFilename = PATH_site . 'typo3temp/globalcontext.json';
		$this->data = array(
			'project' => 'Unit Test Project for EXT:restdoc',
			'copyright' => '2013, Xavier Perseguers',
		);
		\TYPO3\CMS\Core\Utility\GeneralUtility::writeFile($this->temporaryFilename, json_encode($this->data));

		$this->backupCObjTypeAndClass = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'];
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = array(
			0 => 'REST_METADATA',
			1 => 'EXT:restdoc/Classes/ContentObject/RestMetadataContentObject.php:Tx_Restdoc_ContentObject_RestMetadataContentObject',
		);

		$this->contentObject = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tslib_cObj');
		$this->contentObject->start(array());

		$GLOBALS['TT'] =  \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TimeTracker\\NullTimeTracker');
		$GLOBALS['TSFE']->cObjectDepthCounter += 3;
	}

	public function tearDown() {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'] = $this->backupCObjTypeAndClass;

		unlink($this->temporaryFilename);
		unset($this->data);
		unset($this->backupCObjTypeAndClass);
		unset($this->contentObject);
	}

	/**
	 * @test
	 */
	public function canExtractProject() {
		$config = array(
			'path' => 'typo3temp/',
			'cObject' => 'TEXT',
			'cObject.' => array(
				'field' => 'project',
			),
		);
		$value = $this->contentObject->cObjGetSingle('REST_METADATA', $config);
		$expected = $this->data['project'];

		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 */
	public function canProcessCopyrightWithDynamicPath() {
		$config = array(
			'path' => 'typo3',
			'path.' => array(
				'wrap' => '|temp/',
			),
			'cObject' => 'TEXT',
			'cObject.' => array(
				'field' => 'copyright',
				'noTrimWrap' => '|© ||'
			),
		);
		$value = $this->contentObject->cObjGetSingle('REST_METADATA', $config);
		$expected = '© ' . $this->data['copyright'];

		$this->assertEquals($expected, $value);
	}

}

?>