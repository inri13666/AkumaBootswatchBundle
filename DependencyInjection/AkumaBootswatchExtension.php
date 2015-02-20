<?php

namespace Akuma\Bundle\BootswatchBundle\DependencyInjection;

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
        $loader->load('services.yml');
        //$loader->load('assetic.yml');
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

    protected function prependAsseticBundle(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('akuma_bootswatch');
        $config = $this->processConfiguration(new Configuration(), $configs);
        $config['cache_dir'] = $container->getParameter('kernel.cache_dir');

        if (isset($config['auto_configure']) && isset($config['auto_configure']['assetic']) && ($config['auto_configure']['assetic'])) {
            $asseticConfig = new Assetic\Configuration;
            $container->prependExtensionConfig(
                'assetic',
                array('assets' => $asseticConfig->build($config))
            );
        }
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

    public function getXsdValidationBasePath()
    {
        return dirname(__FILE__) . '/../Resources/config/';
    }

    public function getNamespace()
    {
        return 'http://www.example.com/symfony/schema/';
    }
}
