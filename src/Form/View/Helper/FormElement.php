<?php

namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper;
use Laminas\Translator\TranslatorInterface;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\HelperPluginManager;
use function sprintf;

/**
 * Class FormElement
 * @package LaminasBootstrap5\Form\View\Helper
 */
class FormElement extends Helper\FormElement
{
    public const TYPE_HORIZONTAL = 'horizontal';
    public const TYPE_INLINE = 'inline';
    public const TYPE_DEFAULT = 'default';
    public const TYPE_FLOATING_LABEL = 'floating_label';
    public const TYPE_ELEMENT_ONLY = 'element_only';

    public const ELEMENT_COLS_DEFAULT = 9;
    public const ELEMENT_COLS_SMALL = 3;

    protected bool $isSelectPicker = false;

    protected bool $isRendered = false;

    private int $elementCols = self::ELEMENT_COLS_DEFAULT;

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

    protected TranslatorInterface $translator;

    protected string $type = self::TYPE_HORIZONTAL;

    private ?Helper\FormLabel $formLabel;

    private ?EscapeHtml $escapeHtml;

    private ?FormDescription $formDescription;

    private ?Helper\FormElementErrors $formElementErrors;

    public function __construct(HelperPluginManager $viewHelperManager, TranslatorInterface $translator)
    {
        $this->formLabel         = $viewHelperManager->get(name: 'formlabel');
        $this->escapeHtml        = $viewHelperManager->get(name: 'escapehtml');
        $this->formDescription   = $viewHelperManager->get(name: 'lbs5formdescription');
        $this->formElementErrors = $viewHelperManager->get(name: 'formelementerrors');

        $this->translator = $translator;
    }

    public function __invoke(
        ?ElementInterface $element = null,
        string            $type = self::TYPE_HORIZONTAL,
        bool              $formElementOnly = false
    )
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
            return $this->render(element: $element);
        }

        return $this;
    }

    public function render(ElementInterface $element): string
    {
        if ($element->getAttribute(key: 'type') === 'select') {
            if ($element->getOption(option: 'searchable')) {
                $element->setAttribute(key: 'class', value: 'form-control selectpicker');
                $element->setAttribute(key: 'data-live-search', value: 'true');

                //Enable the selectpicker
                $this->setIsSelectPicker(isSelectPicker: true);
            }

            if ($this->isSelectPicker && !$this->isRendered) {
                $this->view->headLink()->appendStylesheet('assets/css/laminas-bootstrap5/bootstrap-select.css');
                $this->view->headScript()->appendFile('assets/js/laminas-bootstrap5/bootstrap-select.js', 'text/javascript');

                $this->isRendered = true;
            }

            if ($this->isSelectPicker) {
                $element->setAttribute(key: 'class', value: 'form-control selectpicker');
                $element->setAttribute(key: 'data-live-search', value: 'true');
            }
        }

        if ($element->getOption(option: 'is-date-range')) {
            $this->view->headLink()->appendStylesheet('assets/css/laminas-bootstrap5/daterangepicker.css');
            $this->view->headScript()->appendFile('assets/js/laminas-bootstrap5/daterangepicker.js', 'text/javascript');

            $element->setAttribute(key: 'class', value: 'daterangepicker-element form-control');
            $element->setAttribute(key: 'placeholder', value: 'Click to set a date interval');
        }

        if ($element->getOption(option: 'has-codemirror')) {
            $this->view->headScript()->appendFile('assets/js/laminas-bootstrap5/codemirror.js', 'text/javascript');
            $this->view->headLink()->appendStylesheet('assets/css/laminas-bootstrap5/codemirror.css');

            $element->setAttribute(key: 'class', value: 'codemirror-element form-control');
            $element->setAttribute(key: 'data-mode', value: $element->getOption(option: 'mode'));
            $element->setAttribute(key: 'data-line-numbers', value: $element->getOption(option: 'line-numbers'));
            $element->setAttribute(key: 'data-height', value: $element->getOption(option: 'height'));
        }

        $renderedType = $this->renderType(element: $element);

        if ($renderedType !== null) {
            return $renderedType;
        }

        $element->setValue(value: $this->translator->translate(message: $element->getValue()));

        return parent::render(element: $element);
    }

    protected function renderType(ElementInterface $element): ?string
    {
        $type = $element->getAttribute(key: 'type');

        if (isset($this->typeMap[$type])) {
            //Produce the label
            $label           = $this->findLabel(element: $element);
            $renderedElement = $this->renderHelper(name: $this->typeMap[$type], element: $element);
            $description     = $this->parseDescription(element: $element);
            $error           = $this->hasFormElementError(element: $element) ? $this->parseFormElementError(element: $element) : null;

            if ($this->type === self::TYPE_ELEMENT_ONLY) {
                return sprintf('%s%s', $renderedElement, $error);
            }

            $this->elementCols = self::ELEMENT_COLS_DEFAULT;
            if (in_array(needle: $type, haystack: ['date', 'datetime-local'])) {
                $this->elementCols = self::ELEMENT_COLS_SMALL;
            }

            switch ($type) {
                case 'radio':
                    return $this->getRadioElement(label: $label, element: $renderedElement, error: $error, description: $description);
                case 'multi_checkbox':
                    return $this->getMultiCheckboxElement(label: $label, element: $renderedElement, error: $error, description: $description);
                case 'checkbox':
                    return $this->getCheckboxElement(element: $renderedElement, error: $error, description: $description);
                case 'submit':
                case 'button':
                    return $renderedElement;
            }

            $label = $this->parseLabel(element: $element);
            return $this->getDefaultElement(label: $label, element: $renderedElement, error: $error, description: $description);
        }

        return null;
    }

    private function findLabel(ElementInterface $element): ?string
    {
        $label = $element->getAttribute(key: 'label') ?? $element->getLabel();

        if (null !== ($translator = $this->formLabel->getTranslator())) {
            $label = $translator->translate(message: $label);
        }

        return $label;
    }

    protected function parseDescription(ElementInterface $element): string
    {
        return $this->formDescription->__invoke(element: $element);
    }

    protected function hasFormElementError(ElementInterface $element): bool
    {
        return '' !== $this->parseFormElementError(element: $element);
    }

    protected function parseFormElementError(ElementInterface $element): string
    {
        $this->formElementErrors->setMessageOpenFormat(messageOpenFormat: '<div class="invalid-feedback"><span%s>');
        $this->formElementErrors->setMessageSeparatorString(messageSeparatorString: '<br>');
        $this->formElementErrors->setMessageCloseString(messageCloseString: '</span></div>');

        return $this->formElementErrors->__invoke(element: $element);
    }

    private function getRadioElement(string $label, string $element, ?string $error, string $description): string
    {
        switch ($this->type) {
            case self::TYPE_HORIZONTAL:
            default:
                return sprintf(
                    '<fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-3 pt-0">%s</legend>
                                    <div class="col-sm-%d">
                                        %s
                                        %s
                                        %s
                                    </div>
                             </fieldset>',
                    $label,
                    $this->elementCols,
                    $element,
                    $error,
                    $description
                );
            case self::TYPE_DEFAULT:
                return sprintf('%s%s%s%s', $label, $element, $error, $description);
        }
    }

    private function getMultiCheckboxElement(
        string  $label,
        string  $element,
        ?string $error,
        string  $description
    ): string
    {
        switch ($this->type) {
            case self::TYPE_HORIZONTAL:
            default:
                return sprintf(
                    '<fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-3 pt-0">%s</legend>
                                    <div class="col-sm-%d">
                                        %s
                                        %s
                                        %s
                                    </div>
                             </fieldset>',
                    $label,
                    $this->elementCols,
                    $element,
                    $error,
                    $description
                );
            case self::TYPE_DEFAULT:
                return sprintf(
                    '<div class="mb-3"><label class="form-label"><strong>%s</strong></label>%s%s%s</div>',
                    $label,
                    $element,
                    $error,
                    $description
                );
        }
    }

    private function getCheckboxElement(string $element, ?string $error, string $description): string
    {
        switch ($this->type) {
            case self::TYPE_HORIZONTAL:
            default:
                return sprintf(
                    '<div class="row mb-3">
                                    <div class="col-sm-%d offset-sm-3">
                                        %s
                                        %s
                                        %s
                                    </div>
                             </div>',
                    $this->elementCols,
                    $element,
                    $error,
                    $description
                );
            case self::TYPE_DEFAULT:
                return sprintf('<div class="mb-3">%s%s%s</div>', $element, $error, $description);
        }
    }

    protected function parseLabel(ElementInterface $element): string
    {
        $label = $this->findLabel(element: $element);

        if (null === $label) {
            return '';
        }

        $openTagAttributes = ['for' => $element->getName()];

        if ($this->type === self::TYPE_HORIZONTAL) {
            $openTagAttributes['class'] = 'col-sm-3 col-form-label';
        }

        if ($element->hasAttribute(key: 'required')) {
            $openTagAttributes['class'] .= ' required';
        }


        $openTag = $this->formLabel->openTag(attributesOrElement: $openTagAttributes);

        if (!$element instanceof LabelAwareInterface || !$element->getLabelOption(key: 'disable_html_escape')) {
            $label = $this->escapeHtml->__invoke(value: $label);
        }

        return $openTag . $label . $this->formLabel->closeTag();
    }

    private function getDefaultElement($label, $element, $error, $description): string
    {
        switch ($this->type) {
            case self::TYPE_HORIZONTAL:
            default:
                return sprintf(
                    '<div class="row mb-3">%s<div class="col-sm-%d">%s%s%s</div></div>',
                    $label,
                    $this->elementCols,
                    $element,
                    $error,
                    $description
                );
            case self::TYPE_INLINE:
            case self::TYPE_DEFAULT:
                return sprintf('%s%s%s%s', $label, $element, $error, $description);
            case self::TYPE_ELEMENT_ONLY:
                return sprintf('%s%s%s', $element, $error, $description);
            case self::TYPE_FLOATING_LABEL:
                return sprintf(
                    '<div class="form-floating mb-3">%s%s%s%s</div>',
                    $element,
                    $label,
                    $error,
                    $description
                );
        }
    }

    public function setIsSelectPicker(bool $isSelectPicker): FormElement
    {
        $this->isSelectPicker = $isSelectPicker;

        return $this;
    }
}
