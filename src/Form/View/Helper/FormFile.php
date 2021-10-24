<?php

namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\View\Helper;

final class FormFile extends Helper\FormFile
{
    public function render(ElementInterface $element): string
    {
        $element->setAttribute('class', 'form-control-file');

        if (\count($element->getMessages()) > 0) {
            $element->setAttribute('class', 'form-control-file is-invalid');
        }

        $element->setAttribute('id', $element->getName());

        return parent::render($element);
    }
}
