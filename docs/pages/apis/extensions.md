# Extensions
Extensions are a powerful way to enhance and customize functionality in Scratch. They allow you to modify HTML, JavaScript, and CSS before they are exported—perfect for tasks like minifying CSS, injecting custom HTML, and more.

Extensions can also listen to events emitted by Scratch PHP, giving you deeper control over the build process. Additionally, they can provide boilerplate code when creating new components and blocks, helping streamline development.

### Registration

Extensions are registered in the `extensions` field of the scratch.json configuration file. Each extension must specify its fully qualified PSR-compliant namespace (e.g., `VendorNamespace\\PackageName\\ExtensionClass`).

```json
{
  "extensions": {
    "VendorNamespace\\PackageName\\ExtensionClass": {}
  }
}
```

### Settings Registry

Extensions can declare their own settings, which are registered when Scratch PHP initializes the extension. These settings can be accessed within the extension class and may be stored as static values if needed.

When building multiple pages, Scratch PHP ensures that each extension is registered only once, preserving efficiency.

```json
{
  "extensions": {
    "VendorNamespace\\PackageName\\ExtensionClass": {
        "allowToDoSomething": true
    }
  }
}
```

### Extension Interface

`ExtensionClass` must implement the `Kenjiefx\ScratchPHP\App\Extensions\ExtensionsInterface`. Although the interface is currently empty, it may include required methods or implementation details in future releases.

Please note that Scratch uses PHP’s Reflection API to verify that the extension implements the interface. If it does not, an error will be thrown during registration.

### ListensTo Attribute

To listen to events, define a method within your `ExtensionClass` and annotate it with the `Kenjiefx\ScratchPHP\App\Events\ListensTo` attribute. Pass the event class reference as the argument to the attribute.

You can find all available event classes at the following link:

```php
<?php 

namespace VendorNamespace\PackageName;

use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Events\PageBuildStartedEvent;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionsInterface;

class MyExtensionClass implements ExtensionsInterface {

    #[ListensTo(PageBuildStartedEvent::class)]
    public function doSomethingWhenPageBuildStarted(PageBuildStartedEvent $event) {
        // Please see all the fields you can retrieve from this event in the events docs
    }

}
```