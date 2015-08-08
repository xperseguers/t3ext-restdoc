<?php
namespace Causal\Restdoc\Tests\Unit\Utility;

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

use Causal\Restdoc\Utility\RestHelper;

/**
 * Testcase for class \Causal\Restdoc\Utility\RestHelper.
 */
class RestHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @test
     */
    public function oneLevelMenuHtmlCanBeExtractedAsArray()
    {
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

        $arr = RestHelper::xmlstr_to_array($html);
        $this->assertEquals($expected, $arr);
    }

    /**
     * @test
     */
    public function twoLevelMenuHtmlCanBeExtractedAsArray()
    {
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

        $arr = RestHelper::xmlstr_to_array($html);
        $this->assertEquals($expected, $arr);
    }

    /**
     * @test
     */
    public function menuCanContainFormatting()
    {
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

        $arr = RestHelper::xmlstr_to_array($html);
        $this->assertEquals($expected, $arr);
    }

}
