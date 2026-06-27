<script>
    window.ecrmsMarkAsyncPanelReady = function (panel) {
        if (panel) panel.classList.add('is-ready');
    };

    // Reveal page if the Vite bundle fails to load (e.g. dev server unreachable).
    setTimeout(function () {
        if (!document.documentElement.classList.contains('is-ready')) {
            document.documentElement.classList.remove('is-loading');
            document.documentElement.classList.add('is-ready');
            var sk = document.getElementById('page-skeleton');
            if (sk) sk.classList.add('is-hidden');
        }
    }, 3500);
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"></noscript>

@vite(['resources/css/app.css', 'resources/js/app.js'])

@stack('styles')
