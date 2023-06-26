<?php

namespace LaminasBootstrap5\View\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 *
 */
class InitHighlightJs extends AbstractHelper
{
    public function __invoke(): static
    {
        $this->getView()->headScript()->appendFile('assets/js/laminas-bootstrap5/highlight.js');
        $this->getView()->headLink()->appendStylesheet('assets/css/laminas-bootstrap5/highlight.css');

        $this->getView()->inlineScript()->appendScript(
            'jQuery(document).ready(function() {hljs.highlightAll();});'
        );

        return $this;
    }
}
