# Commands
ScratchPHP includes a powerful command-line interface (CLI) that lets you build your static site and scaffold elements like components, blocks, and templates with ease.

### Build Command
You can build all the pages by running `php bin/scratch build`. This command accepts 2 options:
- `--pageJson=index.json` To build just a specific page 
- `--baseUrl=` To add base URL to the config. By default, the base URL is `\`

### Create Theme
To create a new theme, you can execute `php bin/scratch create:theme <theme_name>`.

### Create Template
To create a new template within the theme, you can execute `php bin/scratch create:template <template_name>`.

### Create Component
To create a new component, `php bin/scratch create:component <component_name>`.

### Create Block
To create a new block, `php bin/scratch create:block <block_name>`.