<?php
namespace GeorgRinger\Personnel\Domain\Model;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class News extends \GeorgRinger\News\Domain\Model\News {
    /**
     * @var string
     */
    protected $txPersonnelAuthors;

    /**
     * Get all personnel authors associated with this news record
     *
     * @return array
     */

    public function getTxPersonnelAuthors()
    {
        return $this->txPersonnelAuthors;
    }
}
