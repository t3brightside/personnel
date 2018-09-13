<?php
defined('TYPO3_MODE') or die();

  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:personnel/Configuration/PageTS/setup.ts">');

  $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
  $iconRegistry->registerIcon(
      'mimetypes-x-content-personnel',
      \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
      ['source' => 'EXT:personnel/Resources/Public/Images/Icons/mimetypes-x-content-personnel.svg']
  );

  $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] =
      \Brightside\Personnel\Hooks\ContentPostProcessor::class . '->render';

  $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['personnel'] = \Brightside\Personnel\Hooks\PageLayoutView\PersonnelContentElementPreviewRenderer::class;
