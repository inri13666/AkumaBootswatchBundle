<?php

namespace Akuma\Bundle\BootswatchBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Akuma\Bundle\BootswatchBundle\Util\LegacyFormHelper;

class FormStyleExtension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['style'] = $form->getConfig()->getOption('style');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('style' => null));
        $resolver->setDefined(array('style'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        // map old class to new one using LegacyFormHelper
        return LegacyFormHelper::getType('form');
    }
} 