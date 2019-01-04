<?php
defined('TYPO3_MODE') || die('Access denied.');

// Add an entry in the static template list found in sys_templates for static TS

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	'personnel',
	'Configuration/TypoScript/',
	'Personnel'
);

// For other extensions to determine in TS if personnell is loaded
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants('
	personnel.isLoaded = 1
');
