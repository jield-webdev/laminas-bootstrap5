<?php

namespace LaminasBootstrap5\Form\View\Helper;

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
    protected const HEADSCRIPT = 'HeadScript';
    protected const HEADLINK = 'HeadLink';

    protected bool $rendered = false;

    public function __invoke(
        ElementInterface $element = null,
        $type = self::TYPE_HORIZONTAL,
        bool $formElementOnly = false
    ): FilterColumnElement|string|FormElement|static {

        $this->appendScript();
        $this->appendStyle();

        if ($element) {
            return $this->renderFacets($element);
        }

        return $this;
    }

    protected function getContainer(string $containerName)
    {
        return $this->view->plugin($containerName);
    }

    private function appendScript(): void
    {
        $this->getContainer(self::HEADSCRIPT)->appendFile('laminas-bootstrap5/js/simple-load-more.js');
        $this->getContainer(self::HEADSCRIPT)->appendFile('laminas-bootstrap5/js/filter-column.js');
    }

    private function appendStyle(): void
    {
        $this->getContainer(self::HEADLINK)->appendStylesheet('laminas-bootstrap5/css/filter-column.css');
    }

    private function renderFacets(Form $form): string
    {
        if (!$form->has('facet')) {
            return 'no facets found';
        }

        $facets = [];
        /** @var Fieldset $facet */
        foreach ($form->get('facet')->getFieldsets() as $id => $facet) {
            $facets[] = '<div class="simple-load-more">';
            $facets[] = sprintf('<strong>%s</strong>', $facet->get('values')->getLabel());

            if ($facet->has('yesNo')) {
                $facet->get('yesNo')->setAttribute('class', 'form-check-search form-check-yes-no');
                $facet->get('yesNo')->setLabel('Include');
                if ($facet->get('yesNo')->getValue() === 'no') {
                    $facet->get('yesNo')->setLabel('Exclude');
                }
                $facets[] = $this->renderRaw($facet->get('yesNo'));
            }

            if ($facet->has('andOr')) {
                $facet->get('andOr')->setAttribute('class', 'form-check-search form-check-and-or');
                $facet->get('andOr')->setLabel('Or');
                if ($facet->get('andOr')->getValue() === 'and') {
                    $facet->get('andOr')->setLabel('And');
                }
                $facets[] = $this->renderRaw($facet->get('andOr'));
            }

            $facets[] = $this->renderRaw($facet->get('values'));
            $facets[] = '</div>';
        }

        return implode(PHP_EOL, $facets);
    }

    private function renderRaw(ElementInterface $element): ?string
    {
        $type = $element->getAttribute('type');

        switch ($type) {
            case 'multi_checkbox':
                //Based om the type we can choose to render a multi-checkbox as a slider
                if ($element->getOption('type') === FacetField::TYPE_SLIDER) {
                    //Get the helper
                    /** @var FormMultiCheckbox $formMultiCheckbox */
                    $formMultiCheckbox = $this->getView()?->plugin('lbs5formmultislider');

                    return $formMultiCheckbox->render($element);
                }

                //Get the helper
                /** @var FormMultiCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()?->plugin('lbs5formmulticheckbox');
                $formMultiCheckbox->setTemplate(
                    '<div class="form-check form-check-search" data-other="%s">%s%s%s%s</div>'
                );

                //Reset the options so the checked one is always on top
                $sortedValueOptions = [];
                $currentValueOptions = $element->getValueOptions();

                foreach($element->getValue() ?? [] as $value) {
                    $sortedValueOptions[$value] = $currentValueOptions[$value];
                    unset($currentValueOptions[$value]);
                }

                $element->setValueOptions(array_merge($sortedValueOptions, $currentValueOptions));


                return $formMultiCheckbox->render($element);
            case 'checkbox':
                //Get the helper
                /** @var FormCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()?->plugin('lbs5formcheckbox');

                return $formMultiCheckbox->render($element);
            case 'radio':
                //Get the helper
                /** @var FormMultiCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()?->plugin('lbs5formradio');
                $formMultiCheckbox->setTemplate(
                    '<div class="form-check form-check-search %s">%s%s%s%s</div>'
                );

                return $formMultiCheckbox->render($element);
            default:
                return $this->renderHelper($type, $element);
        }
    }
}
