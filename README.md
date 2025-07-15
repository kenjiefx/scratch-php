# Scratch PHP
A simple, lightweight, and endlessly extendable static site generator, providing easy and maintainable way to build static websites, leveraging the power and flexibility of PHP’s templating engine.

***No Parsers, No Hassle*** — ScratchPHP takes advantage of PHP itself being a templating engine. This library relies on simple APIs which are PHP functions themselves, reducing dependencies and eliminating unnecessary overhead. 
This approach keeps ScratchPHP lightweight while still providing the flexibility and power needed to build dynamic static content efficiently.

## Getting Started
The simplest way to get started with Scratch PHP is to create a project using Scratch Skeleton by running this command in `Composer`: 

```bash
composer create-project kenjiefx/scratch-skeleton <app name>
```

Everything you need to get started is included in the skeleton repository. It comes with a built-in development server, allowing you to preview your site instantly—no need to manually run the `build` command during development. Run the command below to start the development server.

```bash
php -S 127.0.0.1:7743 server.php
```

To build and export your static site, run the command below: 

```bash
php bin/scratch build
```

To learn more about ScratchPHP, please see the [documentations page.](kenjiefx.github.io/scratch-php)

## Application Life Cycle
ScratchPHP begins with the instantiation of `Kenjiefx\ScratchPHP\App()`, which detects the runtime context—either CLI or HTTP—and instantiates the appropriate app runner interface. Depending on the context, the CLI runner handles terminal commands, while the HTTP runner manages web requests, both adhering to a common interface with environment-specific implementations. 

Before reaching the service layer, extensions are registered once per execution cycle by the Extension Manager. Early in the lifecycle, service providers are delegated based on the specific CLI command or HTTP route. Finally, the Build Service orchestrates the build process and hands off the export of static pages and assets to an export provider.

## Extensibility 

ScratchPHP provides a flexible way to extend the build process through Extensions. Extensions let you modify HTML, JavaScript, and CSS before they’re exported—ideal for tasks like minifying assets, injecting custom HTML, generating boilerplate code, and more.

```php
<?php 

namespace App;

use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Events\PageBuildStartedEvent;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionsInterface;

class MyExtension implements ExtensionsInterface {

    #[ListensTo(PageBuildStartedEvent::class)]
    public function doSomethingWhenPageBuildStarts(PageBuildStartedEvent $event) {
        // Please see all the fields you can retrieve from this event in the documentation
    }

}
```

## Documentation 

To learn more about ScratchPHP, please see the [documentations page.](kenjiefx.github.io/scratch-php)

## Contributing
Please see CONTRIBUTING.md.


