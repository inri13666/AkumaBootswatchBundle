<?php
/**
 * This file is part of BraincraftedBootstrapBundle.
 * (c) 2012-2013 by Florian Eckerstorfer
 */

namespace Akuma\Bundle\BootswatchBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class TypeSetterExtension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['original_type'] = $form->getConfig()->getType()->getBlockPrefix();
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}
