<?php

namespace LaminasBootstrap5\View\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 *
 */
class InitHighlightJs extends AbstractHelper
{
    public function __invoke()
    {
        $this->getView()->headScript()->appendFile('laminas-bootstrap5/js/highlight.js/highlight.min.js');
        $this->getView()->headLink()->appendStylesheet('laminas-bootstrap5/css/highlight.js/default.min.css');
        $this->getView()->headLink()->appendStylesheet('laminas-bootstrap5/css/highlight.js/github.css');

        $this->getView()->inlineScript()->appendScript(
            'jQuery(document).ready(function() {hljs.highlightAll();});'
        );

        return $this;
    }
}
