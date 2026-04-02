<?php

declare(strict_types=1);

use NITSAN\NsBlog\Constants;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

ExtensionManagementUtility::addTcaSelectItem(
    'pages',
    'doktype',
    [
        'label' => 'Blog Post',
        'value' => (string)Constants::DOKTYPE_BLOG_POST,
        'icon' => 'ns-blog-page-post',
        'group' => 'default',
    ]
);

$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][(string)Constants::DOKTYPE_BLOG_POST] = 'ns-blog-page-post';

$GLOBALS['TCA']['pages']['columns'] = array_replace_recursive(
    $GLOBALS['TCA']['pages']['columns'],
    [
        'publish_date' => [
            'label' => 'Publish date',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'authors' => [
            'label' => 'Authors',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_blog_domain_model_author',
                'MM' => 'tx_blog_post_author_mm',
                'minitems' => 0,
                'maxitems' => 9999,
            ],
        ],
        'featured_image' => [
            'label' => 'Featured image',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
            ],
        ],
    ]
);

ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--div--;Blog,publish_date,featured_image,authors',
    (string)Constants::DOKTYPE_BLOG_POST
);
