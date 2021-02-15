<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbc725b6579403ee0f864cfe92cce460b
{
    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phptojs\\' => 8,
        ),
        'P' => 
        array (
            'PhpParser\\' => 10,
        ),
        'B' => 
        array (
            'BagusIndrayana\\LaravelMap\\' => 26,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phptojs\\' => 
        array (
            0 => __DIR__ . '/..' . '/mostka/phptojs/lib/phptojs',
        ),
        'PhpParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/php-parser/lib/PhpParser',
        ),
        'BagusIndrayana\\LaravelMap\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbc725b6579403ee0f864cfe92cce460b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbc725b6579403ee0f864cfe92cce460b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbc725b6579403ee0f864cfe92cce460b::$classMap;

        }, null, ClassLoader::class);
    }
}
