<?php

namespace Akuma\Bundle\BootswatchBundle\Controller;

use Akuma\Bundle\BootswatchBundle\Form\Demo\ControlStatesFormType;
use Akuma\Bundle\BootswatchBundle\Form\Demo\DefaultStylesFormType;
use Akuma\Bundle\BootswatchBundle\Form\Demo\ExtendingControlsFormType;
use Akuma\Bundle\BootswatchBundle\Form\Demo\HorizontalFormType;
use Akuma\Bundle\BootswatchBundle\Form\Demo\InlineFormType;
use Akuma\Bundle\BootswatchBundle\Form\Demo\SearchFormType;
use Akuma\Bundle\BootswatchBundle\Form\Demo\SupportedFormControlsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 *
 * @package Akuma\Bundle\BootswatchBundle\Controller
 *
 * @Route()
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     *
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render('AkumaBootswatchBundle:Demo:_index.html.twig', array(
            'fa' => $this->container->getParameter('akuma_bootswatch.font_awesome')
        ));
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/forms")
     */
    public function formsActions(Request $request)
    {
        /**
         * http://getbootstrap.com/css/#forms-example
         */
        $basicExample = $this->createFormBuilder();
        $basicExample->add('EmailAddress', 'email');
        $basicExample->add('Password', 'password');
        $basicExample->add('FileInput', 'file');
        $basicExample->add('Submit', 'submit');

        $inlineForm = $this->createFormBuilder(null, array('style' => 'inline'));
        $inlineForm->add('EmailAddress', 'email');
        $inlineForm->add('Password', 'password');
        $inlineForm->add('Submit', 'submit');

        $horizontalForm = $this->createFormBuilder(null, array('style' => 'horizontal'));
        $horizontalForm->add('EmailAddress', 'email');
        $horizontalForm->add('Password', 'password');
        $horizontalForm->add('Submit', 'submit');


//        $supportedControls = $this->createFormBuilder(null, array('style' => 'horizontal'));
//        $types = array(
//            'text',
//            'password',
//            'datetime',
//            //    'datetime-local',
//            'date',
//            //    'month',
//            'time',
//            //    'week',
//            'number',
//            'email',
//            'url',
//            'search',
//            //    'tel',
//            //    'color',
//            'textarea',
//        );
//        foreach ($types as $type) {
//            $supportedControls->add($type, $type);
//        }
        //$supportedControls->add('Submit', 'submit');


        $builder = $this->createFormBuilder(null, array('style' => 'horizontal'));
        $builder->add('prependedInput', 'text', array(
            'attr' => array(
                'placeholder' => 'Username',
                'input_group' => array(
                    'prepend' => '@'
                ),
                'help_text' => 'Letters, numbers and underscores are allowed.'
            )
        ));
        $builder->add('appendedInput', 'text', array(
            'attr' => array(
                'input_group' => array(
                    'append' => '.00'
                ),
            )
        ));
        $builder->add('appendedPrependedInput', 'text', array(
            'attr' => array(
                'input_group' => array(
                    'prepend' => '$',
                    'append' => '.00',
                ),
            )
        ));

        $builder->add('iconInput', 'text', array(
            'attr' => array(
                'input_group' => array(
                    'prepend' => '.icon-cloud',
                    'append' => '.icon-off',
                ),
            )
        ));
        $builder->add('keywords', 'text', array(
            'attr' => array(
                'input_group' => array(
                    'button_append' => array('name' => 'search', 'type' => 'submit')
                )
            )
        ));
        $builder->add(
            'hobbits',
            'bootstrap_collection',
            array(
                'allow_add' => true,
                'allow_delete' => true,
                'add_button_text' => 'Add Hobbit',
                'delete_button_text' => 'Delete Hobbit',
                'sub_widget_col' => 9,
                'button_col' => 3,
            )
        );
        return $this->render(
            'AkumaBootswatchBundle:Demo:baseCss.html.twig',
            array(
                'basicExample' => $basicExample->getForm()->createView(),
                'inlineForm' => $inlineForm->getForm()->createView(),
                'horizontalForm' => $horizontalForm->getForm()->createView(),
//                'supportedControls' => $supportedControls->getForm()->createView(),
                'builderForm' => $builder->getForm()->createView(),
            )
        );
    }
}
