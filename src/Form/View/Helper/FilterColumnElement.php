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
    ) {
        if ($element) {
            return $this->renderFilterBar($element);
        }

        return $this;
    }

    private function renderFilterBar(Form $element)
    {
        $wrapper = '%s                       
        
        <script type="text/javascript">
            $(\'.form-check-search > input[type="checkbox"]\').on(\'click\', function(e) {
                $(\'#search\').submit();
            });
        
            $(function () {
                $(\'#searchButton\').on(\'click\', function () {
                    $(\'#search\').submit();
                });
                $(\'#resetButton\').on(\'click\', function () {
                    $(\'.form-check-search > input[type="checkbox"]\').each(function () {
                        this.removeAttribute(\'checked\');
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
            $this->renderFacets($element),
        );
    }

    private function renderFacets(Form $element): string
    {
        $facets = [];

        $facetWrapper = ' <strong>%s</strong> %s';

        /** @var Fieldset $facet */
        foreach ($element->get('facet') as $facet) {
            $facets[] = sprintf($facetWrapper, $facet->getLabel(), $this->renderRaw($facet));
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
                $formMultiCheckbox = $this->getView()->plugin('lbs5formmulticheckbox');
                $formMultiCheckbox->setTemplate(
                    '<div class="form-check form-check-search" data-other="%s">%s%s%s%s</div>'
                );

                return $formMultiCheckbox->render($element);
            case 'radio':
                //Get the helper
                /** @var FormMultiCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()->plugin('lbs5formradio');
                $formMultiCheckbox->setTemplate(
                    '<div class="form-check form-check-search %s">%s%s%s%s</div>'
                );

                return $formMultiCheckbox->render($element);
            default:
                return $this->renderHelper($type, $element);
        }
    }
}
