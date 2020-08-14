<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit67aed4b349aee8fa6007f26422c10dbd
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'VK\\' => 3,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'VK\\' => 
        array (
            0 => __DIR__ . '/..' . '/vkcom/vk-php-sdk/src/VK',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit67aed4b349aee8fa6007f26422c10dbd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit67aed4b349aee8fa6007f26422c10dbd::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}