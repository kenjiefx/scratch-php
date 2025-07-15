# Events
Events are objects dispatched at various stages of the build process. You can listen to these events and respond with custom actions as needed.

All event classes are namespaced under `Kenjiefx\ScratchPHP\App\Events`.

### PageBuildStartedEvent
Dispatched as soon as the page build process is started. Has 2 public methods: 
- `getName(): string` - returns the name of the page 
- `getPageModel(): PageModel` - returns data about the page

### HTMLBuildCompletedEvent
Dispatched as soon as the page completed building the html. Has 4 public methods: 
- `getName(): string` - returns the name of the page 
- `getPage(): PageModel` - returns data about the page
- `getContent(): string` - returns the rendered page html content
- `updateContent(string $content): void` - updates the html content

### CSSBuildCompletedEvent
Dispatched as soon as the page completed building the css. Has 4 public methods: 
- `getName(): string` - returns the name of the page 
- `getPage(): PageModel` - returns data about the page
- `getContent(): string` - returns the rendered page css content
- `updateContent(string $content): void` - updates the css content

### JSBuildCompletedEvent
Dispatched as soon as the page completed building the javascript. Has 4 public methods: 
- `getName(): string` - returns the name of the page 
- `getPage(): PageModel` - returns data about the page
- `getContent(): string` - returns the rendered page js content
- `updateContent(string $content): void` - updates the js content

### ComponetHTMLCollectedEvent
Dispatched after the the component HTML is rendered. Has 6 methods: 
- `getName(): string` - returns the name of the Component
- `getComponent(): ComponentModel` - returns data about the Component
- `getContent(): string` - returns the rendered component content
- `getComponentDir(): string` - returns the directory location of the component 
- `updateContent(string $content): void` - updates the content of the Component
- `getData(): array` - returns the data passed to the component

### ComponentCSSCollectedEvent
Dispatched after component CSS is retrieved. Has 5 methods: 
- `getName(): string` - returns the name of the Component
- `getComponent(): ComponentModel` - returns data about the Component
- `getContent(): string` - returns the rendered component content
- `getComponentDir(): string` - returns the directory location of the component 
- `updateContent(string $content): void` - updates the content of the Component

### ComponentJSCollectedEvent
Dispatched after component CSS is retrieved. Has 5 methods: 
- `getName(): string` - returns the name of the Component
- `getComponent(): ComponentModel` - returns data about the Component
- `getContent(): string` - returns the rendered component content
- `getComponentDir(): string` - returns the directory location of the component 
- `updateContent(string $content): void` - updates the content of the Component

### BlockCSSCollectedEvent
Dispatched after block CSS is retrieved. Has 5 methods: 
- `getName(): string` - returns the name of the Block
- `getBlock(): BlockModel` - returns data about the Block
- `getContent(): string` - returns the rendered block content
- `getgetBlockDir(): string` - returns the directory location of the block 
- `updateContent(string $content): void` - updates the content of the block

### BlockJSCollectedEvent
Dispatched after block CSS is retrieved. Has 5 methods: 
- `getName(): string` - returns the name of the Block
- `getBlock(): BlockModel` - returns data about the Block
- `getContent(): string` - returns the rendered block content
- `getBlockDir(): string` - returns the directory location of the block 
- `updateContent(string $content): void` - updates the content of the block