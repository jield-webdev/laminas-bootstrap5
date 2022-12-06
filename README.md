# laminas-bootstrap5

Repository to use Bootstrap 5 code in Laminas Framework (based on zfc-twitter-bootstrap)

## Installation

Create a writeable directory `assets` in your project public folder (for example ```public/assets```).

```bash

## Forced injection of Bootstrap JS, Jquery and Jquery UI

This module default injects the latest versions of Bootstrap JS (including Popper), Jquery and JqueryUI as these
libraries are required for the JS in this module.
If for some reason you want to use your own versions of these libraries, you can disable the injection of these
libraries by setting the following config in your application config:

```php
return [
    'laminas-bootstrap5'         => [
        'inject_jquery'    => false,
        'inject_jquery_ui' => false,
        'inject_bootstrap_js' => false,
    ],
];
```
