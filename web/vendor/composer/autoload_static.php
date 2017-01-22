<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdef781e388e6276243d7a68b1d50f7a0
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Phroute\\Phroute\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Phroute\\Phroute\\' => 
        array (
            0 => __DIR__ . '/..' . '/phroute/phroute/src/Phroute',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdef781e388e6276243d7a68b1d50f7a0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdef781e388e6276243d7a68b1d50f7a0::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
