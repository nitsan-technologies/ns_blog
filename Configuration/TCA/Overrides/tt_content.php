<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['nsblog_posts'] = 'content-textmedia';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['nsblog_latestposts'] = 'content-elements-text';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['nsblog_category'] = 'content-menu-categorized';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['nsblog_authorposts'] = 'content-menu-sitemap';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['nsblog_relatedposts'] = 'content-menu-related';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['nsblog_sidebar'] = 'content-menu-pages';

ExtensionUtility::registerPlugin(
    extensionName: 'NsBlog',
    pluginName: 'Posts',
    pluginTitle: 'NS Blog: Posts',
    pluginIcon: 'ns-blog-plugin-posts',
    group: 'nsblog'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:plugin,pages,recursive',
    'nsblog_posts',
    'after:palette:headers'
);

ExtensionUtility::registerPlugin(
    extensionName: 'NsBlog',
    pluginName: 'LatestPosts',
    pluginTitle: 'NS Blog: Latest Posts',
    pluginIcon: 'ns-blog-plugin-latestposts',
    group: 'nsblog'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:plugin,pages,recursive',
    'nsblog_latestposts',
    'after:palette:headers'
);

ExtensionUtility::registerPlugin(
    extensionName: 'NsBlog',
    pluginName: 'Category',
    pluginTitle: 'NS Blog: Category',
    pluginIcon: 'ns-blog-plugin-category',
    group: 'nsblog'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:plugin,pages,recursive',
    'nsblog_category',
    'after:palette:headers'
);

ExtensionUtility::registerPlugin(
    extensionName: 'NsBlog',
    pluginName: 'AuthorPosts',
    pluginTitle: 'NS Blog: Author Posts',
    pluginIcon: 'ns-blog-plugin-authorposts',
    group: 'nsblog'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:plugin,pages,recursive',
    'nsblog_authorposts',
    'after:palette:headers'
);

ExtensionUtility::registerPlugin(
    extensionName: 'NsBlog',
    pluginName: 'RelatedPosts',
    pluginTitle: 'NS Blog: Related Posts',
    pluginIcon: 'ns-blog-plugin-authorposts',
    group: 'nsblog'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:plugin,pages,recursive',
    'nsblog_relatedposts',
    'after:palette:headers'
);

ExtensionUtility::registerPlugin(
    extensionName: 'NsBlog',
    pluginName: 'Sidebar',
    pluginTitle: 'NS Blog: Sidebar',
    pluginIcon: 'ns-blog-plugin-category',
    group: 'nsblog'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:plugin,pages,recursive',
    'nsblog_sidebar',
    'after:palette:headers'
);

