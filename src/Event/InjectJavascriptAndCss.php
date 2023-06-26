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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, $this->setHeadScript(...), 1000);
    }

    public function setHeadScript(): void
    {
        $injectJquery      = $this->config['laminas-bootstrap5']['inject_jquery'] ?? true;
        $injectJqueryUI    = $this->config['laminas-bootstrap5']['inject_jquery_ui'] ?? true;
        $injectBootstrapJS = $this->config['laminas-bootstrap5']['inject_bootstrap_js'] ?? true;

        if ($injectJquery) {
            $this->renderer->headScript()->appendFile(
                '//cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=',
                ]
            );
        }

        if ($injectJqueryUI) {
            $this->renderer->headScript()->appendFile(
                '//code.jquery.com/ui/1.13.1/jquery-ui.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=',
                ]
            );
        }

        if ($injectBootstrapJS) {
            $this->renderer->headScript()->appendFile(
                '//cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js',
                'text/javascript',
                [
                    'crossorigin' => 'anonymous',
                    'integrity'   => 'sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3',
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
