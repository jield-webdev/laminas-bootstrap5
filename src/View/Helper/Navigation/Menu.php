<?php

namespace LaminasBootstrap5\View\Helper\Navigation;

use Laminas\Navigation\AbstractContainer;
use Laminas\Navigation\Page\AbstractPage;
use Laminas\Navigation\Page\Mvc;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\Helper\Navigation\Menu as LaminasMenu;
use RecursiveIteratorIterator;

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
    ) {
        $html = '';

        // find deepest active
        $found = $this->findActive($container, $minDepth, $maxDepth);

        $escaper = $this->view->plugin('escapeHtmlAttr');
        assert($escaper instanceof EscapeHtmlAttr);

        $foundPage  = null;
        $foundDepth = 0;

        if ($found) {
            $foundPage  = $found['page'];
            $foundDepth = $found['depth'];
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
        foreach ($iterator as $key => $page) {
            $depth    = $iterator->getDepth();
            $isActive = $page->isActive(true);

            if ($depth === 0) {
                $page->setClass(trim('nav-link ' . $page->getClass()));
            } elseif ($depth > 0) {
                $page->setClass(trim('dropdown-item ' . $page->getClass()));
            }

            if ($page->getParent()?->hasChildren() && $depth === 0) {
                $page->setClass(trim('dropdown-toggle ' . $page->getClass()));
                $page->setId($key);
                $page->setOptions(['role' => 'button', 'data-bs-toggle' => 'dropdown', 'aria-expanded' => false]);
            }

            if ($depth < $minDepth || !$this->accept($page)) {
                // page is below minDepth or not accepted by acl/visibility
                continue;
            } elseif ($onlyActive && !$isActive) {
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
            $myIndent = $indent . str_repeat('        ', $depth);
            if ($depth > $prevDepth) {
                // start new ul tag
                if ($ulClass && $depth === 0) {
                    $ulClass = ' class="' . $escaper($ulClass) . '"';
                } else {
                    $ulClass = ' class="dropdown-menu" aria-labelledby="' . $key . '"';
                }
                $html .= $myIndent . '<ul' . $ulClass . '>' . PHP_EOL;
            } elseif ($prevDepth > $depth) {
                // close li/ul tags until we're at current depth
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $ind  = $indent . str_repeat('        ', $i);
                    $html .= $ind . '    </li>' . PHP_EOL;
                    $html .= $ind . '</ul>' . PHP_EOL;
                }
                // close previous li tag
                $html .= $myIndent . '    </li>' . PHP_EOL;
            } else {
                // close previous li tag
                $html .= $myIndent . '    </li>' . PHP_EOL;
            }

            $liClasses = ['nav-item'];


            if ($depth === 0 && $page->getParent()->hasChildren()) {
                $liClasses[] = 'dropdown';
            }

            // Is page active?
            if ($isActive) {
                $liClasses[] = $liActiveClass;
            }

            // Add CSS class from page to <li>
            if ($addClassToListItem && $page->getClass()) {
                $liClasses[] = $page->getClass();
            }
            $liClass = empty($liClasses) ? '' : ' class="' . $escaper(implode(' ', $liClasses)) . '"';
            $html    .= $myIndent . '    <li' . $liClass . '>' . PHP_EOL
                . $myIndent . '        ' . $this->htmlify($page, $escapeLabels, $addClassToListItem) . PHP_EOL;

            // store as previous depth for next iteration
            $prevDepth = $depth;
        }

        if ($html) {
            // done iterating container; close open ul/li tags
            for ($i = $prevDepth + 1; $i > 0; $i--) {
                $myIndent = $indent . str_repeat('        ', $i - 1);
                $html     .= $myIndent . '    </li>' . PHP_EOL
                    . $myIndent . '</ul>' . PHP_EOL;
            }
            $html = rtrim($html, PHP_EOL);
        }

        return $html;
    }

    public function htmlify(AbstractPage $page, $escapeLabel = true, $addClassToListItem = false)
    {
        // get attribs for element
        $attribs = [
            'id'    => $page->getId(),
            'title' => $this->translate($page->getTitle(), $page->getTextDomain()),
        ];

        if (null !== $page->get('role')) {
            $attribs['role'] = $page->get('role');
        }
        if (null !== $page->get('data-bs-toggle')) {
            $attribs['data-bs-toggle'] = $page->get('data-bs-toggle');
        }
        if (null !== $page->get('area-expanded')) {
            $attribs['area-expanded'] = $page->get('area-expanded');
        }

        if ($addClassToListItem === false) {
            $attribs['class'] = $page->getClass();
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
