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
    []
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'LatestPosts',
    [
        PostController::class => 'listLatestPosts',
    ],
    []
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'Category',
    [
        PostController::class => 'listPostsByCategory',
    ],
    []
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'AuthorPosts',
    [
        PostController::class => 'listPostsByAuthor',
    ],
    []
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'Sidebar',
    [
        PostController::class => 'sidebar',
    ],
    []
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'RelatedPosts',
    [
        PostController::class => 'relatedPosts',
    ],
    []
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'RecentPostsWidget',
    [
        WidgetController::class => 'recentPosts',
    ],
    []
);

ExtensionUtility::configurePlugin(
    'NsBlog',
    'CategoryWidget',
    [
        WidgetController::class => 'categories',
    ],
    []
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['nsblogvh'][] = 'NITSAN\\NsBlog\\ViewHelpers';
