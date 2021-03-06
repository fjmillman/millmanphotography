<?php declare(strict_types = 1);

return [
    'settings' => [
        'displayErrorDetails' => getenv('ENVIRONMENT') == 'development' ? true : false,
        'plates' => [
            'directory' =>  __DIR__ . '/../templates/',
            'assetPath' => getenv('ENVIRONMENT') == 'development' ? __DIR__ . '/../public/' : __DIR__ . '/../public_html/',
        ],
        'markdown' => [
            'renderer' => [
                'block_separator' => "\n",
                'inner_separator' => "\n",
                'soft_break'      => "\n",
            ],
            'enable_em' => true,
            'enable_strong' => true,
            'use_asterisk' => true,
            'use_underscore' => true,
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ],
        'glide' => [
            'source' => 'img',
            'source_path_prefix' => 'source',
            'cache' => 'img',
            'cache_path_prefix' => 'cache',
            'driver' => 'imagick',
            'max_image_size' => 2000*2000,
            'sign_key' => getenv('SIGN_KEY')
        ],
        'doctrine' => [
            'meta' => [
                'entity_path' => [
                    __DIR__ . '/../src/Entity'
                ],
                'auto_generate_proxies' => true,
                'proxy_dir' =>  __DIR__.'/../cache/proxies',
                'cache' => null,
            ],
            'connection' => [
                'driver' => getenv('DB_DRIVER'),
                'host' => getenv('DB_HOST'),
                'dbname' => getenv('DB_NAME'),
                'user' => getenv('DB_USERNAME'),
                'password' => getenv('DB_PASSWORD'),
            ]
        ],
        'mailer' => [
            'host' => getenv('SMTP_HOST'),
            'authentication' => getenv('SMTP_AUTHENTICATION'),
            'security' => getenv('SMTP_SECURITY'),
            'port' => getenv('SMTP_PORT'),
            'username' => getenv('SMTP_USERNAME'),
            'password' => getenv('SMTP_PASSWORD'),
        ],
        'logger' => [
            'name' => 'millman-photography',
            'settings' => [
                'path' => 'millman-photography.log',
                'directory' => __DIR__ . '/../logs/',
                'filename' => 'millman-photography.log',
                'timezone' => 'Europe/London',
            ],
        ],
    ]
];
