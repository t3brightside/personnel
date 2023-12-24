<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use Brightside\Personnel\Hooks\ContentPostProcessor;

defined('TYPO3') || die('Access denied.');

(function () {
    $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
    if ($versionInformation->getMajorVersion() < 12) {
        ExtensionManagementUtility::addPageTSConfig('
            @import "EXT:personnel/Configuration/page.tsconfig"
        ');
    }

    $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon(
        'mimetypes-x-content-personnel',SvgIconProvider::class,
        ['source' => 'EXT:personnel/Resources/Public/Icons/mimetypes-x-content-personnel.svg']
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] =
    ContentPostProcessor::class . '->render';

    ExtensionUtility::configurePlugin(
        'personnel',
        'Personnel',
        [
            'Personnel' => 'personnel'
        ]
    );
})();
