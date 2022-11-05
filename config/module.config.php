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
        ],
        \LaminasBootstrap5\Event\InjectJavascriptAndCss::class => [
            \Laminas\View\Renderer\PhpRenderer::class
        ]
    ],
    'service_manager' => [
        'factories' => [
            \LaminasBootstrap5\Event\InjectJavascriptAndCss::class => ConfigAbstractFactory::class,
        ]
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'map' => [
                'laminas-bootstrap5/js/filter-column.js' => __DIR__ . '/../public/filter-column/filter-column.js',
                'laminas-bootstrap5/css/filter-column.css' => __DIR__ . '/../public/filter-column/filter-column.css',

                'laminas-bootstrap5/js/form-submit.js' => __DIR__ . '/../public/main/form-submit.js',
                'laminas-bootstrap5/js/form-datepicker.js' => __DIR__ . '/../public/main/form-datepicker.js',
                'laminas-bootstrap5/css/style.css' => __DIR__ . '/../public/main/style.css',

                'laminas-bootstrap5/js/simple-load-more.js' => __DIR__ . '/../public/simple-load-more/jquery.simpleLoadMore.min.js',

                'laminas-bootstrap5/js/moment.js' => __DIR__ . '/../public/date-range-picker/moment.min.js',
                'laminas-bootstrap5/js/date-range-picker.js' => __DIR__ . '/../public/date-range-picker/daterangepicker.js',
                'laminas-bootstrap5/js/date-range-picker-main.js' => __DIR__ . '/../public/date-range-picker/main.js',
                'laminas-bootstrap5/css/date-range-picker.css' => __DIR__ . '/../public/date-range-picker/daterangepicker.css',

                'laminas-bootstrap5/js/codemirror.js' => __DIR__ . '/../public/codemirror/codemirror.js',
                'laminas-bootstrap5/css/codemirror.css' => __DIR__ . '/../public/codemirror/codemirror.css',

                'laminas-bootstrap5/js/bootstrap-select.min.js' => __DIR__ . '/../public/bootstrap-select-1.14-dev/dist/js/bootstrap-select.min.js',
                'laminas-bootstrap5/css/bootstrap-select.min.css' => __DIR__ . '/../public/bootstrap-select-1.14-dev/dist/css/bootstrap-select.min.css',
                'laminas-bootstrap5/js/ajax-bootstrap-select.min.js' => __DIR__ . '/../public/ajax-bootstrap-select/dist/js/ajax-bootstrap-select.min.js',
                'laminas-bootstrap5/css/ajax-bootstrap-select.min.css' => __DIR__ . '/../public/ajax-bootstrap-select/dist/css/ajax-bootstrap-select.css',

            ],
        ],
    ]
];
