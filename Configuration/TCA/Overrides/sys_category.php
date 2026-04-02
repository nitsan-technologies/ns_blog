<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

$GLOBALS['TCA']['sys_category']['columns'] = array_replace_recursive(
    $GLOBALS['TCA']['sys_category']['columns'],
    [
        'slug' => [
            'label' => 'Slug',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => ['title'],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'default' => '',
            ],
        ],
    ]
);

ExtensionManagementUtility::addToAllTCAtypes('sys_category', 'slug', '', 'after:title');
