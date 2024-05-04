<?php return array(
    'root' => array(
        'name' => '__root__',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => null,
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        '__root__' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => null,
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'php-amqplib/php-amqplib' => array(
            'pretty_version' => 'v2.12.1',
            'version' => '2.12.1.0',
            'reference' => '0eaaa9d5d45335f4342f69603288883388c2fe21',
            'type' => 'library',
            'install_path' => __DIR__ . '/../php-amqplib/php-amqplib',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'phpseclib/phpseclib' => array(
            'pretty_version' => '2.0.47',
            'version' => '2.0.47.0',
            'reference' => 'b7d7d90ee7df7f33a664b4aea32d50a305d35adb',
            'type' => 'library',
            'install_path' => __DIR__ . '/../phpseclib/phpseclib',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'videlalvaro/php-amqplib' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => 'v2.12.1',
            ),
        ),
    ),
);
