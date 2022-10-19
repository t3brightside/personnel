<?php
defined('TYPO3_MODE') || defined('TYPO3') || die('Access denied.');

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile(
    'personnel',
    'Configuration/TypoScript/',
    'Personnel'
);
