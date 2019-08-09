<?php
namespace Brightside\Personnel\Hooks;
use TYPO3\CMS\Core\Database\ConnectionPool;
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
      $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_personnel_domain_model_person');
      $statement = $queryBuilder
        ->select('*')
        ->from('tx_personnel_domain_model_person')
        ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($personId, \PDO::PARAM_INT)))
        ->execute();
        $vcfFilename = 'person.vcf';
        while ($row = $statement->fetch()) {
          if ($row['firstname'] || $row['lastname']) {
            $vcfFilename = $row['firstname'] . '_' . $row['lastname'] . '.vcf';
          }
        }
      header('Content-type: text/x-vCard; charset=utf-8');
      header('Content-Disposition: attachment; ; filename="'.$vcfFilename.'"');
    }
  }
}
