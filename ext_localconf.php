<?php
declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Brightside\Personnel\Hooks\ContentPostProcessor;

defined('TYPO3') || die('Access denied.');

(function () {
    ExtensionUtility::configurePlugin(
        'personnel',
        'Personnel',
        [
            'Personnel' => 'personnel'
        ],
        [], 
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT 
    );
})();
