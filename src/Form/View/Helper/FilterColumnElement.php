<?php

namespace LaminasBootstrap5\Form\View\Helper;

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
    public function __invoke(
        ElementInterface $element = null,
        $type = self::TYPE_HORIZONTAL,
        bool $formElementOnly = false
    ): FilterColumnElement|string|FormElement|static {
        if ($element) {
            return $this->renderFilterBar($element);
        }

        return $this;
    }

    private function renderFilterBar(Form|ElementInterface $form): string
    {
        $wrapper = '%s                       
        
        <script type="text/javascript">
            $(\'.form-check-search > input[type="checkbox"]\').on(\'click\', function() {
                $(\'#search\').submit();
            });
            $(\'.form-check-search\').on(\'click\', function() {
                $(\'#search\').submit(); //yesno/andor
            });

            $(function () {
                $(\'#searchButton\').on(\'click\', function () {
                    $(\'#search\').submit();
                });
                $(\'#resetButton\').on(\'click\', function () {
                    $(\'.form-check-search > input[type="checkbox"]\').each(function () {
                        this.removeAttribute(\'checked\');
                    });
                    $(".form-check-search").each(function () {
                        this.removeAttribute("checked");
                    });                    
                    $(\'.form-check-search > input[type="radio"]\').each(function () {
                        this.removeAttribute(\'checked\');
                    });
                    $(\'input[name="query"]\').val(\'\');
                    $(\'#search\').submit();
                });
            });
        </script>';

        return sprintf(
            $wrapper,
            $this->renderFacets($form),
        );
    }

    private function renderFacets(Form $form): string
    {
        if (!$form->has('facet')) {
            return 'no facets found';
        }

        $facets = [];

        /** @var Fieldset $facet */
        foreach ($form->get('facet')->getFieldsets() as $facet) {
            $facets[] = sprintf('<strong>%s</strong>', $facet->get('values')->getLabel());

            if ($facet->has('yesNo')) {
                $facet->get('yesNo')->setAttribute('class', 'form-check-search form-check-yes-no');
                $facet->get('yesNo')->setLabel('Yes');
                if ($facet->get('yesNo')->getValue() === 'no') {
                    $facet->get('yesNo')->setLabel('No');
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
        }

        return implode(PHP_EOL, $facets);
    }

    private function renderRaw(ElementInterface $element): ?string
    {
        $type = $element->getAttribute('type');

        switch ($type) {
            case 'multi_checkbox':
                //Get the helper
                /** @var FormMultiCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()?->plugin('lbs5formmulticheckbox');
                $formMultiCheckbox->setTemplate(
                    '<div class="form-check form-check-search" data-other="%s">%s%s%s%s</div>'
                );

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
