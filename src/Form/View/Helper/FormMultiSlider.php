<?php

namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\View\Helper;

use function count;

/**
 *
 */
final class FormMultiSlider extends Helper\FormText
{

    public function render(ElementInterface $element): string
    {
        $id = md5($element->getName());

        if (count($element->getMessages()) > 0) {
            $element->setAttribute('class', $element->getAttribute('class') . ' is-invalid');
        }

        $element->setAttribute('class', $element->getAttribute('class') . ' form-multi-slider');

        $element->setAttribute('id', $id);

        //Grab the raw values (keys)
        $rawValueOptions = array_keys($element->getValueOptions());

        //Sort values from low to high
        sort($rawValueOptions);

        $ticks = implode(', ', $rawValueOptions);
        $value = $element->getValue(); //The SearchFormResult helper transforms the result into an array

        if (null === $value) {
            $value = $ticks;
        }

        $element->setAttribute('data-provide', 'slider');
        $element->setAttribute('data-slider-ticks', "[" . $ticks . "]");
        $element->setAttribute('data-slider-ticks-labels', "[" . $ticks . "]");
        $element->setAttribute('data-slider-value', "[" . $value . "]");
        $element->setAttribute('data-slider-lock-to-ticks', true);

        return parent::render($element);
    }
}
