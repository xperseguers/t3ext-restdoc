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

namespace Causal\Restdoc\Tests\Functional\ContentObject;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Testcase for class \Causal\Restdoc\ContentObject\RestMetadataContentObject.
 */
class RestMetadataContentObjectTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /** @var string */
    protected $fixturePath;

    /** @var array */
    protected $backupCObjTypeAndClass;

    /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
    protected $contentObject;

    public function setUp(): void
    {
        $pathSite = Environment::getPublicPath() . '/';
        $this->fixturePath = substr(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('restdoc') . 'Tests/Functional/Fixtures/_build/json/', strlen($pathSite));

        $this->backupCObjTypeAndClass = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = [
            0 => 'REST_METADATA',
            1 => 'EXT:restdoc/Classes/ContentObject/RestMetadataContentObject.php:Causal\\Restdoc\\ContentObject\\RestMetadataContentObject',
        ];

        $this->contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $this->contentObject->start([]);

        $GLOBALS['TT'] = GeneralUtility::makeInstance(NullTimeTracker::class);
        if ($GLOBALS['TSFE'] === null) {
            $GLOBALS['TSFE'] = new \stdClass();
        }
        $GLOBALS['TSFE']->cObjectDepthCounter += 3;
    }

    public function tearDown(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'] = $this->backupCObjTypeAndClass;
        unset($this->fixturePath, $this->backupCObjTypeAndClass, $this->contentObject);
    }

    /**
     * @test
     */
    public function canExtractProject(): void
    {
        $config = [
            'path' => $this->fixturePath,
            'cObject' => 'TEXT',
            'cObject.' => [
                'field' => 'project',
            ],
        ];
        $value = $this->contentObject->cObjGetSingle('REST_METADATA', $config);
        $expected = 'Test Project';

        self::assertEquals($expected, $value);
    }

    /**
     * @test
     */
    public function canProcessCopyrightWithDynamicPath(): void
    {
        $pathParts = explode('/', $this->fixturePath, 2);
        $config = [
            'path' => $pathParts[0],
            'path.' => [
                'wrap' => '|/' . $pathParts[1],
            ],
            'cObject' => 'TEXT',
            'cObject.' => [
                'field' => 'copyright',
                'noTrimWrap' => '|© ||'
            ],
        ];
        $value = $this->contentObject->cObjGetSingle('REST_METADATA', $config);
        $expected = '© 2013, Xavier Perseguers';

        self::assertEquals($expected, $value);
    }
}
