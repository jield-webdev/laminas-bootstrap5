<?php

namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\Element\MultiCheckbox;
use Laminas\Form\ElementInterface;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper;

use function array_key_exists;
use function array_merge;
use function count;
use function implode;
use function in_array;
use function is_scalar;
use function md5;
use function method_exists;

/**
 * Class FormMultiCheckbox
 * @package LaminasBootstrap5\Form\View\Helper
 */
class FormMultiCheckbox extends Helper\FormMultiCheckbox
{
    private string $template = '<div class="form-check %s">%s%s%s%s</div>';

    public function render(ElementInterface|MultiCheckbox $element): string
    {
        $name = static::getName($element);

        $options = $element->getValueOptions();

        $attributes         = $element->getAttributes();
        $attributes['name'] = $name;
        $attributes['type'] = $this->getInputType();
        $selectedOptions    = (array)$element->getValue();

        $rendered = $this->renderOptions($element, $options, $selectedOptions, $attributes);

        // Render hidden element
        $useHiddenElement = method_exists($element, 'useHiddenElement') && $element->useHiddenElement()
            ? $element->useHiddenElement()
            : $this->useHiddenElement;

        if ($useHiddenElement) {
            $rendered = $this->renderHiddenElement($element) . $rendered;
        }

        return $rendered;
    }

    protected function renderOptions(
        ElementInterface $element,
        array $options,
        array $selectedOptions,
        array $attributes
    ): string {
        $escapeHtmlHelper      = $this->getEscapeHtmlHelper();
        $labelHelper           = $this->getLabelHelper();
        $labelClose            = $labelHelper->closeTag();
        $labelPosition         = $this->getLabelPosition();
        $globalLabelAttributes = [];
        $closingBracket        = $this->getInlineClosingBracket();

        if ($element instanceof LabelAwareInterface) {
            $globalLabelAttributes = $element->getLabelAttributes();
        }

        if (empty($globalLabelAttributes)) {
            $globalLabelAttributes = $this->labelAttributes;
        }

        $combinedMarkup = [];
        $count          = 0;

        foreach ($options as $key => $optionSpec) {
            $count++;
            if ($count > 1 && array_key_exists('id', $attributes)) {
                unset($attributes['id']);
            }

            $value           = '';
            $label           = '';
            $inputAttributes = $attributes;
            $labelAttributes = $globalLabelAttributes;
            $selected        = (isset($inputAttributes['selected'])
                && $inputAttributes['type'] !== 'radio'
                && $inputAttributes['selected']);
            $disabled        = (isset($inputAttributes['disabled']) && $inputAttributes['disabled']);

            if (is_scalar($optionSpec)) {
                $optionSpec = [
                    'label' => $optionSpec,
                    'value' => $key
                ];
            }

            if (isset($optionSpec['value'])) {
                $value = $optionSpec['value'];
            }
            if (isset($optionSpec['label'])) {
                $label = $optionSpec['label'];
            }
            if (isset($optionSpec['selected'])) {
                $selected = $optionSpec['selected'];
            }
            if (isset($optionSpec['disabled'])) {
                $disabled = $optionSpec['disabled'];
            }
            if (isset($optionSpec['label_attributes'])) {
                $labelAttributes = array_merge($labelAttributes, $optionSpec['label_attributes']);
            }
            if (isset($optionSpec['attributes'])) {
                $inputAttributes = array_merge($inputAttributes, $optionSpec['attributes']);
            }

            if (in_array($value, $selectedOptions)) {
                $selected = true;
            }

            $elementId                = md5($element->getName() . $label . $value);
            $inputAttributes['class'] = 'form-check-input';

            if (count($element->getMessages()) > 0) {
                $inputAttributes['class'] = 'form-check-input is-invalid';
            }

            $inputAttributes['id'] = $elementId;

            $inputAttributes['value']    = $value;
            $inputAttributes['checked']  = $selected;
            $inputAttributes['disabled'] = $disabled;

            $input = sprintf(
                '<input %s%s',
                $this->createAttributesString($inputAttributes),
                $closingBracket
            );

            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label,
                    $this->getTranslatorTextDomain()
                );
            }

            if (!$element instanceof LabelAwareInterface || !$element->getLabelOption('disable_html_escape')) {
                $label = $escapeHtmlHelper($label);
            }

            $labelAttributes['for']   = $elementId;
            $labelAttributes['class'] = 'form-check-label';

            $labelOpen = $labelHelper->openTag($labelAttributes);

            $inlineClass = '';

            switch ($labelPosition) {
                case self::LABEL_PREPEND:
                    $markup = sprintf($this->template, $inlineClass, $label, $labelOpen, $input, $labelClose);
                    break;
                case self::LABEL_APPEND:
                default:
                    $markup = sprintf($this->template, $inlineClass, $input, $labelOpen, $label, $labelClose);
                    break;
            }

            $combinedMarkup[] = $markup;
        }

        return implode($this->getSeparator(), $combinedMarkup);
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }
}
