<?php
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile(
    'personnel',
    'Configuration/TypoScript/',
    'Personnel'
);

ExtensionManagementUtility::addStaticFile(
    'personnel',
    'Configuration/TypoScript/NewsAuthor',
    'Personnel - News author'
);
