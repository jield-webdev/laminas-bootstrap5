<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\Element\MultiCheckbox;
use Laminas\Form\ElementInterface;
use Search\Form\SearchResult;

/**
 * Class FilterBarElement
 *
 * @package LaminasBootstrap5\Form\View\Helper
 */
class FilterBarElement extends FormElement
{
    public function __invoke(ElementInterface $element = null, bool $inline = false, bool $formElementOnly = false)
    {
        $this->inline          = $inline;
        $this->formElementOnly = $formElementOnly;

        if ($element) {
            return $this->renderFilterBar($element);
        }

        return $this;
    }

    private function renderFilterBar(SearchResult $element)
    {
        $wrapper = '
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand">Filter</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#filterBar"
                    aria-controls="filterBar" aria-expanded="false" aria-label="Toggle Filter">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="collapse navbar-collapse" id="filterBar">
                <ul class="navbar-nav mr-auto">
                     %s                   
                </ul>
                <div class="form-inline">
                        %s
                        %s
                        %s
                </div>
            </div>
        </nav>
        
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
        </script>
        
        <style type="text/css">
            .dropdown-item > label > input {
                margin-right: 0.3rem;  
            }
        </style>
    ';

        return \sprintf(
            $wrapper,
            $this->renderFacets($element),
            $this->renderRaw($element->get('query')),
            $this->renderRaw($element->get('search')),
            $this->renderRaw($element->get('reset'))
        );
    }

    private function renderFacets(SearchResult $element): string
    {
        $facets = [];

        $facetWrapper
            = '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown-%d" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            %s
                        </a>                        
                        <div class="dropdown-menu inactive dropdown-menu-filter-bar" area-labelledby="searchDropdown-%d">
                            %s
                             <div class="dropdown-divider"></div>
                             <div class="dropdown-item">
                             <input type="submit" name="search" class="btn btn-outline-success ml-2 my-2 my-sm-0" value="Search">
                             </div>
                            
                        </div>   
                                             
                    </li>';


        $counter = 1;
        /** @var MultiCheckbox $facet */
        foreach ($element->get('facet') as $facet) {
            $facets[] = \sprintf($facetWrapper, $counter, $facet->getLabel(), $counter, $this->renderRaw($facet));
            $counter++;
        }

        return \implode(PHP_EOL, $facets);
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
            case 'text':
            case 'search':
                return $this->renderHelper('lbs5forminput', $element);
            case 'button':
                $element->setAttribute(
                    'class',
                    'ml-2 my-2 my-sm-0 ' . $element->getAttribute('class')
                );
                $element->setAttribute('id', 'searchButton');
                if ($element->getName() === 'reset') {
                    $element->setAttribute('id', 'resetButton');
                    $element->setAttribute(
                        'class',
                        'ml-2 my-2 my-sm-0 ' . $element->getAttribute('class')
                    );
                }
                return $this->renderHelper('formbutton', $element);
            default:
                return $this->renderHelper($type, $element);
        }
    }
}
