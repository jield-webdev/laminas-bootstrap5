<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\View\Helper;

final class FormFile extends Helper\FormFile
{
    public function render(ElementInterface $element)
    {
        $element->setAttribute('class', 'form-control-file');

        if (\count($element->getMessages()) > 0) {
            $element->setAttribute('class', 'form-control-file is-invalid');
        }

        $element->setAttribute('id', $element->getName());

        return parent::render($element);
    }
}
