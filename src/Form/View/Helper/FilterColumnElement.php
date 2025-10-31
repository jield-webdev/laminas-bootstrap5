<?php

namespace LaminasBootstrap5\Form\View\Helper;

use Jield\Search\Enum\FacetFieldVisibilityEnum;
use Jield\Search\ValueObject\FacetField;
use Laminas\Form\ElementInterface;
use Laminas\Form\Fieldset;
use Laminas\Form\Form;
use function implode;
use function sprintf;

/**
 * Class FilterColumnElement
 * @package LaminasBootstrap5\Form\View\Helper
 */
class FilterColumnElement extends FormElement
{
    protected const string HEADSCRIPT = 'HeadScript';
    protected const string HEADLINK = 'HeadLink';

    protected bool $rendered = false;

    public function __invoke(
        ?ElementInterface $element = null,
                          $type = self::TYPE_HORIZONTAL,
        bool              $formElementOnly = false
    ): FilterColumnElement|string|FormElement|static
    {
        $this->appendScript();

        if ($element instanceof Form) {
            return $this->renderFacets(form: $element);
        }

        return $this;
    }

    protected function getContainer(string $containerName)
    {
        return $this->view->plugin(name: $containerName);
    }

    private function appendScript(): void
    {
        $this->getContainer(containerName: self::HEADSCRIPT)->appendFile(
            'assets/js/laminas-bootstrap5/filter-column.js',
            'text/javascript'
        );
        $this->getContainer(containerName: self::HEADLINK)->appendStylesheet('assets/css/laminas-bootstrap5/filter-column.css');
    }

    private function renderFacets(Form $form): string
    {
        if (!$form->has(elementOrFieldset: 'facet')) {
            return 'no facets found';
        }

        $facets   = [];
        $facets[] = $this->renderGeneralFilter(form: $form);

        /** @var Fieldset $facet */
        foreach ($form->get(elementOrFieldset: 'facet')->getFieldsets() as $id => $facet) {

            if ($facet->getOption(option: 'visibility') !== FacetFieldVisibilityEnum::FILTER_COLUMN) {
                continue;
            }

            $facets[] = '<div class="mb-3 simple-load-more">';
            $facets[] = sprintf('<strong>%s</strong>', $facet->get(elementOrFieldset: 'values')->getLabel());

            if ($facet->has(elementOrFieldset: 'yesNo')) {
                $facet->get(elementOrFieldset: 'yesNo')->setAttribute(key: 'class', value: 'form-check-search form-check-yes-no');
                $facet->get(elementOrFieldset: 'yesNo')->setLabel(label: 'Include');
                if ($facet->get(elementOrFieldset: 'yesNo')->getValue() === 'no') {
                    $facet->get(elementOrFieldset: 'yesNo')->setLabel(label: 'Exclude');
                }
                $facets[] = $this->renderRaw(element: $facet->get(elementOrFieldset: 'yesNo'));
            }

            if ($facet->has(elementOrFieldset: 'andOr')) {
                $facet->get(elementOrFieldset: 'andOr')->setAttribute(key: 'class', value: 'form-check-search form-check-and-or');
                $facet->get(elementOrFieldset: 'andOr')->setLabel(label: 'Or');
                if ($facet->get(elementOrFieldset: 'andOr')->getValue() === 'and') {
                    $facet->get(elementOrFieldset: 'andOr')->setLabel(label: 'And');
                }
                $facets[] = $this->renderRaw(element: $facet->get(elementOrFieldset: 'andOr'));
            }

            $facets[] = $this->renderRaw(element: $facet->get(elementOrFieldset: 'values'));
            $facets[] = '</div>';
        }

        return implode(separator: PHP_EOL, array: $facets);
    }

    private function renderGeneralFilter(Form $form): string
    {
        $generalFilter = [];

        if ($form->has(elementOrFieldset: 'filter') && $form->get(elementOrFieldset: 'filter')->has(elementOrFieldset: 'general')) {

            $element = $form->get(elementOrFieldset: 'filter')->get(elementOrFieldset: 'general');

            $generalFilter[] = '<div class="mb-3">';
            $generalFilter[] = sprintf('<strong>%s</strong>', ucfirst(string: $element->getLabel()));

            //Get the helper
            /** @var FormMultiCheckbox $formMultiCheckbox */
            $formMultiCheckbox = $this->getView()?->plugin(name: 'lbs5formmulticheckbox');
            $formMultiCheckbox->setTemplate(
                template: '<div class="form-check form-check-search" data-other="%s">%s%s%s%s</div>'
            );

            $generalFilter[] = $formMultiCheckbox->render(element: $element);

            $generalFilter[] = '</div>';
        }

        return implode(separator: PHP_EOL, array: $generalFilter);
    }


    private function renderRaw(ElementInterface $element): ?string
    {
        $type = $element->getAttribute(key: 'type');

        switch ($type) {
            case 'multi_checkbox':
                //Based om the type we can choose to render a multi-checkbox as a slider
                if ($element->getOption(option: 'type') === FacetField::TYPE_SLIDER) {
                    //Get the helper
                    /** @var FormMultiCheckbox $formMultiCheckbox */
                    $formMultiCheckbox = $this->getView()?->plugin(name: 'lbs5formmultislider');

                    return $formMultiCheckbox->render(element: $element);
                }

                //Get the helper
                /** @var FormMultiCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()?->plugin(name: 'lbs5formmulticheckbox');
                $formMultiCheckbox->setTemplate(
                    template: '<div class="form-check form-check-search" data-other="%s">%s%s%s%s</div>'
                );

                //Reset the options so the checked one is always on top
                $sortedValueOptions  = [];
                $currentValueOptions = $element->getValueOptions();

                foreach ((array)($element->getValue() ?? []) as $value) {
                    //We can only unset the value if it is in the value options
                    if (array_key_exists(key: $value, array: $currentValueOptions)) {
                        $sortedValueOptions[$value] = $currentValueOptions[$value];
                        unset($currentValueOptions[$value]);
                    }
                }

                $element->setValueOptions(options: $sortedValueOptions + $currentValueOptions);

                return $formMultiCheckbox->render(element: $element);
            case 'checkbox':
                //Get the helper
                /** @var FormCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()?->plugin(name: 'lbs5formcheckbox');

                return $formMultiCheckbox->render(element: $element);
            case 'radio':
                //Get the helper
                /** @var FormMultiCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()?->plugin(name: 'lbs5formradio');
                $formMultiCheckbox->setTemplate(
                    template: '<div class="form-check form-check-search %s">%s%s%s%s</div>'
                );

                return $formMultiCheckbox->render(element: $element);
            default:
                return $this->renderHelper(name: $type, element: $element);
        }
    }
}
