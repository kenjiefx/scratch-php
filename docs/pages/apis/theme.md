# Theme API

### Template Content  
Renders the main content of the page based on the associated Page JSON configuration. This function should be called where you want the page's body content to appear.

```php
<?php template_content(); ?>
```

### Template Assets  
Injects the necessary CSS and JavaScript files used by the current theme. Typically placed within the `<head>` section of your HTML layout.

```php
<?php template_assets(); ?>
```

### Page Title  
Outputs the title of the page, as defined in the Page JSON configuration. Commonly used inside the `<title>` tag of the HTML document.

```php
<?php page_title(); ?>
```

### Page Data  
Retrieves a specific piece of custom data from the Page JSON configuration. Accepts a single parameter:  
- `string $field` — the key corresponding to the desired value in the Page JSON.

```php
<?php page_data('layoutType'); ?>
```

### Base URL  
Returns the base URL of the site, as defined in the ScratchJSON configuration file or via the CLI parameter `--baseUrl=`. Useful for constructing absolute paths to resources.

```php
<?php base_url(); ?>
```

### Component  
Renders a reusable component. Accepts two parameters:  
- `string $name` — the relative path to the component file within the `components` directory (supports nested paths).  
- `array $data` — an optional associative array of data passed to the component.

Example:
```php
<?php component("Forms\\LoginForm"); ?>
```

### Block  
Includes and renders a block file. Accepts two parameters:  
- `string $name` — the relative path to the block file.  
- `array $data` — an optional associative array of data passed to the block.

```php
<?php block("FormElements\\Input"); ?>
```

### Snippet  
Includes and renders a snippet file. Accepts the same parameters as `block()`:  
- `string $name` — the relative path to the snippet file.  
- `array $data` — an optional associative array of data passed to the snippet.

```php
<?php snippet("FormElements\\Input"); ?>
```

### Asset Links  
Generates a URL to an asset located in the `<theme_dir>/assets` directory. Use this to reference images, icons, scripts, etc.

```php
<?php asset('favicon.ico'); ?>
```
