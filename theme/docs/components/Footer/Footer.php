<footer class="footer">
  <div class="content has-text-centered">
    <p class="is-size-7">
      <strong>ScratchPHP</strong> by <a href="https://www.linkedin.com/in/rterrado/">Rom Terrado</a>.
      The website is proudly built with <strong>ScratchPHP</strong>. 
      This page is updated since <?php 
        $timestamp = page_data('updatedAt');
        echo date('F j, Y g:i A', (int) ($timestamp / 1000));
        ?>.
    </p>
  </div>
</footer>