<?php

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use LaminasBootstrap5\Form\View;
use LaminasBootstrap5\Navigation;
use LaminasBootstrap5\View\Helper;

return [
    'view_helpers' => [
        'aliases' => [

            //Add some BC changes with previous module
            'ztbalert' => 'lbs5alert',
            'ztbformelement' => 'lbs5formelement',
            'filterbarelement' => 'lbs5filterbarelement',

            'lbs5navigation' => Helper\Navigation::class,
            'lbs5filterbarelement' => View\Helper\FilterBarElement::class,
            'lbs5filtercolumnelement' => View\Helper\FilterColumnElement::class,
            'lbs5formelement' => View\Helper\FormElement::class,
            'lbs5formcheckbox' => View\Helper\FormCheckbox::class,

        ],
        'factories' => [
            Helper\Navigation::class => Navigation\View\NavigationHelperFactory::class,
            View\Helper\FormElement::class => ConfigAbstractFactory::class,
            View\Helper\FormCheckbox::class => ConfigAbstractFactory::class,
            View\Helper\FilterBarElement::class => ConfigAbstractFactory::class,
            View\Helper\FilterColumnElement::class => ConfigAbstractFactory::class,
        ],
        'invokables' => [
            'lbs5formdescription' => View\Helper\FormDescription::class,
            'lbs5formdatetimelocal' => View\Helper\FormDateTimeLocal::class,
            'lbs5forminput' => View\Helper\FormInput::class,
            'lbs5formmultislider' => View\Helper\FormMultiSlider::class,
            'lbs5formfile' => View\Helper\FormFile::class,
            'lbs5formradio' => View\Helper\FormRadio::class,
            'lbs5formtextarea' => View\Helper\FormTextarea::class,
            'lbs5formselect' => View\Helper\FormSelect::class,
            'lbs5formmulticheckbox' => View\Helper\FormMultiCheckbox::class,
            'lbs5alert' => Helper\Alert::class,
        ],
    ],
    ConfigAbstractFactory::class => [
        View\Helper\FormElement::class => [
            'ViewHelperManager',
            \Laminas\I18n\Translator\TranslatorInterface::class
        ],
        View\Helper\FormCheckbox::class => [
            'ViewHelperManager',
            \Laminas\I18n\Translator\TranslatorInterface::class
        ],
        View\Helper\FilterBarElement::class => [
            'ViewHelperManager',
            \Laminas\I18n\Translator\TranslatorInterface::class
        ],
        View\Helper\FilterColumnElement::class => [
            'ViewHelperManager',
            \Laminas\I18n\Translator\TranslatorInterface::class
        ]
    ]
];
