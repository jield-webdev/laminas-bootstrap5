<?php

namespace LaminasBootstrap5\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\View\Helper\AbstractHelper;

use function sprintf;

/**
 * Class FormDescription
 * @package LaminasBootstrap5\Form\View\Helper
 */
final class FormDescription extends AbstractHelper
{
    private string $inlineWrapper = '<small class="form-text">%s</small>';
    private string $blockWrapper = '<small class="form-text">%s</small>';

    public function __invoke(
        ElementInterface $element = null,
        string $blockWrapper = null,
        string $inlineWrapper = null
    ) {
        if ($element) {
            return $this->render($element, $blockWrapper, $inlineWrapper);
        }

        return $this;
    }

    public function render(ElementInterface $element, string $blockWrapper = null, string $inlineWrapper = null): string
    {
        $blockWrapper  = $blockWrapper ?: $this->blockWrapper;
        $inlineWrapper = $inlineWrapper ?: $this->inlineWrapper;

        $html = '';
        if ($inline = $element->getOption('help-inline')) {
            if (null !== ($translator = $this->getTranslator())) {
                $inline = $translator->translate(
                    $inline,
                    $this->getTranslatorTextDomain()
                );
            }

            $html .= sprintf($inlineWrapper, $inline);
        }

        if ($block = $element->getOption('help-block')) {
            if (null !== ($translator = $this->getTranslator())) {
                $block = $translator->translate(
                    $block,
                    $this->getTranslatorTextDomain()
                );
            }

            $html .= sprintf($blockWrapper, $block);
        }

        return $html;
    }
}
