/**
 * Performance helpers: page skeleton, lazy media, deferred external scripts.
 */

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function initPageShell() {
    const skeleton = document.getElementById('page-skeleton');

    const reveal = () => {
        document.documentElement.classList.remove('is-loading');
        document.documentElement.classList.add('is-ready');

        if (skeleton) {
            skeleton.classList.add('is-hidden');
            window.setTimeout(() => skeleton.remove(), 300);
        }
    };

    if (prefersReducedMotion) {
        reveal();
        return;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', reveal, { once: true });
    } else {
        requestAnimationFrame(reveal);
    }
}

export function initLazyMedia() {
    document.querySelectorAll('[data-lazy-media]').forEach((wrapper) => {
        const media = wrapper.querySelector('img, video');

        if (!media) {
            wrapper.classList.add('is-loaded');
            return;
        }

        const markLoaded = () => wrapper.classList.add('is-loaded');
        const markError = () => wrapper.classList.add('is-error');

        if (media.tagName === 'IMG' && media.complete && media.naturalWidth > 0) {
            markLoaded();
            return;
        }

        if (media.tagName === 'VIDEO' && media.readyState >= 2) {
            markLoaded();
            return;
        }

        media.addEventListener('load', markLoaded, { once: true });
        media.addEventListener('loadeddata', markLoaded, { once: true });
        media.addEventListener('error', markError, { once: true });
    });

    if (!('loading' in HTMLImageElement.prototype)) {
        const images = document.querySelectorAll('img[loading="lazy"][data-src]');

        if (images.length && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    obs.unobserve(img);
                });
            }, { rootMargin: '200px 0px' });

            images.forEach((img) => observer.observe(img));
        }
    }
}

export function loadScript(src) {
    return new Promise((resolve, reject) => {
        const existing = document.querySelector(`script[data-src="${src}"]`);

        if (existing) {
            if (existing.dataset.loaded === 'true' || existing.getAttribute('data-loaded') === 'true') {
                resolve();
                return;
            }

            if (existing.readyState === 'complete' || existing.readyState === 'loaded') {
                existing.dataset.loaded = 'true';
                resolve();
                return;
            }

            existing.addEventListener('load', () => {
                existing.dataset.loaded = 'true';
                resolve();
            }, { once: true });
            existing.addEventListener('error', () => reject(new Error(`Failed to load ${src}`)), { once: true });
            return;
        }

        const script = document.createElement('script');
        script.src = src;
        script.defer = true;
        script.dataset.src = src;
        script.onload = () => {
            script.dataset.loaded = 'true';
            resolve();
        };
        script.onerror = () => reject(new Error(`Failed to load ${src}`));
        document.body.appendChild(script);
    });
}

export function loadStylesheet(href) {
    return new Promise((resolve, reject) => {
        if (document.querySelector(`link[data-href="${href}"]`)) {
            resolve();
            return;
        }

        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;
        link.dataset.href = href;
        link.onload = () => resolve();
        link.onerror = () => reject(new Error(`Failed to load ${href}`));
        document.head.appendChild(link);
    });
}

export function markAsyncPanelReady(panel) {
    if (panel) {
        panel.classList.add('is-ready');
    }
}

export function whenEcrmsReady() {
    return new Promise((resolve) => {
        if (typeof window.ecrmsLoadScript === 'function') {
            resolve();
            return;
        }

        const wait = () => {
            if (typeof window.ecrmsLoadScript === 'function') {
                resolve();
            } else {
                requestAnimationFrame(wait);
            }
        };

        wait();
    });
}
