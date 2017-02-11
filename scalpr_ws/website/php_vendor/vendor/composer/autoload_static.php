<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitca6b52916c84861f3db3d227cdd303ad
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/Twilio',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitca6b52916c84861f3db3d227cdd303ad::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitca6b52916c84861f3db3d227cdd303ad::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}