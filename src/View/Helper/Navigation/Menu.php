<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace LaminasBootstrap5\View\Helper\Navigation;

use Laminas\Navigation\AbstractContainer;
use Laminas\Navigation\Page\AbstractPage;
use Laminas\Navigation\Page\Mvc;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\Helper\Navigation\Menu as LaminasMenu;
use RecursiveIteratorIterator;

use function count;
use function str_repeat;

/**
 * Helper for rendering menus from navigation containers
 */
class Menu extends LaminasMenu
{
    /**
     * CSS class to use for the ul element
     */
    protected $ulClass = 'nav';

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
    ): string
    {
        $html = '';

        // find deepest active
        $found = $this->findActive($container, $minDepth, $maxDepth);

        /* @var $escaper EscapeHtmlAttr */
        $escaper = $this->view->plugin('escapeHtmlAttr');

        if ($found) {
            /** @var Mvc $foundPage */
            $foundPage  = $found['page'];
            $foundDepth = $found['depth'];
        } else {
            $foundPage  = null;
            $foundDepth = null;
        }

        // create iterator
        $iterator = new RecursiveIteratorIterator(
            $container,
            RecursiveIteratorIterator::SELF_FIRST
        );

        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }

        // iterate container
        $prevDepth = -1;
        /** @var Mvc $page */
        foreach ($iterator as $page) {
            $depth    = $iterator->getDepth();
            $isActive = $page->isActive(true);
            if ($depth < $minDepth || !$this->accept($page)) {
                // page is below minDepth or not accepted by acl/visibility
                continue;
            }

            if ($onlyActive && !$isActive) {
                // page is not active itself, but might be in the active branch
                $accept = false;
                if ($foundPage) {
                    if ($foundPage->hasPage($page)) {
                        // accept if page is a direct child of the active page
                        $accept = true;
                    } elseif ($foundPage->getParent()->hasPage($page)) {
                        // page is a sibling of the active page...
                        if (!$foundPage->hasPages(!$this->renderInvisible)
                            || (is_int($maxDepth) && $foundDepth + 1 > $maxDepth)
                        ) {
                            // accept if active page has no children, or the
                            // children are too deep to be rendered
                            $accept = true;
                        }
                    }
                }
                if (!$accept) {
                    continue;
                }
            }

            // make sure indentation is correct
            $depth    -= $minDepth;
            $myIndent = $indent . str_repeat('    ', $depth + 1);
            if ($depth > $prevDepth) {
                // start new ul tag
                if ($ulClass && $depth == 0) {
                    $ulClass = ' class="' . $escaper($ulClass) . '"';
                } else {
                    $ulClass = '';
                }

                if ($depth === 0) {
                    $html .= $myIndent . '<ul' . $ulClass . '>' . PHP_EOL;
                } else {
                    $html .= $myIndent . '<div class="dropdown-menu" area-labelled-by="menu-' . md5($page->getTitle())
                        . '">' . PHP_EOL;
                }
            }

            // render li tag and page
            $liClasses = ['nav-item'];

            // Is page active?
            if ($isActive) {
                $liClasses[] = $liActiveClass;
            }

            if ($page->hasPages() && $maxDepth !== 0) {
                $liClasses[] = 'dropdown';
            }

            // Add CSS class from page to <li>
            if ($addClassToListItem && $page->getClass()) {
                $liClasses[] = $page->getClass();
            }
            $liClass = empty($liClasses) ? '' : ' class="' . $escaper(implode(' ', $liClasses)) . '"';

            if ($depth < $prevDepth) {
                $html .= '       ' . $myIndent . '</div>' . PHP_EOL;

                if ($depth === 0) {
                    $html .= '    ' . '</li>' . PHP_EOL;
                }
            }

            $html .= $myIndent
                . ($depth === 0 ? '    <li' . $liClass . '>' : '')
                . $myIndent . $this->htmlify($page, $escapeLabels, $depth > 0, $maxDepth !== 0)
                . PHP_EOL;


            // store as previous depth for next iteration
            $prevDepth = $depth;
        }

        if ($html) {
            // done iterating container; close open ul/li tags
            for ($i = $prevDepth + 1; $i > 0; $i--) {
                $myIndent = $indent . str_repeat('        ', $i - 1);
                $html     .= $myIndent . '    </li>' . PHP_EOL . $myIndent . '</ul>' . PHP_EOL;
            }
            $html = rtrim($html, PHP_EOL);
        }

        return $html;
    }

    public function htmlify(AbstractPage $page, $escapeLabel = true, $isChild = false, $showDropdown = true): string
    {
        // get attribs for element
        $attribs = [
            'id'    => $page->getId(),
            'title' => $this->translate($page->getTitle(), $page->getTextDomain()),
        ];

        $class[] = $page->getClass();

        if (!$isChild && $page->hasPages(true)) {
            $attribs['data-bs-toggle']   = 'dropdown';
            $attribs['aria-haspopup'] = 'true';
            $attribs['aria-expanded'] = 'false';
            $attribs['role']          = 'button';
            $attribs['id']            = md5($page->getId() . $page->getTitle());
            $class[]                  = 'dropdown-toggle px-2';
        }

        if ($isChild) {
            $class[] = 'dropdown-item';
        } else {
            $class[] = 'nav-link link-dark';
        }

        // does page have a href?
        $href = $page->getHref();

        if ($href) {
            $element = 'a';
            if (!$isChild && $page->hasPages(true)) {
                $href = '#';
            }
            $attribs['href']   = $href;
            $attribs['target'] = $page->getTarget();
        } else {
            $element = 'span';
        }

        if (count($class) > 0) {
            $attribs['class'] = trim(implode(' ', $class));
        }

        $html  = '<' . $element . $this->htmlAttribs($attribs) . '>';
        $label = $this->translate($page->getLabel(), $page->getTextDomain());

        if ($escapeLabel === true) {
            /** @var EscapeHtml $escaper */
            $escaper = $this->view->plugin('escapeHtml');
            $html    .= $escaper($label);
        } else {
            $html .= $label;
        }

        $html .= '</' . $element . '>';

        return $html;
    }
}
