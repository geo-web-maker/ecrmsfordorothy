import Alpine from 'alpinejs';
import {
    initLazyMedia,
    initPageShell,
    loadScript,
    loadStylesheet,
    markAsyncPanelReady,
    whenEcrmsReady,
} from './performance';

window.Alpine = Alpine;
window.ecrmsLoadScript = loadScript;
window.ecrmsLoadStylesheet = loadStylesheet;
window.ecrmsMarkAsyncPanelReady = markAsyncPanelReady;
window.ecrmsWhenReady = whenEcrmsReady;

initPageShell();
initLazyMedia();

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => Alpine.start(), { once: true });
} else {
    Alpine.start();
}
