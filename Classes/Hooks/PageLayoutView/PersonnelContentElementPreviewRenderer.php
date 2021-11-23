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
				$itemContent = $parentObject->linkEditContent('<span style="display: block; margin-top: 0.3em;">Persons from page: '. $row['pages'] .'</span>', $row);
			}
			if ($row['CType'] === 'personnel_selected') {
        $itemContent = $parentObject->linkEditContent('<span style="display: block; margin-top: 0.3em;">Selected persons: '. $row['tx_personnel'] .'</span>', $row);
			}
			$itemContent .= '<ul style="margin: 0; padding: 0.2em 1.4em;">';
			if ($row['tx_personnel_orderby']) {
        $itemContent .= '<li>Order by: ' . $parentObject->linkEditContent($parentObject->renderText($row['tx_personnel_orderby']), $row) . '</li>';
			}
			if ($row['tx_personnel_images'] == 1) {
				$itemContent .= '<li>' . $parentObject->linkEditContent('Images: disabled', $row) . '</li>';
			}
			if ($row['tx_personnel_vcard'] == 1) {
				$itemContent .= '<li>' . $parentObject->linkEditContent('vCard: disabled', $row) . '</li>';
			}
			if ($row['tx_personnel_information'] == 1) {
				$itemContent .= '<li>' . $parentObject->linkEditContent('Information: disabled', $row) . '</li>';
			}
            if ($row['tx_pagelist_authors'] > 0) {
				$itemContent .= '<li>' . $parentObject->linkEditContent('Author filter: active', $row) . '</li>';
			}
			if ($row['tx_paginatedprocessors_paginationenabled'] == 1) {
        		$itemContent .= '<li>' . $parentObject->linkEditContent('Pagination: enabled', $row) . '</li>';
			}
			if ($row['tx_paginatedprocessors_itemsperpage'] && $row['tx_paginatedprocessors_paginationenabled'] == 1) {
				$itemContent .= '<li>' . $parentObject->linkEditContent($parentObject->renderText('Items per page: ' . $row['tx_paginatedprocessors_itemsperpage']), $row) . '</li>';
			}
            if ($row['tx_paginatedprocessors_urlsegment'] && $row['tx_paginatedprocessors_paginationenabled'] == 1) {
				$itemContent .= '<li>' . $parentObject->linkEditContent($parentObject->renderText('URL segment: ' . $row['tx_paginatedprocessors_urlsegment']), $row) . '</li>';
			}
			$itemContent .= '</ul>';
			$drawItem = FALSE;
		}
	}
}
