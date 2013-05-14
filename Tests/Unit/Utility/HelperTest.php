<?php
namespace Causal\Restdoc\Tests\Unit\Utility;

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
 * Testcase for class Tx_Restdoc_Utility_Helper.
 */
class HelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function oneLevelMenuHtmlCanBeExtractedAsArray() {
		$html = <<<HTML
<div>
	<ul>
		<li>Menu 1</li>
		<li>Menu 2</li>
		<li>Menu 3</li>
	</ul>
</div>
HTML;

		$expected = array(
			'ul' => array(
				'li' => array(
					0 => 'Menu 1',
					1 => 'Menu 2',
					2 => 'Menu 3',
				)
			),
		);

		$arr = \Tx_Restdoc_Utility_Helper::xmlstr_to_array($html);
		$this->assertEquals($expected, $arr);
	}

	/**
	 * @test
	 */
	public function twoLevelMenuHtmlCanBeExtractedAsArray() {
		$html = <<<HTML
<div>
	<ul>
		<li>Menu 1</li>
		<li>Menu 2
			<ul>
				<li>Menu 2.1</li>
			</ul>
		</li>
		<li>Menu 3</li>
	</ul>
</div>
HTML;

		$expected = array(
			'ul' => array(
				'li' => array(
					0 => 'Menu 1',
					1 => array(
						0 => 'Menu 2',
						'ul' => array(
							'li' => 'Menu 2.1'
						),
					),
					2 => 'Menu 3',
				)
			),
		);

		$arr = \Tx_Restdoc_Utility_Helper::xmlstr_to_array($html);
		$this->assertEquals($expected, $arr);
	}

	/**
	 * @test
	 */
	public function menuCanContainFormatting() {
		$html = <<<HTML
<div>
	<ul>
		<li>Some <em>important</em> word</li>
		<li><strong>Very</strong> important</li>
	</ul>
</div>
HTML;

		$expected = array(
			'ul' => array(
				'li' => array(
					0 => 'Some <em>important</em> word',
					1 => '<strong>Very</strong> important',
				),
			),
		);

		$arr = \Tx_Restdoc_Utility_Helper::xmlstr_to_array($html);
		$this->assertEquals($expected, $arr);
	}

}

?>