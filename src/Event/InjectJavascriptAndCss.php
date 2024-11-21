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
                '//code.jquery.com/jquery-3.7.1.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=',
                ]
            );
        }

        if ($injectJqueryUI) {
            $this->renderer->headScript()->appendFile(
                '//code.jquery.com/ui/1.13.2/jquery-ui.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=',
                ]
            );
        }

        if ($injectBootstrapJS) {
            $this->renderer->headScript()->appendFile(
                '//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz',
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
