<?php
    defined('TYPO3_MODE') || die('Access denied.');
    call_user_func(
        function () {
            $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
              \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
            );
            $personnelConiguration = $extensionConfiguration->get('personnel');

            $tempColumns = array(
              'tx_personnel_authors' => [
                'exclude' => 1,
                'label' => 'Author / Contact person',
                'config' => [
                  'type' => 'select',
                  'renderType' => 'selectMultipleSideBySide',
                  'enableMultiSelectFilterTextfield' => true,
                  'foreign_table' => 'tx_personnel_domain_model_person',
                  'foreign_table_where' => 'AND tx_personnel_domain_model_person.sys_language_uid IN (-1,0)',
                  'size' => '3',
                  'behaviour' => [
                    'allowLanguageSynchronization' => true,
                  ],
                ]
              ],
            );
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns, 1);

            if ($personnelConiguration['personnelEnableAuthors']) {
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
                    'pages',
                    '--palette--;Personnel;personnelcontact',
                    '1',
                    'after:description'
                );
                $GLOBALS['TCA']['pages']['palettes']['personnelcontact']['showitem'] = '
                    tx_personnel_authors,
                ';
            }
        }
    );
