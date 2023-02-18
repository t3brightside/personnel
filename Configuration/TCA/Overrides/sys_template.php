<?php
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile(
    'personnel',
    'Configuration/TypoScript/',
    'Personnel'
);
