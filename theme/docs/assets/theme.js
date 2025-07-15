setTimeout(() => {
    $('.site-wrapper').fadeIn()
    let pathname = location.pathname
    pathname = pathname.replace('/scratch-php/', '/')
    $(`[data-sidebar-activate="${pathname}"]`).addClass('is-active')
}, 1000);