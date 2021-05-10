<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

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
