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

use function count;

/**
 * Class FormSelect
 * @package LaminasBootstrap5\Form\View\Helper
 */
final class FormSelect extends Helper\FormSelect
{
    public function render(ElementInterface $element): string
    {
        if (null === $element->getAttribute('class')) {
            $element->setAttribute('class', 'form-control');
        }

        if (count($element->getMessages()) > 0) {
            $element->setAttribute('class', $element->getAttribute('class') . ' is-invalid');
        }

        if (null === $element->getAttribute('id')) {
            $element->setAttribute('id', $element->getName());
        }

        return parent::render($element);
    }
}
