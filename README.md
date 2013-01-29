# ScanKssBundle

This bundle provides a simple integration of the [kss-php](https://github.com/scaninc/kss-php)
library into Symfony2. KSS is a methodology for documenting CSS and generating
styleguides. You can find more information about KSS here at http://warpspire.com/kss/.

## Installation

The easiest way to install this bundle is through composer. In your Symfony2 project
folder, type the following command:

    $ composer require scan/ScanKssBundle

This will install the bundle and all dependencies needed for the bundle to work.

Next, you will need to enable the bundle by adding it to your Kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new Scan\Bundle\KssBundle\ScanKssBundle(),
    );
}
```

Finally, if you'd like try out the example provided in the bundle, you'll need
to add the following to your symfony2 routes.

```yaml
# app/config/routing_dev.yml
scan_kss:
    resource: @ScanKssBundle/Controller/
    type:     annotation
    prefix:   /_kssExample
```

## Basic Usage

To output the dynamically generated styleguides, you'll need to create a
\Scan\Kss\Parser in your controller and pass it the directory containing your
stylesheets.

```php
<?php

namespace Scan\Bundle\KssBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Scan\Bundle\KssBundle\Model\Parser;

class ExampleController extends Controller
{
    /**
     * @Route("/styleguide")
     * @Template
     */
    public function styleguideAction()
    {
        $kss = new Parser($this->getRequest()->server->get('DOCUMENT_ROOT') . '/bundles/scankss/css');
        return array(
            'kss' => $kss,
        );
    }
}
```

Then in your views, when you want to output a styleguide section, use the following
twig include:

```html
{% include 'ScanKssBundle:Blocks:block.html.twig' with
    {
        'section' : kss.getSection('1.1'),
        'html' : '<button class="$modifierClass">Example Button</button>'
    }
%}
```

Finally, you'll need to include a small JavaScript file to help rendering of
pseudo-classes such as :hover, :disabled, etc. in the styleguide. This can be
done with the following lines somewhere in your layout or view:

```html
{% javascripts '@ScanKssBundle/Resources/public/js/*' %}
    <script src="{{ asset_url }}"></script>
{% endjavascripts %}
```

If you would like, you can either create your own styles for the block or use
the styles included. To use the styles included, add the following to your
layout or view:

```html
{% stylesheets 'bundles/scankss/css/*' %}
    <link rel="stylesheet" href="{{ asset_url }}" />
{% endstylesheets %}
```


For a complete example, take a look at the included [example controllers]
(https://github.com/scaninc/ScanKssBundle/blob/master/Controller),
[views](https://github.com/scaninc/ScanKssBundle/blob/master/Resources/views), and
[stylesheets](https://github.com/scaninc/ScanKssBundle/blob/master/Resources/public/css).
