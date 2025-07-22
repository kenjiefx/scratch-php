<?php 

/**
 * ScratchPHP Preview Server
 *
 * Start this server with:
 *   php -S 127.0.0.1:7743 app.php
 *
 * ⚠️ This server is intended for development only.
 *
 * ScratchPHP is a static site generator — it produces static HTML, CSS, and JS
 * files in the <export> folder. However, for convenience, this built-in server
 * can be used to serve your site without manually running the build command.
 *
 * On each request, this server dynamically builds the relevant static page before
 * responding. This makes it easy to see changes live during development.
 *
 * For production deployments, it is highly recommended to use a static file server
 * like Nginx or Apache to serve the pre-generated contents of the <export> folder
 * instead of routing traffic through this PHP entry point.
 */

use Kenjiefx\ScratchPHP\App;

define('ROOT', __DIR__);
require 'vendor/autoload.php';

$app = new App();
$app->run();