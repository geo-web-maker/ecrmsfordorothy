import Alpine from 'alpinejs';
import {
    initLazyMedia,
    initPageShell,
    loadScript,
    loadStylesheet,
    markAsyncPanelReady,
    whenEcrmsReady,
} from './performance';
import { initLogoutConfirm } from './logout-confirm';

window.Alpine = Alpine;
window.ecrmsLoadScript = loadScript;
window.ecrmsLoadStylesheet = loadStylesheet;
window.ecrmsMarkAsyncPanelReady = markAsyncPanelReady;
window.ecrmsWhenReady = whenEcrmsReady;

initPageShell();
initLazyMedia();
initLogoutConfirm();

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => Alpine.start(), { once: true });
} else {
    Alpine.start();
}
