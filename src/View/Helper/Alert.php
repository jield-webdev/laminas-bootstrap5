<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace LaminasBootstrap5\View\Helper;

use Laminas\View\Helper\AbstractHelper;

use function sprintf;
use function trim;

/**
 * Class Alert
 * @package LaminasBootstrap5\View\Helper
 */
class Alert extends AbstractHelper
{
    private string $format = '<div class="alert alert-%s %s" role="alert">%s%s</div>';

    public function info(string $alert, bool $isDismissible = false): string
    {
        return $this->render($alert, 'primary', $isDismissible);
    }

    public function render(string $alert, string $class = '', bool $isDismissible = false): string
    {
        $closeButton      = '';
        $dismissibleClass = '';
        if ($isDismissible) {
            $closeButton
                              = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                   </button>';
            $dismissibleClass = 'alert-dismissible fade show';
        }
        $class = trim($class);

        return sprintf(
            $this->format,
            $class,
            $dismissibleClass,
            $closeButton,
            $alert
        );
    }

    public function danger(string $alert, bool $isDismissible = false): string
    {
        return $this->render($alert, 'danger', $isDismissible);
    }

    public function success(string $alert, bool $isDismissible = false): string
    {
        return $this->render($alert, 'success', $isDismissible);
    }

    public function warning(string $alert, bool $isDismissible = false): string
    {
        return $this->render($alert, 'warning', $isDismissible);
    }

    public function primary(string $alert, bool $isDismissible = false): string
    {
        return $this->render($alert, 'primary', $isDismissible);
    }

    public function secondary(string $alert, bool $isDismissible = false): string
    {
        return $this->render($alert, 'secondary', $isDismissible);
    }

    public function light(string $alert, bool $isDismissible = false): string
    {
        return $this->render($alert, 'light', $isDismissible);
    }

    public function dark(string $alert, bool $isDismissible = false): string
    {
        return $this->render($alert, 'dark', $isDismissible);
    }

    public function __invoke(string $alert = null, string $class = 'info', bool $isDismissible = false)
    {
        if (null !== $alert) {
            return $this->render($alert, $class, $isDismissible);
        }

        return $this;
    }
}
