<?php

declare(strict_types=1);

use NITSAN\NsBlog\Controller\PostController;
use NITSAN\NsBlog\Controller\WidgetController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

ExtensionUtility::configurePlugin(
    'NsBlog',
    'Posts',
    [
        PostController::class => 'listRecentPosts',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'DemandedPosts',
    [
        PostController::class => 'listByDemand',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'LatestPosts',
    [
        PostController::class => 'listLatestPosts',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'Category',
    [
        PostController::class => 'listPostsByCategory',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'AuthorPosts',
    [
        PostController::class => 'listPostsByAuthor',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'Sidebar',
    [
        PostController::class => 'sidebar',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'RelatedPosts',
    [
        PostController::class => 'relatedPosts',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'RecentPostsWidget',
    [
        WidgetController::class => 'recentPosts',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'CategoryWidget',
    [
        WidgetController::class => 'categories',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid'] ??= [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces'] ??= [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['nsblogvh'] ??= [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['nsblogvh'][] = 'NITSAN\\NsBlog\\ViewHelpers';
