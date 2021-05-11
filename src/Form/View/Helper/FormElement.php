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

use function md5;
use function sprintf;

/**
 * Class FormElement
 * @package LaminasBootstrap5\Form\View\Helper
 */
class FormElement extends Helper\FormElement
{
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
    protected bool $inline = false;
    protected bool $formElementOnly = false;
    private Helper\FormLabel $formLabel;
    private EscapeHtml $escapeHtml;
    private FormDescription $formDescription;
    private Helper\FormElementErrors $formElementErrors;
    private string $inlineWrapper = '<div class="form-group">%s%s%s%s</div>';

    private string $formElementOnlyWrapper = '%s%s';
    private string $horizontalWrapper = '<div class="row mb-3">%s<div class="col-sm-9">%s%s%s</div></div>';
    private string $radioWrapper = '<fieldset class="form-group">
                                <div class="row">
                                    <legend class="col-form-label col-sm-3 pt-0">%s</legend>
                                    <div class="col-sm-9">
                                        %s
                                        %s
                                        %s
                                    </div>
                                </div>
                             </fieldset>';
    private string $inlineRadioWrapper = '<div class="form-group">
                                    <strong class="col-form-label">%s</strong>
                                        %s
                                        %s
                                        %s                                
                             </div>';
    private string $checkboxWrapper = '<div class="row mb-3">
                                    <div class="col-form-label col-sm-3 pt-0">%s</div>
                                    <div class="col-sm-9">
                                        %s
                                        %s
                                        %s
                                    </div>
                             </div>';
    private string $inlineCheckboxWrapper = '<div class="form-group">
                                    <strong class="col-form-label">%s</strong>
                                        %s
                                        %s
                                        %s
                                    </div>';
    private string $singleCheckboxWrapper = '<div class="row mb-3">
                                                <div class="col-sm-9 offset-sm-3">
                                                    <div class="custom-control custom-switch">
                                                        %s
                                                        %s
                                                        %s
                                                        %s                                                       
                                                    </div>
                                                    
                                                </div>    
                                            </div>';
    private string $inlineSingleCheckboxWrapper = '<div class="custom-control custom-checkbox">
                                        %s
                                        %s
                                    </div>';

    public function __construct(HelperPluginManager $viewHelperManager, TranslatorInterface $translator)
    {
        $this->formLabel         = $viewHelperManager->get('formlabel');
        $this->escapeHtml        = $viewHelperManager->get('escapehtml');
        $this->formDescription   = $viewHelperManager->get('lbs5formdescription');
        $this->formElementErrors = $viewHelperManager->get('formelementerrors');

        $this->translator = $translator;
    }

    public function __invoke(ElementInterface $element = null, bool $inline = false, bool $formElementOnly = false)
    {
        $this->inline          = $inline;
        $this->formElementOnly = $formElementOnly;

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

            $wrapper = $this->horizontalWrapper;

            if ($this->inline) {
                $wrapper = $this->inlineWrapper;
            }

            switch ($type) {
                case 'radio':
                    $wrapper = $this->radioWrapper;

                    if ($this->inline) {
                        $wrapper = $this->inlineRadioWrapper;
                    }
                    break;
                case 'multi_checkbox':
                    $wrapper = $this->checkboxWrapper;

                    if ($this->inline) {
                        $wrapper = $this->inlineCheckboxWrapper;
                    }
                    break;
                case 'checkbox':
                    $wrapper = $this->singleCheckboxWrapper;

                    if ($this->inline) {
                        $wrapper = $this->inlineSingleCheckboxWrapper;
                    }

                    $label = '<label class="custom-control-label" for="' . md5($element->getName()) . '">' . $label
                        . '</label>';
                    return sprintf($wrapper, $renderedElement, $label, $error, $description);

                    break;
                case 'submit':
                case 'button':
                    return $renderedElement;
                default:
                    $label = $this->parseLabel($element);
            }

            if ($this->formElementOnly) {
                return sprintf($this->formElementOnlyWrapper, $renderedElement, $error);
            }

            return sprintf($wrapper, $label, $renderedElement, $error, $description);
        }

        return null;
    }

    private function findLabel(ElementInterface $element): ?string
    {
        $label = $element->getLabel();
        if (null !== $element->getAttribute('label')) {
            $label = $element->getAttribute('label');
        }

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

    protected function parseLabel(ElementInterface $element): string
    {
        $label = $this->findLabel($element);

        if (null === $label) {
            return '';
        }

        $openTagAttributes = ['for' => $element->getName()];

        if (!$this->inline) {
            $openTagAttributes['class'] = 'col-sm-3 col-form-label';
        }

        $openTag = $this->formLabel->openTag($openTagAttributes);


        if (!$element instanceof LabelAwareInterface || !$element->getLabelOption('disable_html_escape')) {
            $label = $this->escapeHtml->__invoke($label);
        }

        return $openTag . $label . $this->formLabel->closeTag();
    }
}
