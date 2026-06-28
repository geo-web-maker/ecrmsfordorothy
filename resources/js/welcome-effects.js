(function () {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reducedMotion) {
        document.querySelectorAll('[data-reveal], [data-stagger]').forEach((el) => el.classList.add('in'));
        return;
    }

    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }
                entry.target.classList.add('in');
                revealObserver.unobserve(entry.target);
            });
        },
        { threshold: 0.1 }
    );

    document.querySelectorAll('[data-reveal], [data-stagger]').forEach((el) => revealObserver.observe(el));
})();
