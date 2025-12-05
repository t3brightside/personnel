<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;

return [
    'mimetypes-x-content-personnel' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:personnel/Resources/Public/Icons/mimetypes-x-content-personnel.svg',
    ],
];