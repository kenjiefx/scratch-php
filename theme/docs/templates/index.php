<div class="layout site-wrapper" style="display:none;">
  <?php component('Header'); ?>
  <main class="section">
    <div class="columns">
      <div class="column is-3 --sidebar-element">
        <?php component('Sidebar'); ?>
      </div>
      <div class="column is-9">
        <div class="content">
          <?php echo page_data('content'); ?>
        </div>
      </div>
    </div>
  </main>
  <?php component('Footer'); ?>
</div>