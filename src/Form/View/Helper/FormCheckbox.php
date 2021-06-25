<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\View\HelperPluginManager;
use Laminas\Form\View\Helper;

/**
 * Class FormCheckbox
 * @package LaminasBootstrap5\Form\View\Helper
 */
final class FormCheckbox extends \Laminas\Form\View\Helper\FormCheckbox
{
    private Helper\FormLabel $formLabel;

    public function __construct(HelperPluginManager $viewHelperManager, TranslatorInterface $translator)
    {
        $this->formLabel         = $viewHelperManager->get('formlabel');

        $this->translator = $translator;
    }

    public function render(ElementInterface $element): string
    {
        $element->setAttribute('class', 'form-check-input');

        if (\count($element->getMessages()) > 0) {
            $element->setAttribute('class', 'form-check-input is-invalid');
        }

        $element->setAttribute('id', \md5($element->getName()));

        $renderedElement = parent::render($element);

        $template = '<div class="form-check form-switch">%s<label class="form-check-label" for="%s">%s</label></div>';

        return sprintf($template, $renderedElement, \md5($element->getName()), $this->findLabel($element));
    }

    private function findLabel(ElementInterface $element): ?string
    {
        $label = $element->getAttribute('label') ?? $element->getLabel();

        if (null !== ($translator = $this->formLabel->getTranslator())) {
            $label = $translator->translate($label);
        }

        return $label;
    }
}
