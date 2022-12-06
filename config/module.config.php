<?php

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use LaminasBootstrap5\Form\View;
use LaminasBootstrap5\Navigation;
use LaminasBootstrap5\View\Helper;

return [
    'laminas-bootstrap5'         => [
        'inject_jquery'       => true,
        'inject_jquery_ui'    => true,
        'inject_bootstrap_js' => true,
    ],
    'view_helpers'               => [
        'aliases'    => [
            //Add some BC changes with previous module
            'ztbalert'         => 'lbs5alert',
            'ztbformelement'   => 'lbs5formelement',
            'filterbarelement' => 'lbs5filterbarelement',
            'initHighlightJs'  => 'lbs5initHighlightJs',

            'lbs5navigation'          => Helper\Navigation::class,
            'lbs5filterbarelement'    => View\Helper\FilterBarElement::class,
            'lbs5filtercolumnelement' => View\Helper\FilterColumnElement::class,
            'lbs5formelement'         => View\Helper\FormElement::class,
            'lbs5formcheckbox'        => View\Helper\FormCheckbox::class,

        ],
        'factories'  => [
            Helper\Navigation::class               => Navigation\View\NavigationHelperFactory::class,
            View\Helper\FormElement::class         => ConfigAbstractFactory::class,
            View\Helper\FormCheckbox::class        => ConfigAbstractFactory::class,
            View\Helper\FilterBarElement::class    => ConfigAbstractFactory::class,
            View\Helper\FilterColumnElement::class => ConfigAbstractFactory::class,
        ],
        'invokables' => [
            'lbs5formdescription'   => View\Helper\FormDescription::class,
            'lbs5formdatetimelocal' => View\Helper\FormDateTimeLocal::class,
            'lbs5forminput'         => View\Helper\FormInput::class,
            'lbs5formmultislider'   => View\Helper\FormMultiSlider::class,
            'lbs5formfile'          => View\Helper\FormFile::class,
            'lbs5formradio'         => View\Helper\FormRadio::class,
            'lbs5formtextarea'      => View\Helper\FormTextarea::class,
            'lbs5formselect'        => View\Helper\FormSelect::class,
            'lbs5formmulticheckbox' => View\Helper\FormMultiCheckbox::class,
            'lbs5alert'             => Helper\Alert::class,
            'initHighlightJs'       => Helper\InitHighlightJs::class,
        ],
    ],
    ConfigAbstractFactory::class => [
        View\Helper\FormElement::class                         => [
            'ViewHelperManager',
            \Laminas\I18n\Translator\TranslatorInterface::class
        ],
        View\Helper\FormCheckbox::class                        => [
            'ViewHelperManager',
            \Laminas\I18n\Translator\TranslatorInterface::class
        ],
        View\Helper\FilterBarElement::class                    => [
            'ViewHelperManager',
            \Laminas\I18n\Translator\TranslatorInterface::class
        ],
        View\Helper\FilterColumnElement::class                 => [
            'ViewHelperManager',
            \Laminas\I18n\Translator\TranslatorInterface::class
        ],
        \LaminasBootstrap5\Event\InjectJavascriptAndCss::class => [
            'Config',
            \Laminas\View\Renderer\PhpRenderer::class
        ]
    ],
    'service_manager'            => [
        'factories' => [
            \LaminasBootstrap5\Event\InjectJavascriptAndCss::class => ConfigAbstractFactory::class,
        ]
    ],
    'asset_manager'              => [
        'resolver_configs' => [
            'collections' => [
                'assets/js/laminas-bootstrap5/main.js'               => [
                    'main/form-submit.js',
                    'simple-load-more/jquery.simpleLoadMore.min.js',
                    'bootstrap-slider/bootstrap-slider.min.js',
                ],
                'assets/css/laminas-bootstrap5/main.css'             => [
                    'bootstrap-slider/css/bootstrap-slider.min.css',
                ],
                'assets/js/laminas-bootstrap5/filter-column.js'      => [
                    'filter-column/filter-column.js',
                ],
                'assets/css/laminas-bootstrap5/filter-column.css'    => [
                    'filter-column/filter-column.css',
                ],
                'assets/js/laminas-bootstrap5/daterangepicker.js'    => [
                    'date-range-picker/moment.min.js',
                    'date-range-picker/daterangepicker.js',
                    'main/form-datepicker.js',
                ],
                'assets/css/laminas-bootstrap5/daterangepicker.css'  => [
                    'date-range-picker/daterangepicker.css',
                ],
                'assets/js/laminas-bootstrap5/codemirror.js'         => [
                    'codemirror/codemirror.js',
                    'main/form-codemirror.js',
                    'codemirror/mode/xml/xml.js',
                    'codemirror/mode/htmlmixed/htmlmixed.js',
                    'codemirror/mode/css/css.js',
                    'codemirror/mode/javascript/javascript.js',
                    'codemirror/mode/sql/sql.js',
                    'codemirror/mode/markdown/markdown.js',
                    'codemirror/mode/twig/twig.js',
                ],
                'assets/css/laminas-bootstrap5/codemirror.css'       => [
                    'codemirror/codemirror.css'
                ],
                'assets/js/laminas-bootstrap5/bootstrap-select.js'   => [
                    'bootstrap-select-1.14-dev/dist/js/bootstrap-select.min.js',
                    'main/form-bootstrap-select.js',
                    'ajax-bootstrap-select/dist/js/ajax-bootstrap-select.min.js',
                ],
                'assets/css/laminas-bootstrap5/bootstrap-select.css' => [
                    'bootstrap-select-1.14-dev/dist/css/bootstrap-select.min.css',
                    'ajax-bootstrap-select/dist/css/ajax-bootstrap-select.css',
                ],
                'assets/js/laminas-bootstrap5/highlight.js'          => [
                    'highlight.js/highlight.min.js'
                ],
                'assets/css/laminas-bootstrap5/highlight.css'        => [
                    'highlight.js/default.min.css',
                    'highlight.js/github.css',
                ],
                'assets/js/laminas-bootstrap5/sortable.js'           => [
                    'sortable-js/Sortable.min.js'
                ],
            ],
            'paths'       => [
                __DIR__ . '/../public',
            ],
        ],
        'caching'          => [
            'assets/js/laminas-bootstrap5/main.js'               => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/css/laminas-bootstrap5/main.css'             => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/js/laminas-bootstrap5/filter-column.js'      => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/css/laminas-bootstrap5/filter-column.css'    => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/js/laminas-bootstrap5/daterangepicker.js'    => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/css/laminas-bootstrap5/daterangepicker.css'  => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/js/laminas-bootstrap5/codemirror.js'         => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/css/laminas-bootstrap5/codemirror.css'       => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/js/laminas-bootstrap5/bootstrap-select.js'   => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/css/laminas-bootstrap5/bootstrap-select.css' => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/js/laminas-bootstrap5/highlight.js'          => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/css/laminas-bootstrap5/highlight.css'        => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
            'assets/js/laminas-bootstrap5/sortable.js'           => [
                'cache'   => 'FilePath',
                'options' => [
                    'dir' => __DIR__ . '/../../../../public',
                ],
            ],
        ],
    ],
];
