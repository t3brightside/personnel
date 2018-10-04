<?php
namespace Brightside\Personnel\Hooks\PageLayoutView;

use \TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use \TYPO3\CMS\Backend\View\PageLayoutView;

class PersonnelContentElementPreviewRenderer implements PageLayoutViewDrawItemHookInterface {
	 /**
     * Preprocesses the preview rendering of a content element of type "textmedia"
     *
     * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
     * @param bool $drawItem Whether to draw the item using the default functionality
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     *
     * @return void
     */

	 public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row) {
		if ($row['CType'] === 'personnel_frompages' || $row['CType'] === 'personnel_selected') {
			if ($row['CType'] === 'personnel_frompages') {
        $itemContent .= '<span>Persons from pages</span>';
			}
			if ($row['CType'] === 'personnel_selected') {
        $itemContent .= '<span>Selected persons</span>';
			}
			$itemContent .= '<ul style="margin: 0; padding: 0.2em 1.4em;">';
			if ($row['tx_personnel_orderby']) {
        $itemContent .= '<li>Order by: ' . $parentObject->linkEditContent($parentObject->renderText($row['tx_personnel_orderby']), $row) . '</li>';
			}
			if ($row['tx_personnel_images'] == 1) {
        $itemContent .= '<li>Images: disabled</li>';
			}
			if ($row['tx_personnel_vcard'] == 1) {
        $itemContent .= '<li>vCard: disabled</li>';
			}
			if ($row['tx_personnel_information'] == 1) {
        $itemContent .= '<li>Information: disabled</li>';
			}
			if ($row['tx_personnel_paginate'] == 1) {
        $itemContent .= '<li>Pagination: enabled</li>';
			}
			if ($row['tx_personnel_paginateitems'] > 1 && $row['tx_personnel_paginate'] == 1) {
				$itemContent .= '<li>Items per page: ' . $parentObject->linkEditContent($parentObject->renderText($row['tx_personnel_paginateitems']), $row) . '</li>';
			}
			if ($row['tx_personnel_paginateitems'] == 0 && $row['tx_personnel_paginate'] == 1) {
				$itemContent .= '<li>Items per page: TypoScript';
			}
			$itemContent .= '</ul>';
			$drawItem = FALSE;
		}
	}
}
