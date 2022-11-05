<?php

declare(strict_types=1);

namespace LaminasBootstrap5\Event;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Renderer\PhpRenderer;

class InjectJavascriptAndCss extends AbstractListenerAggregate
{
    private PhpRenderer $renderer;

    public function __construct(PhpRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function attach(EventManagerInterface $events, $priority = 1): void
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, fn() => $this->setHeadLink(), 1000);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, fn() => $this->setHeadScript(), 1000);
    }

    public function setHeadLink(): void
    {
        $this->renderer->headLink()->appendStylesheet(
            '/laminas-bootstrap5/css/style.css',
            'all',
            null,
            null
        );
    }

    public function setHeadScript(): void
    {
        $this->renderer->headScript()->appendFile(
            '//cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js',
            'text/javascript',
            [
                'crossorigin' => 'anonymous',
                'integrity' => 'sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=',
            ]
        );
        $this->renderer->headScript()->appendFile(
            '//code.jquery.com/ui/1.13.1/jquery-ui.min.js',
            'text/javascript',
            [
                'crossorigin' => 'anonymous',
                'integrity' => 'sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=',
            ]
        );

        $this->renderer->headScript()->appendFile(
            '/laminas-bootstrap5/js/form-submit.js',
            'text/javascript',
            [
                'crossorigin' => 'anonymous',
            ]
        );
    }
}