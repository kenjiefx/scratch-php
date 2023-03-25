# scratch-php
A simple, extendable, static site generator built using PHP

## Installation 
This package is available in Composer. To install in your root directory, command: 
```
composer require kenjiefx/scratch-php
```

# Getting Started
To get started, after the package is installed, follow along the steps provided below: 
1. Create a new file in your root directory named 'scratch'
2. Copy and paste the following code in the file. 
```
<?php
use Kenjiefx\ScratchPHP\App;

define('ROOT',__DIR__);
require ROOT.'/vendor/autoload.php';

$app = new App();
$app->run();
```
3. Create `/pages` directory in your root (discussed below)
4. Create `/dist` directory in your root (discussed below) 
5. Create `/theme` directory in your root (discussed below)

### Creating A New Theme 
To create a new theme, just run the command below: 
```
php scratch create:theme name_of_your_theme
```
You can find the new theme created in the \theme folder of your root directory. Notice as well that a file named `scratch.config.json` is created in your root directory.

### Creating An Index/Home Page 
To start a new page, just create a file named `index.json` in the \pages folder in your root directory, with the following content: 
```
{
    "template": "index",
    "title": "Hello, Scratch!"
}
```

### Build Your Page 
To build your index/home page, just run the following command below 
```
php scratch build
```
Notice that in your /dist folder, you can find the pages and assets rendered by ScratchPHP. 

### View Your Site In Your Local Server 
You can install any frameworks such as ExpressJS that are built for serving static websites. 

