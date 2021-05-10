<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */
namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\ElementInterface;

final class FormCheckbox extends \Laminas\Form\View\Helper\FormCheckbox
{
    public function render(ElementInterface $element)
    {
        $element->setAttribute('class', 'custom-control-input');

        if (\count($element->getMessages()) > 0) {
            $element->setAttribute('class', 'custom-control-input is-invalid');
        }

        $element->setAttribute('id', \md5($element->getName()));

        return parent::render($element);
    }
}
