<?php
/**
 * User  : Nikita.Makarov
 * Date  : 2/20/15
 * Time  : 3:43 AM
 * E-Mail: nikita.makarov@effective-soft.com
 */

namespace Akuma\Bundle\BootswatchBundle\Assetic\Filter;


use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;

class ExposedFilter implements FilterInterface, ContainerAwareInterface
{

    /**
     * @var Container
     */
    protected $container;

    public function filterLoad(AssetInterface $asset)
    {
        $content = $asset->getContent();
        /**
         * Find All Params In %%
         */
        $regex_pattern = '/\%[a-z0-9A-Z\._]+\%/';
        $boolean = preg_match_all($regex_pattern, $content, $matches_out);
        $replaces = array();
        if ($boolean) {
            $matches = array_unique($matches_out[0]);
            foreach ($matches as $match) {
                if ($value = $this->container->getParameterBag()->resolveValue($match)) {
                    $replaces[$match] = $value;
                };
            }
            $content = str_replace(array_keys($replaces), array_values($replaces), $content);
        }
        $asset->setContent($content);
    }


    public function filterDump(AssetInterface $asset)
    {
    }


    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}