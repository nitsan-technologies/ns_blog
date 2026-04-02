<?php
declare(strict_types=1);

return [
    \NITSAN\NsBlog\Domain\Model\Post::class => [
        'tableName' => 'pages',
    ],
    \NITSAN\NsBlog\Domain\Model\Category::class => [
        'tableName' => 'sys_category',
    ],
    \NITSAN\NsBlog\Domain\Model\Author::class => [
        'tableName' => 'tx_blog_domain_model_author',
    ],
];
