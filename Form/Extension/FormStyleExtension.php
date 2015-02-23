<?php
/**
 * User  : Nikita.Makarov
 * Date  : 2/23/15
 * Time  : 7:36 AM
 * E-Mail: nikita.makarov@effective-soft.com
 */

namespace Akuma\Bundle\BootswatchBundle\Form\Extension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('style' => null));
        $resolver->setOptional(array('style'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }
} 