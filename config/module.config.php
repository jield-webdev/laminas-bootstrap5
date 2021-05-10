<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use LaminasBootstrap5\Form\View;
use LaminasBootstrap5\Navigation;
use LaminasBootstrap5\View\Helper;

return [
    'view_helpers'               => [
        'aliases'    => [

            'lbs5navigation'       => Helper\Navigation::class,
            'lbs5filterbarelement' => View\Helper\FilterBarElement::class,
            'lb5sformelement'      => View\Helper\FormElement::class,

        ],
        'factories'  => [
            Helper\Navigation::class            => Navigation\View\NavigationHelperFactory::class,
            View\Helper\FormElement::class      => ConfigAbstractFactory::class,
            View\Helper\FilterBarElement::class => ConfigAbstractFactory::class,
        ],
        'invokables' => [
            'lbs5formdescription'   => View\Helper\FormDescription::class,
            'lbs5forminput'         => View\Helper\FormInput::class,
            'lbs5formfile'          => View\Helper\FormFile::class,
            'lbs5formradio'         => View\Helper\FormRadio::class,
            'lbs5formcheckbox'      => View\Helper\FormCheckbox::class,
            'lbs5formtextarea'      => View\Helper\FormTextarea::class,
            'lbs5formselect'        => View\Helper\FormSelect::class,
            'lbs5formmulticheckbox' => View\Helper\FormMultiCheckbox::class,
            'lbs5alert'             => Helper\Alert::class,
        ],
    ],
    ConfigAbstractFactory::class => [
        View\Helper\FormElement::class      => [
            'ViewHelperManager',
            \Laminas\I18n\Translator\TranslatorInterface::class
        ],
        View\Helper\FilterBarElement::class => [
            'ViewHelperManager',
            \Laminas\I18n\Translator\TranslatorInterface::class
        ]
    ]
];
