<?php 

namespace Kenjiefx\ScratchPHP\App\Builders;

/**
 * List of messages that can be sent to the HTML Builder.
 */
enum BuildMessage: string
{
    case TEMPLATE_RENDER = 'template.render';
    case GET_THEME = 'theme.get';
    case GET_TEMPLATE = 'template.get';
    case GET_PAGE = 'page.get';
    case GET_COMPONENT_REGISTRY = 'component.registry.get';

}