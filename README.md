[AkumaBootswatchBundle](http://bootswatch.akuma.in)
=================

AkumaBootswatchBundle helps you integrate [Bootswatch Themes](http://bootswatch.com) in your [Symfony2](http://symfony.com) project.
AkumaBootswatchBundle also supports the official Sass port of Bootstrap and Font Awesome.

Developed by [Nikita Makarov](http://akuma.in).


Installation
------------

First you need to add `akuma/bootswatch-bundle` to `composer.json`:

```json
{
   "require": {
        "akuma/bootsswatch-bundle": "dev-master"
    }
}
```

Please note that `dev-master` points to the latest release. 

You also have to add `AkumaBootswatchBundle` to your `AppKernel.php`:

```php
// app/AppKernel.php
//...
class AppKernel extends Kernel
{
    //...
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Akuma\Bundle\BootswatchBundle\AkumaBootswatchBundle()
        );
        //...

        return $bundles;
    }
    //...
}
```
Configuration
-------------

You also may customize some config options:
```yml
akuma_bootswatch:
    less_filter: ~ # Default "none", and may be one of : "less", "lessphp", "sass", "scssphp" or "none"
    auto_configure:
        assetic: true # default true
        twig: true # coming soon
        knp_menu: true # coming soon
        knp_paginator: true # coming soon
    bootswatch:
        path: ~ # default %kernel.root_dir%/../vendor/thomaspark/bootswatch
        theme: ~ # default "cosmo"
    fonts_dir: ~ # default %kernel.root_dir%/../web/fonts
    font_awesome: ~ #default true
    output_dir: ~ # default empty to output all into "%kernel.root_dir%/../web"
```

To install fonts you need to execute the next command:
```cmd
php app/console akuma:bootswatch:install
```

Compatibility
-------------

This bundle has two main dependencies, [Symfony2](http://symfony.com) and [Bootswatch](https://github.com/thomaspark/bootswatch/).

Changelog
---------

### Version 1.0.0

- First release


License
-------

- The bundle is licensed under the [MIT License](http://opensource.org/licenses/MIT)
- The CSS and Javascript files from Twitter Bootstrap are licensed under the [Apache License 2.0](http://www.apache.org/licenses/LICENSE-2.0) for all versions before 3.1
- The CSS and Javascript files from Twitter Bootstrap are licensed under the [MIT License](http://opensource.org/licenses/MIT) for 3.1 and after
