<?php
namespace Brightside\Personnel\Hooks;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContentPostProcessor
{
    /**
     * @param $funcRef
     */
    public function render($params) {

        /** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $feobj */
        $feobj = &$params['pObj'];

        if($GLOBALS['TSFE']->type == 888){
            $personId = (int) GeneralUtility::_GET('person');

            $person = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
                'lastname,firstname',
                'tx_personnel_domain_model_person',
                'uid='. $personId
            );

            $vcfFilename = 'person.vcf';
            if(is_array($person)){
                $vcfFilename = $person['firstname'] . '_' . $person['lastname'] . '.vcf';
            }

            header('Content-type:text/x-vCard');
            header('Content-Disposition: attachment; ; filename="'.$vcfFilename.'"');
        }
    }
}
