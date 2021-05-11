<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace LaminasBootstrap5\View\Helper\Navigation;

use RecursiveIteratorIterator;

use Laminas\View\Helper\Navigation\Menu as LaminasMenu;
use Laminas\Navigation\Navigation;
use Laminas\Navigation\AbstractContainer;
use Laminas\Navigation\Page\AbstractPage;
use Laminas\View;
use Laminas\View\Exception;

/**
 * Helper for rendering menus from navigation containers
 */
class SubMenu extends LaminasMenu
{
    /**
     * CSS class to use for the ul element
     *
     * @var string
     */
    protected $ulClass = 'nav';


    /**
     * Renders a normal menu (called from {@link renderMenu()})
     *
     * @param  AbstractContainer $container          container to render
     * @param  string            $ulClass            CSS class for first UL
     * @param  string            $indent             initial indentation
     * @param  int|null          $minDepth           minimum depth
     * @param  int|null          $maxDepth           maximum depth
     * @param  bool              $onlyActive         render only active branch?
     * @param  bool              $escapeLabels       Whether or not to escape the labels
     * @param  bool              $addClassToListItem Whether or not page class applied to <li> element
     * @param  string            $liActiveClass      Active class of LI element
     *
     * @return string
     */
    protected function renderNormalMenu(
        AbstractContainer $container,
        $ulClass,
        $indent,
        $minDepth,
        $maxDepth,
        $onlyActive,
        $escapeLabels,
        $addClassToListItem,
        $liActiveClass
    ) {
        $html = '<ul class="nav-sub-menu ' . $ulClass . '">';

        // find deepest active
        $found = $this->findActive($container, $minDepth, $maxDepth);

        if ($found) {
            $foundPage = $found['page'];
        } else {
            return '';
        }


        // create iterator
        $iterator = new RecursiveIteratorIterator(
            $container,
            RecursiveIteratorIterator::SELF_FIRST
        );

        // iterate container
        $prevDepth = -1;
        foreach ($iterator as $page) {
            $depth = $iterator->getDepth();

            if ($depth === 0) {
                continue;
            }

            $isActive = $page->isActive(true);

            if ($foundPage) {
                // page is not active itself, but might be in the active branch

                $accept = false;
                if ($foundPage->hasPage($page) ||
                    $foundPage->getParent()->hasPage($page) ||
                    (
                        !$foundPage->getParent() instanceof Navigation &&
                        $foundPage->getParent()->getParent()->hasPage($page)
                    )

                ) {
                    // accept if page is a direct child of the active page
                    $accept = true;
                }


                if (!$accept) {
                    continue;
                }
            }


            // make sure indentation is correct
            $depth -= $minDepth;
            $myIndent = $indent . str_repeat('        ', $depth);

            if ($depth === 1 && $page->hasChildren()) {
                // start new ul tag
                $page->isDropdown = true;
                $html .= $myIndent . '' . PHP_EOL;
            } elseif ($prevDepth > $depth) {
                $html .= $myIndent . '    </li>' . PHP_EOL;
            } else {
                $html .= $myIndent . '   </li>' . PHP_EOL;
            }

            // render li tag and page
            $liClasses = ['nav-item'];
            // Is page active?
            if ($isActive) {
                $liClasses[] = 'active';
            }

            if ($depth === 2) {
                $liClasses[] = 'sub';
            }


            // Add CSS class from page to <li>
            if ($addClassToListItem && $page->getClass()) {
                $liClasses[] = $page->getClass();
            }

            $liClass = empty($liClasses) ? '' : ' class="' . implode(' ', $liClasses) . '"';

            $html .= $myIndent . '    <li' . $liClass . '>' . PHP_EOL
                . $myIndent . '        ' . $this->htmlify($page, $escapeLabels, $addClassToListItem) . PHP_EOL;

            // store as previous depth for next iteration
            $prevDepth = $depth;
        }

        return $html;
    }

    /**
     * Returns an HTML string containing an 'a' element for the given page if
     * the page's href is not empty, and a 'span' element if it is empty.
     *
     * Overrides {@link AbstractHelper::htmlify()}.
     *
     * @param  AbstractPage $page               page to generate HTML for
     * @param  bool         $escapeLabel        Whether or not to escape the label
     * @param  bool         $isChild Whether or not to add the page class to the list item
     * @return string
     */
    public function htmlify(AbstractPage $page, $escapeLabel = true, $isChild = false): string
    {
        // get attribs for element
        $attribs = [
            'id'     => $page->getId(),
            'title'  => $this->translate($page->getTitle(), $page->getTextDomain()),
        ];

        $class = [];
        $class[] = $page->getClass();
        $class[] = 'flex-sm-fill text-sm-center nav-link';

        if ($page->isActive()) {
            $class[] = 'active';
        }

        // does page have a href?
        $href = $page->getHref();
        if ($href) {
            $element           = 'a';
            $attribs['href']   = $href;
            $attribs['target'] = $page->getTarget();
        } else {
            $element = 'span';
        }

        if (count($class) > 0) {
            $attribs['class'] = implode(' ', $class);
        }

        $html  = '<' . $element . $this->htmlAttribs($attribs) . '>';
        $label = $this->translate($page->getLabel(), $page->getTextDomain());

        if ($escapeLabel === true) {
            /** @var \Laminas\View\Helper\EscapeHtml $escaper */
            $escaper = $this->view->plugin('escapeHtml');
            $html .= $escaper($label);
        } else {
            $html .= $label;
        }

        $html .= '</' . $element . '>';
        return $html;
    }
}
