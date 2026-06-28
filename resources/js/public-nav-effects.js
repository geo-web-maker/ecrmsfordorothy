(function () {
    const navHeader = document.getElementById('public-nav-header');
    if (!navHeader) {
        return;
    }

    const onScroll = () => navHeader.classList.toggle('nav-scrolled', window.scrollY > 50);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
})();
