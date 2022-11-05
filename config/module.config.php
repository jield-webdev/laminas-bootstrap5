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
            \LaminasBootstrap5\Event\InjectJavascriptAndCss::class => ConfigAbstractFactory::class,
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
        ],
        \LaminasBootstrap5\Event\InjectJavascriptAndCss::class => [
            \Laminas\View\Renderer\PhpRenderer::class
        ]
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'map' => [
                'external/js/filter-column.js' => __DIR__ . '/../public/filter-column/filter-column.js',
                'external/css/filter-column.css' => __DIR__ . '/../public/filter-column/filter-column.css',

                'external/js/simple-load-more.js' => __DIR__ . '/../public/simple-load-more/jquery.simpleLoadMore.min.js',

                'external/js/moment.js' => __DIR__ . '/../public/date-range-picker/moment.min.js',
                'external/js/date-range-picker.js' => __DIR__ . '/../public/date-range-picker/daterangepicker.js',
                'external/css/date-range-picker.css' => __DIR__ . '/../public/date-range-picker/daterangepicker.css',

                'external/js/codemirror.js' => __DIR__ . '/../public/codemirror/codemirror.js',
                'external/css/codemirror.css' => __DIR__ . '/../public/codemirror/codemirror.css',

                'external/js/bootstrap-select.min.js' => __DIR__ . '/../public/bootstrap-select-1.14-dev/dist/js/bootstrap-select.min.js',
                'external/css/bootstrap-select.min.css' => __DIR__ . '/../public/bootstrap-select-1.14-dev/css/bootstrap-select.min.css',
                'external/js/ajax-bootstrap-select.min.js' => __DIR__ . '/../public/ajax-bootstrap-select/dist/js/ajax-bootstrap-select.min.js',
                'external/css/ajax-bootstrap-select.min.css' => __DIR__ . '/../public/ajax-bootstrap-select/dist/css/ajax-bootstrap-select.min.css',

            ],
        ],
    ]
];
