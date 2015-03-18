<?php
/**
 * User  : Nikita.Makarov
 * Date  : 3/17/15
 * Time  : 11:14 AM
 * E-Mail: nikita.makarov@effective-soft.com
 */

namespace Akuma\Bundle\BootswatchBundle\Twig;


class BootstrapMenuExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'attr' => new \Twig_SimpleFilter('attr', array($this, 'attrFilter'), array('is_safe' => array('html'))),
        );
    }

    public function attrFilter($array)
    {
        $str = "";
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $v = implode(" ", $v);
            } else {

            }
            $str .= " $k=\"$v\"" . PHP_EOL;
        }
        return " " . trim(str_replace('""', '"', $str));
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'akuma_bootswatch_menu';
    }
}