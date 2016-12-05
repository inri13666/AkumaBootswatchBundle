<?php

namespace Akuma\Bundle\BootswatchBundle\DependencyInjection;

use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AkumaBootswatchExtension extends Extension implements PrependExtensionInterface
{
    protected function createConfigEntries(array $config, ContainerBuilder $container, $parent = null)
    {
        $isScalar = true;
        foreach ($config as $key => $value) {
            if (!is_numeric($key)) {
                $isScalar = false;
                break;
            }
        }
        if ($isScalar) {
            if (!is_null($parent)) {
                $container->setParameter($parent, $config);
            }
        } else {
            foreach ($config as $key => $value) {
                if (is_array($value)) {
                    //$keys = array_keys($value);
                    $this->createConfigEntries($value, $container, $parent ? $parent . '.' . $key : $key);
                } else {
                    $container->setParameter($parent ? $parent . '.' . $key : $key, $value);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->createConfigEntries($config, $container, $this->getAlias());

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('form.yml');
        $loader->load('twig.yml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        foreach ($bundles as $bundle => $class) {
            if (method_exists($this, 'prepend' . $bundle)) {
                call_user_func_array(array($this, 'prepend' . $bundle), array($container));
            }
        }
    }

    protected function getConfig(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        return $this->processConfiguration(new Configuration(), $configs);
    }

    protected function prependAsseticBundle(ContainerBuilder $container)
    {
        $config = $this->getConfig($container);
        if (isset($config['auto_configure']) && isset($config['auto_configure']['assetic']) && ($config['auto_configure']['assetic'])) {
            $asseticConfig = new Assetic\Configuration;
            $container->prependExtensionConfig(
                'assetic',
                array('assets' => $asseticConfig->build($config))
            );
            $container->prependExtensionConfig(
                'assetic',
                array('bundles' => array('AkumaBootswatchBundle'))
            );

            $container->prependExtensionConfig(
                'assetic',
                array('filters' =>
                    array('exposed' =>
                        array(
                            'resource' => dirname(__FILE__) . '/../Resources/config/assetic.xml',
                            //'apply_to' => array('\.less$', '\.scss$', '\.js$', '\.css$')
                        )
                    )
                )
            );
        }
    }

    /**
     * @param ContainerBuilder $container The service container
     *
     * @return void
     */
    protected function prependTwigBundle(ContainerBuilder $container)
    {
        $config = $this->getConfig($container);
        if (isset($config['auto_configure']) && isset($config['auto_configure']['twig']) && ($config['auto_configure']['twig'])) {
            foreach (array_keys($container->getExtensions()) as $name) {
                switch ($name) {
                    case 'twig':
                        $container->prependExtensionConfig(
                            $name,
                            array(
//                                'form' => array('resources' => array('AkumaBootswatchBundle:Form:fields.html.twig')),
                                'form_themes' => array('AkumaBootswatchBundle:Form:fields.html.twig')
                            )
                        );
                        break;
                }
            }
        }
    }

    /**
     * @param ContainerBuilder $container The service container
     *
     * @return void
     */
    protected function prependKnpMenuBundle(ContainerBuilder $container)
    {
        $config = $this->getConfig($container);
        if (isset($config['auto_configure']) && isset($config['auto_configure']['knp_menu']) && ($config['auto_configure']['knp_menu'])) {
            foreach (array_keys($container->getExtensions()) as $name) {
                switch ($name) {
                    case 'knp_menu':
                        $container->prependExtensionConfig(
                            $name,
                            array('twig' => array('template' => 'AkumaBootswatchBundle:Default:_menu.html.twig'))
                        );
                        break;
                }
            }
        }
    }

    /**
     * @param ContainerBuilder $container The service container
     *
     * @return void
     */
    protected function prependKnpPaginatorBundle(ContainerBuilder $container)
    {
        $config = $this->getConfig($container);
        if (isset($config['auto_configure']) && isset($config['auto_configure']['knp_paginator']) && ($config['auto_configure']['knp_paginator'])) {
            foreach (array_keys($container->getExtensions()) as $name) {
                switch ($name) {
                    case 'knp_paginator':
                        $container->prependExtensionConfig(
                            $name,
                            array('template' => array('pagination' => 'AkumaBootswatchBundle:Default:_pagination.html.twig'))
                        );
                        break;
                }
            }
        }
    }

    public function getXsdValidationBasePath()
    {
        return dirname(__FILE__) . '/../Resources/config/';
    }

    public function getNamespace()
    {
        return 'http://www.example.com/symfony/schema/';
    }
}
