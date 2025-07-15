<nav class="navbar is-transparent">
  <div class="navbar-brand">
    <a class="navbar-item" href="https://kenjiefx.github.io/scratch-php/">
      <img src="<?php asset('scratch-logo.png'); ?>">
    </a>
    <a class="navbar-item" href="https://kenjiefx.github.io/scratch-php/">
      <?php block('TextGlitch'); ?>
    </a>
    <div class="navbar-burger js-burger" data-control="hambuger">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>

  <div class="navbar-menu">
    <div class="navbar-start">
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="field is-grouped">
          <p class="control">
            <a
              class="bd-tw-button button"
              data-social-network="Twitter"
              data-social-action="tweet"
              data-social-target="https://bulma.io"
              target="_blank"
              href="https://github.com/kenjiefx/scratch-php"
            >
              <span class="icon">
                <i class="fab fa-github"></i>
              </span>
              <span> See Github </span>
            </a>
          </p>
          <p class="control">
            <a class="button is-primary" href="https://github.com/kenjiefx/scratch-php-skeleton/archive/refs/heads/main.zip">
              <span class="icon">
                <i class="fas fa-download"></i>
              </span>
              <span>Download</span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</nav>