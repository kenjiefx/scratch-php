$('document').ready(function() {
    const isMobile = window.matchMedia("(max-width: 768px)").matches
    let sidebarIsHidden = false
    if (isMobile) {
        $('.--sidebar-element').slideUp()
        sidebarIsHidden = true
    }
    $('[data-control="hambuger"]').on('click', function() {
        this.classList.toggle('is-active')
        if (sidebarIsHidden) {
            $('.--sidebar-element').slideDown()
            sidebarIsHidden = false
        } else {
            $('.--sidebar-element').slideUp()
            sidebarIsHidden = true
        }
    })
})