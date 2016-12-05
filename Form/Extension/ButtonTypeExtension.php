<?php

namespace Akuma\Bundle\BootswatchBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class ButtonTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['button_class'] = $form->getConfig()->getOption('button_class');
        $view->vars['as_link'] = $form->getConfig()->getOption('as_link');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('button_class', 'as_link'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ButtonType::class;
    }
}