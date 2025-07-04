<?php use Kenjiefx\ScratchPHP\App\HTTP\Editor\EditorService; ?>
<section class="--editor-mainframe">
    <section class="--editor-component-wrapper">
        <?php component(EditorService::getComponentModel()->namespace); ?>
    </section>
</section>

<style>
.--editor-mainframe {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background-color: var(--editor-bg-color, #f0f0f0);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.--editor-component-wrapper {
    max-width: 400px;
    width: 100%;
    height: max-content;
    box-shadow: 1px 3px 9px 0px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}
</style>