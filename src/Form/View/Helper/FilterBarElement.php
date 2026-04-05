<?php

namespace LaminasBootstrap5\Form\View\Helper;

use Jield\Search\Enum\FacetFieldVisibilityEnum;
use Jield\Search\ValueObject\FacetField;
use Laminas\Form\Element\MultiCheckbox;
use Laminas\Form\ElementInterface;
use Laminas\Form\Fieldset;
use Laminas\Form\Form;
use function implode;
use function sprintf;

class FilterBarElement extends FormElement
{
    public function __invoke(?ElementInterface $element = null, $type = false, bool $formElementOnly = false)
    {
        if ($element instanceof Form) {
            return $this->renderFilterBar($element);
        }

        return $this;
    }

    private function renderFilterBar(Form $element): string
    {
        $wrapper = '
        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#filterBar"
                        aria-controls="filterBar" aria-expanded="false" aria-label="Toggle Filter">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="filterBar">
                    <div class="filter-bar-row">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 filter-bar-facets">
                             %s
                             %s                   
                        </ul>
                        <div class="input-group filter-bar-search">
                                %s
                                %s
                                %s
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <style type="text/css">
            .filter-bar-row { display: flex; align-items: center; gap: 0.75rem; flex-wrap: nowrap; width: 100%%; min-width: 0; }
            .filter-bar-facets { flex: 1 1 auto; flex-wrap: nowrap; overflow: visible; min-width: 0; }
            .filter-bar-facet-item { white-space: nowrap; }
            .filter-bar-search { flex: 0 1 auto; flex-wrap: nowrap; width: auto; max-width: 100%%; overflow: hidden; }
            .filter-bar-search > .btn { flex: 0 0 auto; white-space: nowrap; }
            .filter-bar-search > .form-control { flex: 0 1 auto; min-width: 0; }
            .filter-bar-query { min-width: 6ch; }
        </style>
        
        <script type="text/javascript">
            $(\'.dropdown-menu-filter-bar\').on(\'click\', function(e) {
                e.stopPropagation();
            });
        
            $(function () {
                $(\'#searchButton\').on(\'click\', function () {
                    $(\'#search\').submit();
                });
                $(\'#resetButton\').on(\'click\', function () {
                    $(\'input[type="checkbox"]\').each(function () {
                        this.removeAttribute(\'checked\');
                    });
                    $(\'input[name="query"]\').val(\'\');
                    $(\'#search\').submit();
                });
            });

            $(function () {
                var $row = $(\'#filterBar .filter-bar-row\');
                var $input = $(\'.filter-bar-query\');
                var $inputGroup = $(\'.filter-bar-search\');
                var $nav = $(\'.filter-bar-facets\');
                var $items = $nav.find(\'.filter-bar-facet-item\');
                var canvas = document.createElement(\'canvas\');
                var context = canvas.getContext(\'2d\');

                if (!$row.length || !$input.length || !$nav.length) {
                    return;
                }

                function measureTextWidth(text, font) {
                    if (!context) {
                        return text.length * 8;
                    }
                    context.font = font;
                    return context.measureText(text).width;
                }

                function inputTargetWidth() {
                    var inputEl = $input.get(0);
                    var style = window.getComputedStyle(inputEl);
                    var value = $input.val() || $input.attr(\'placeholder\') || \' \';
                    var font = style.font || (style.fontStyle + \' \' + style.fontVariant + \' \' + style.fontWeight + \' \' + style.fontSize + \'/\' + style.lineHeight + \' \' + style.fontFamily);
                    var padding = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight) + 2;
                    var maxWidth = measureTextWidth(\'MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM\', font) + padding;
                    var minWidth = measureTextWidth(\'MMMMMM\', font) + padding;
                    var target = measureTextWidth(value, font) + padding;
                    return Math.max(minWidth, Math.min(target, maxWidth));
                }

                function fitFilterBar() {
                    $items.removeClass(\'d-none\');
                    var rowWidth = $row.width();
                    if (!rowWidth) {
                        return;
                    }

                    var buttonsWidth = 0;
                    $inputGroup.children().not($input).each(function () {
                        buttonsWidth += $(this).outerWidth(true);
                    });

                    var maxInputWidth = Math.max(80, rowWidth - buttonsWidth - 8);
                    var targetWidth = Math.min(inputTargetWidth(), maxInputWidth);
                    $input.css(\'width\', targetWidth + \'px\');

                    $inputGroup.css(\'max-width\', rowWidth + \'px\');
                    var available = rowWidth - $inputGroup.outerWidth(true) - 8;
                    if (available <= 0) {
                        return;
                    }

                    while ($nav.get(0).scrollWidth > available && $items.filter(\':not(.d-none)\').length) {
                        $items.filter(\':not(.d-none)\').last().addClass(\'d-none\');
                    }

                    var $hidden = $items.filter(\'.d-none\');
                    for (var i = 0; i < $hidden.length; i += 1) {
                        var $candidate = $hidden.eq(i).removeClass(\'d-none\');
                        if ($nav.get(0).scrollWidth > available) {
                            $candidate.addClass(\'d-none\');
                            break;
                        }
                    }
                }

                $input.on(\'input\', fitFilterBar);
                $(window).on(\'resize\', fitFilterBar);
                setTimeout(fitFilterBar, 0);
            });
        </script>
        

    ';

        return sprintf(
            $wrapper,
            $element->has('filter') && $element->get('filter')->has('general') ? $this->renderGeneralFilter($element->get('filter')->get('general')) : '',
            $this->renderFacets($element),
            $this->renderRaw($element->get('query')),
            $this->renderRaw($element->get('search')),
            $this->renderRaw($element->get('reset'))
        );
    }

    private function renderFacets(Form $form): string
    {
        $facets = [];

        $facetWrapper
            = '<li class="nav-item dropdown filter-bar-facet-item">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown-%d" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            %s
                        </a>                        
                        <div class="dropdown-menu inactive dropdown-menu-filter-bar" aria-labelledby="searchDropdown-%d">
                            %s
                            %s
                            %s
                        </div>
                    </li>';


        /** @var Fieldset $facet */
        foreach ($form->get('facet')->getFieldsets() as $counter => $facet) {

            if ($facet->getOption('visibility') === FacetFieldVisibilityEnum::FILTER_COLUMN) {
                continue;
            }

            $yesNo = '';
            if ($facet->has('yesNo')) {
                $facet->get('yesNo')->setAttribute('class', 'form-check-search form-check-yes-no');
                $facet->get('yesNo')->setLabel('Include');
                if ($facet->get('yesNo')->getValue() === 'no') {
                    $facet->get('yesNo')->setLabel('Exclude');
                }
                $yesNo = '<div class="dropdown-item"><div class="form-check">' . $this->renderRaw(
                        $facet->get('yesNo')
                    ) . '</div></div>';
            }

            $andOr = '';
            if ($facet->has('andOr')) {
                $facet->get('andOr')->setAttribute('class', 'form-check-search form-check-and-or');
                $facet->get('andOr')->setLabel('Or');
                if ($facet->get('andOr')->getValue() === 'and') {
                    $facet->get('andOr')->setLabel('And');
                }
                $andOr = '<div class="dropdown-item"><div class="form-check">' . $this->renderRaw(
                        $facet->get('andOr')
                    ) . '</div></div>';
            }

            $facets[] = sprintf(
                $facetWrapper,
                $counter,
                $facet->get('values')->getLabel(),
                $counter,
                $yesNo,
                $andOr,
                $this->renderRaw($facet->get('values'))
            );
        }

        return implode(PHP_EOL, $facets);
    }

    private function renderGeneralFilter(MultiCheckbox $generalFilter): string
    {
        if ($generalFilter->getOption('visibility') !== FacetFieldVisibilityEnum::FILTER_BAR) {
            return '';
        }

        $facetWrapper
            = '<li class="nav-item dropdown filter-bar-facet-item">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown-general-filter" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            %s
                        </a>                        
                        <div class="dropdown-menu inactive dropdown-menu-filter-bar" aria-labelledby="searchDropdown-general-filter">
                            %s
                        </div>
                    </li>';

        return sprintf(
            $facetWrapper,
            ucfirst($generalFilter->getLabel()),
            $this->renderRaw($generalFilter)
        );
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
                $formMultiCheckbox = $this->getView()->plugin('lbs5formmulticheckbox');
                $formMultiCheckbox->setTemplate(
                    '<div class="dropdown-item"><div class="form-check %s">%s%s%s%s</div></div>'
                );

                return $formMultiCheckbox->render($element);
            case 'radio':
                //Get the helper
                /** @var FormMultiCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()->plugin('lbs5formradio');
                $formMultiCheckbox->setTemplate(
                    '<div class="dropdown-item"><div class="form-check %s">%s%s%s%s</div></div>'
                );

                return $formMultiCheckbox->render($element);
            case 'select':
                //Get the helper
                /** @var FormMultiCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()->plugin('lbs5formselect');

                return $formMultiCheckbox->render($element);
            case 'checkbox':
                //Get the helper
                /** @var FormCheckbox $formMultiCheckbox */
                $formMultiCheckbox = $this->getView()?->plugin('lbs5formcheckbox');

                return $formMultiCheckbox->render($element);
            case 'text':
            case 'search':
                if ($element->getName() === 'query') {
                    $element->setAttribute(
                        'class',
                        'filter-bar-query ' . $element->getAttribute('class')
                    );
                }
                $element->setAttribute(
                    'class',
                    'form-control ' . $element->getAttribute('class')
                );
                return $this->renderHelper('lbs5forminput', $element);
            case 'button':
                $element->setAttribute('id', 'searchButton');
                if ($element->getName() === 'reset') {
                    $element->setAttribute('id', 'resetButton');
                }
                return $this->renderHelper('formbutton', $element);
            case 'submit':
                return $this->renderHelper('formsubmit', $element);
            default:
                return $this->renderHelper($type, $element);
        }
    }
}
