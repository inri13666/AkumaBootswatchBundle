<?php
/**
 * This file is part of BraincraftedBootstrapBundle.
 * (c) 2012-2013 by Florian Eckerstorfer
 */

namespace Akuma\Bundle\BootswatchBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Akuma\Bundle\BootswatchBundle\Util\LegacyFormHelper;
/**
 * TypeSetterExtension
 *
 * @package    BraincraftedBootstrapBundle
 * @subpackage Form
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012-2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://bootstrap.braincrafted.com Bootstrap for Symfony2
 */
class TypeSetterExtension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['original_type'] = LegacyFormHelper::isLegacy() ? $form->getConfig()->getType()->getName() : $form->getConfig()->getType()->getBlockPrefix();
    }
    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        // map old class to new one using LegacyFormHelper
        return LegacyFormHelper::getType('form');
    }
}
