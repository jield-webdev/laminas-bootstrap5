<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\HelperPluginManager;

use function sprintf;

/**
 * Class FormElement
 * @package LaminasBootstrap5\Form\View\Helper
 */
class FormElement extends Helper\FormElement
{
    public const TYPE_HORIZONTAL     = 'horizontal';
    public const TYPE_INLINE         = 'inline';
    public const TYPE_DEFAULT        = 'default';
    public const TYPE_FLOATING_LABEL = 'floating_label';
    public const TYPE_ELEMENT_ONLY   = 'element_only';

    protected $typeMap
        = [
            'text'           => 'lbs5forminput',
            'email'          => 'lbs5forminput',
            'number'         => 'lbs5forminput',
            'color'          => 'lbs5forminput',
            'password'       => 'lbs5forminput',
            'url'            => 'lbs5forminput',
            'checkbox'       => 'lbs5formcheckbox',
            'file'           => 'lbs5formfile',
            'textarea'       => 'lbs5formtextarea',
            'datetime-local' => 'lbs5formdatetimelocal',
            'radio'          => 'lbs5formradio',
            'datetime'       => 'lbs5forminput',
            'date'           => 'lbs5forminput',
            'select'         => 'lbs5formselect',
            'multi_checkbox' => 'lbs5formmulticheckbox',
        ];

    protected TranslatorInterface     $translator;
    protected string                  $type = self::TYPE_HORIZONTAL;
    private ?Helper\FormLabel         $formLabel;
    private ?EscapeHtml               $escapeHtml;
    private ?FormDescription          $formDescription;
    private ?Helper\FormElementErrors $formElementErrors;

    public function __construct(HelperPluginManager $viewHelperManager, TranslatorInterface $translator)
    {
        $this->formLabel         = $viewHelperManager->get('formlabel');
        $this->escapeHtml        = $viewHelperManager->get('escapehtml');
        $this->formDescription   = $viewHelperManager->get('lbs5formdescription');
        $this->formElementErrors = $viewHelperManager->get('formelementerrors');

        $this->translator = $translator;
    }

    public function __invoke(ElementInterface $element = null, $type = self::TYPE_HORIZONTAL, bool $formElementOnly = false)
    {
        //We previously has the type a boolean with $inline
        if ($type === true) {
            $type = self::TYPE_DEFAULT;
        }

        if ($formElementOnly) {
            $type = self::TYPE_ELEMENT_ONLY;
        }

        $this->type = $type;

        if ($element) {
            return $this->render($element);
        }

        return $this;
    }

    public function render(ElementInterface $element)
    {
        $renderedType = $this->renderType($element);

        if ($renderedType !== null) {
            return $renderedType;
        }

        $element->setValue($this->translator->translate($element->getValue()));

        return parent::render($element);
    }

    protected function renderType(ElementInterface $element): ?string
    {
        $type = $element->getAttribute('type');

        if (isset($this->typeMap[$type])) {
            //Produce the label
            $label           = $this->findLabel($element);
            $renderedElement = $this->renderHelper($this->typeMap[$type], $element);
            $description     = $this->parseDescription($element);
            $error           = $this->hasFormElementError($element) ? $this->parseFormElementError($element) : null;

            if ($this->type === self::TYPE_ELEMENT_ONLY) {
                return sprintf('%s%s', $renderedElement, $error);
            }

            switch ($type) {
                case 'radio':
                    return $this->getRadioElement($label, $renderedElement, $error, $description);
                case 'multi_checkbox':
                    return $this->getMultiCheckboxElement($label, $renderedElement, $error, $description);
                case 'checkbox':
                    return $this->getCheckboxElement($renderedElement, $error, $description);
                case 'submit':
                case 'button':
                    return $renderedElement;
            }

            $label = $this->parseLabel($element);
            return $this->getDefaultElement($label, $renderedElement, $error, $description);
        }

        return null;
    }

    private function findLabel(ElementInterface $element): ?string
    {
        $label = $element->getAttribute('label') ?? $element->getLabel();

        if (null !== ($translator = $this->formLabel->getTranslator())) {
            $label = $translator->translate($label);
        }

        return $label;
    }

    private function parseDescription(ElementInterface $element): string
    {
        return $this->formDescription->__invoke($element);
    }

    private function hasFormElementError(ElementInterface $element): bool
    {
        return '' !== $this->parseFormElementError($element);
    }

    private function parseFormElementError(ElementInterface $element): string
    {
        $this->formElementErrors->setMessageOpenFormat('<div class="invalid-feedback"><span%s>');
        $this->formElementErrors->setMessageSeparatorString('<br>');
        $this->formElementErrors->setMessageCloseString('</span></div>');

        return $this->formElementErrors->__invoke($element);
    }

    private function getRadioElement(string $label, string $element, ?string $error, string $description): string
    {
        switch ($this->type) {
            case self::TYPE_HORIZONTAL:
            default:
                return sprintf('<fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-3 pt-0">%s</legend>
                                    <div class="col-sm-9">
                                        %s
                                        %s
                                        %s
                                    </div>
                             </fieldset>', $label, $element, $error, $description);
            case self::TYPE_DEFAULT:
                return sprintf('%s%s%s%s', $label, $element, $error, $description);
        }
    }

    private function getMultiCheckboxElement(string $label, string $element, ?string $error, string $description): string
    {
        switch ($this->type) {
            case self::TYPE_HORIZONTAL:
            default:
                return sprintf('<fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-3 pt-0">%s</legend>
                                    <div class="col-sm-9">
                                        %s
                                        %s
                                        %s
                                    </div>
                             </fieldset>', $label, $element, $error, $description);
            case self::TYPE_DEFAULT:
                return sprintf('<div class="mb-3"><label class="form-label"><strong>%s</strong></label>%s%s%s</div>', $label, $element, $error, $description);
        }
    }

    private function getCheckboxElement(string $element, ?string $error, string $description): string
    {
        switch ($this->type) {
            case self::TYPE_HORIZONTAL:
            default:
                return sprintf('<div class="row mb-3">
                                    <div class="col-sm-9 offset-sm-3">
                                        %s
                                        %s
                                        %s
                                    </div>
                             </div>', $element, $error, $description);
            case self::TYPE_DEFAULT:
                return sprintf('<div class="mb-3">%s%s%s</div>', $element, $error, $description);
        }

    }

    protected function parseLabel(ElementInterface $element): string
    {
        $label = $this->findLabel($element);

        if (null === $label) {
            return '';
        }

        $openTagAttributes = ['for' => $element->getName()];

        if ($this->type === self::TYPE_HORIZONTAL) {
            $openTagAttributes['class'] = 'col-sm-3 col-form-label';
        }

        $openTag = $this->formLabel->openTag($openTagAttributes);


        if (!$element instanceof LabelAwareInterface || !$element->getLabelOption('disable_html_escape')) {
            $label = $this->escapeHtml->__invoke($label);
        }

        return $openTag . $label . $this->formLabel->closeTag();
    }

    private function getDefaultElement($label, $element, $error, $description): string
    {
        switch ($this->type) {
            case self::TYPE_HORIZONTAL:
            default:
                return sprintf('<div class="row mb-3">%s<div class="col-sm-9">%s%s%s</div></div>', $label, $element, $error, $description);
            case self::TYPE_INLINE:
            case self::TYPE_DEFAULT:
                return sprintf('%s%s%s%s', $label, $element, $error, $description);
            case self::TYPE_ELEMENT_ONLY:
                return sprintf('%s%s%s', $element, $error, $description);
            case self::TYPE_FLOATING_LABEL:
                return sprintf('<div class="form-floating mb-3">%s%s%s%s</div>', $element, $label, $error, $description);
        }

    }
}
