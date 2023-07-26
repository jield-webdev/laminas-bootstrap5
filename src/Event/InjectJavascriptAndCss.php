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
                '//code.jquery.com/jquery-3.7.0.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=',
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
                '//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz',
                ]
            );
        }

        $this->renderer->headScript()->appendFile(
            'assets/js/laminas-bootstrap5/main.js',
            'text/javascript',
            [
                'crossorigin' => 'anonymous',
            ]
        );
        $this->renderer->headLink()->appendStylesheet('assets/css/laminas-bootstrap5/main.css');
    }
}
