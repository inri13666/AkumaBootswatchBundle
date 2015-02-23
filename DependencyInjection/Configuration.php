<?php

namespace Akuma\Bundle\BootswatchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('akuma_bootswatch')->addDefaultsIfNotSet();

        /**
         * TODO: Add LESS and SaaS capability
         */

        /** @var ArrayNodeDefinition $auto_configure */
        $auto_configure = $rootNode->children()->arrayNode('auto_configure')->addDefaultsIfNotSet();
        $auto_configure->children()->booleanNode('assetic')->defaultValue(true)->end();
        $auto_configure->children()->booleanNode('twig')->defaultValue(true)->end();
        $auto_configure->children()->booleanNode('knp_menu')->defaultValue(true)->end();
        $auto_configure->children()->booleanNode('knp_paginator')->defaultValue(true)->end();
        $auto_configure->end();

        /** @var ArrayNodeDefinition $bootswatch */
        $bootswatch = $rootNode->children()->arrayNode('bootswatch')->addDefaultsIfNotSet();

        $default = '%kernel.root_dir%/../vendor/thomaspark/bootswatch';
        $bootswatch->children()->scalarNode('path')->treatNullLike($default)->defaultValue($default)->end();

        $default = 3;
        $bootswatch->children()->integerNode('version')->treatNullLike($default)->defaultValue($default)->validate()->ifNotInArray(array(2, 3))->thenInvalid('Invalid version "%s"')->end();
        $default = 'cosmo';
        $bootswatch->children()->scalarNode('theme')
            ->treatNullLike($default)
            ->defaultValue($default)
            ->validate()
            ->ifNotInArray(array(
                "amelia",
                "cerulean",
                "cosmo",
                "cyborg",
                "darkly",
                "flatly",
                "journal",
                "lumen",
                "paper",
                "readable",
                "sandstone",
                "simplex",
                "slate",
                "spacelab",
                "superhero",
                "united",
                "yeti"
            ))
            ->thenInvalid('Invalid theme "%s"')
            ->end();
        $bootswatch->end();

        $default = '%kernel.root_dir%/../web/fonts';
        $rootNode->children()->scalarNode('fonts_dir')->treatNullLike($default)->defaultValue($default)->end();
        $default = '';
        $rootNode->children()->scalarNode('output_dir')->treatNullLike($default)->defaultValue($default)->end();
        $default = 'none';
        $rootNode->children()->scalarNode('less_filter')->treatNullLike($default)->defaultValue($default)
            ->validate()
            ->ifNotInArray(array('less', 'lessphp', 'sass', 'scssphp', 'none'))
            ->thenInvalid('Invalid less filter "%s"')
            ->end();
        $rootNode->children()->scalarNode('jquery_path')->defaultNull()->end();
        $rootNode->children()->booleanNode('font_awesome')->treatNullLike(true)->defaultTrue()->end();

        $rootNode->children()->scalarNode('icon_prefix')
            ->defaultValue('glyphicon')
            ->end()
            ->scalarNode('icon_tag')
            ->defaultValue('span')
            ->end();
        return $treeBuilder;
    }
}
