<?php

declare(strict_types=1);

if (!defined('TYPO3')) {
    die('Access denied.');
}

$ll = 'LLL:EXT:ns_blog/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_blog_domain_model_author',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'default_sortby' => 'ORDER BY name',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'typeicon_classes' => [
            'default' => 'ns-blog-record-author',
        ],
        'searchFields' => 'name,title,email,slug',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;' . $ll . 'tx_blog_domain_model_author.tabs.profile,
                    name, title, slug, location, image, bio,
                --div--;' . $ll . 'tx_blog_domain_model_author.tabs.contact,
                    website, email,
                --div--;' . $ll . 'tx_blog_domain_model_author.tabs.social,
                    twitter, linkedin, xing, instagram, profile,
                --div--;' . $ll . 'tx_blog_domain_model_author.tabs.details,
                    details_page,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden, sys_language_uid, l18n_parent, l18n_diffsource
            ',
        ],
    ],
    'columns' => [
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'name' => [
            'label' => $ll . 'tx_blog_domain_model_author.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 100,
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'slug' => [
            'label' => $ll . 'tx_blog_domain_model_author.slug',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => ['name'],
                    'replacements' => [
                        '/' => '',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'default' => '',
            ],
        ],
        'title' => [
            'label' => $ll . 'tx_blog_domain_model_author.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 100,
                'eval' => 'trim',
            ],
        ],
        'email' => [
            'label' => $ll . 'tx_blog_domain_model_author.email',
            'config' => [
                'type' => 'email',
                'size' => 30,
            ],
        ],
        'website' => [
            'label' => $ll . 'tx_blog_domain_model_author.website',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
                'placeholder' => 'https://',
            ],
        ],
        'location' => [
            'label' => $ll . 'tx_blog_domain_model_author.location',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'bio' => [
            'label' => $ll . 'tx_blog_domain_model_author.bio',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 6,
                'eval' => 'trim',
            ],
        ],
        'twitter' => [
            'label' => $ll . 'tx_blog_domain_model_author.twitter',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'linkedin' => [
            'label' => $ll . 'tx_blog_domain_model_author.linkedin',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'instagram' => [
            'label' => $ll . 'tx_blog_domain_model_author.instagram',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'xing' => [
            'label' => $ll . 'tx_blog_domain_model_author.xing',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'profile' => [
            'label' => $ll . 'tx_blog_domain_model_author.profile',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'image' => [
            'label' => $ll . 'tx_blog_domain_model_author.image',
            'config' => [
                'type' => 'file',
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                ],
                'minitems' => 0,
                'maxitems' => 1,
                'allowed' => 'common-image-types',
            ],
        ],
        'details_page' => [
            'label' => $ll . 'tx_blog_domain_model_author.details_page',
            'config' => [
                'type' => 'group',
                'allowed' => 'pages',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
                'default' => 0,
            ],
        ],
        'sys_language_uid' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l18n_parent' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => '',
                        'value' => 0,
                    ],
                ],
                'foreign_table' => 'tx_blog_domain_model_author',
                'foreign_table_where' => 'AND {#tx_blog_domain_model_author}.{#pid}=###CURRENT_PID### AND {#tx_blog_domain_model_author}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l18n_diffsource' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_diffsource',
            'config' => [
                'type' => 'passthrough',
                'default' => '',
            ],
        ],
    ],
];
