<?php

namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\ElementInterface;

/**
 * Class FormRadio
 * @package LaminasBootstrap5\Form\View\Helper
 */
final class FormRadio extends FormMultiCheckbox
{
    protected static function getName(ElementInterface $element): string
    {
        return $element->getName();
    }

    protected function getInputType(): string
    {
        return 'radio';
    }
}
