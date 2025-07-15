<?php $baseUrl = base_url(); ?>
<aside class="menu">
  <p class="menu-label">Documentation</p>
  <ul class="menu-list">
    <li>
      <a href="<?php echo $baseUrl; ?>" data-sidebar-activate="/">Introduction</a>
      <ul>
        <li><a href="/#introduction">About Scratch PHP</a></li>
        <li><a href="/#getting_started">Getting Started</a></li>
        <li><a href="/#application_life_cycle">Application Life Cycle</a></li>
        <li><a href="/#extensibility">Extensibility</a></li>
      </ul>
    </li>
  </ul>
  <p class="menu-label">API</p>
  <ul class="menu-list">
    <li>
      <a href="<?php echo $baseUrl; ?>apis/theme.html" data-sidebar-activate="/apis/theme.html">Theme API</a>
      <ul>
        <li><a href="<?php echo $baseUrl; ?>apis/theme.html#template_content">Template Content</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/theme.html#template_assets">Template Assets</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/theme.html#page_title">Page Title</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/theme.html#component">Component</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/theme.html#block">Block</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/theme.html#snippet">Snippet</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/theme.html#asset_links">Asset Links</a></li>
      </ul>
    </li>
    <li>
      <a href="<?php echo $baseUrl; ?>apis/extensions.html" data-sidebar-activate="/apis/extensions.html">Extensions</a>
      <ul>
        <li><a href="<?php echo $baseUrl; ?>apis/extensions.html#registration">Registration</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/extensions.html#settings_registry">Settings Registry</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/extensions.html#extension_interface">Extension Interface</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/extensions.html#listensto_attribute">ListensTo Attribute</a></li>
      </ul>
    </li>
    <li>
      <a href="<?php echo $baseUrl; ?>apis/commands.html" data-sidebar-activate="/apis/commands.html">Commands</a>
      <ul>
        <li><a href="<?php echo $baseUrl; ?>apis/commands.html#build_command">Build Command</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/commands.html#create_theme">Create Theme</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/commands.html#create_template">Create Template</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/commands.html#create_component">Create Component</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/commands.html#create_block">Create Block</a></li>
      </ul>
    </li>
    <li>
      <a href="<?php echo $baseUrl; ?>apis/events.html" data-sidebar-activate="/apis/events.html">Events</a>
      <ul>
        <li><a href="<?php echo $baseUrl; ?>apis/events.html#pagebuildstartedevent">PageBuildStartedEvent</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/events.html#htmlbuildcompletedevent">HTMLBuildCompletedEvent</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/events.html#cssbuildcompletedevent">CSSBuildCompletedEvent</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/events.html#jsbuildcompletedevent">JSBuildCompletedEvent</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/events.html#componentcsscollectedevent">ComponetHTMLCollectedEvent</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/events.html#htmlbuildcompletedevent">ComponentCSSCollectedEvent</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/events.html#componentjscollectedevent">ComponentJSCollectedEvent</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/events.html#blockcsscollectedevent">BlockCSSCollectedEvent</a></li>
        <li><a href="<?php echo $baseUrl; ?>apis/events.html#blockjscollectedevent">BlockJSCollectedEvent</a></li>
      </ul>
    </li>
  </ul>
</aside>