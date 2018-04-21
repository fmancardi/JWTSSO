<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb9c8f5f21c50774d8e481b3c18bfcd19
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb9c8f5f21c50774d8e481b3c18bfcd19::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb9c8f5f21c50774d8e481b3c18bfcd19::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
