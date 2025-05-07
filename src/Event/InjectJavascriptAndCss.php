<?php

declare(strict_types=1);

namespace LaminasBootstrap5\Event;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Renderer\PhpRenderer;

class InjectJavascriptAndCss extends AbstractListenerAggregate
{
    public function __construct(private readonly array $config, private readonly PhpRenderer $renderer)
    {
    }

    public function attach(EventManagerInterface $events, $priority = 1): void
    {
        $this->listeners[] = $events->attach(eventName: MvcEvent::EVENT_RENDER, listener: $this->setHeadScript(...), priority: 1000);
    }

    public function setHeadScript(): void
    {
        $injectJquery      = $this->config['laminas-bootstrap5']['inject_jquery'] ?? true;
        $injectJqueryUI    = $this->config['laminas-bootstrap5']['inject_jquery_ui'] ?? true;
        $injectBootstrapJS = $this->config['laminas-bootstrap5']['inject_bootstrap_js'] ?? true;

        if ($injectJquery) {
            $this->renderer->headScript()->appendFile(
                '//cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==',
                ]
            );
        }

        if ($injectJqueryUI) {
            $this->renderer->headScript()->appendFile(
                '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/jquery-ui.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha512-MSOo1aY+3pXCOCdGAYoBZ6YGI0aragoQsg1mKKBHXCYPIWxamwOE7Drh+N5CPgGI5SA9IEKJiPjdfqWFWmZtRA==',
                ]
            );
        }

        if ($injectBootstrapJS) {
            $this->renderer->headScript()->appendFile(
                '//cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO',
                ]
            );
        }

        $this->renderer->headScript()->appendFile(
            '/assets/js/laminas-bootstrap5/main.js',
            'text/javascript',
            [
                'crossorigin' => 'anonymous',
            ]
        );
        $this->renderer->headLink()->appendStylesheet('/assets/css/laminas-bootstrap5/main.css');
    }
}
