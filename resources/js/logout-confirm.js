let pendingLogoutForm = null;
let logoutBypassConfirm = false;

function openLogoutConfirm(form) {
    const modal = document.getElementById('logout-confirm-modal');
    if (!modal) {
        form.submit();
        return;
    }

    pendingLogoutForm = form;
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    modal.querySelector('[data-logout-confirm]')?.focus();
}

function closeLogoutConfirm() {
    const modal = document.getElementById('logout-confirm-modal');
    if (!modal) {
        return;
    }

    pendingLogoutForm = null;
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

export function initLogoutConfirm() {
    const modal = document.getElementById('logout-confirm-modal');
    if (!modal) {
        return;
    }

    document.querySelectorAll('form[action*="logout"]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (logoutBypassConfirm) {
                return;
            }

            event.preventDefault();
            openLogoutConfirm(form);
        });

        form.querySelectorAll('a[href*="logout"]').forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();

                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                }
            });
        });
    });

    modal.querySelectorAll('[data-logout-cancel]').forEach((el) => {
        el.addEventListener('click', closeLogoutConfirm);
    });

    modal.querySelector('[data-logout-confirm]')?.addEventListener('click', () => {
        if (!pendingLogoutForm) {
            return;
        }

        const form = pendingLogoutForm;
        closeLogoutConfirm();
        logoutBypassConfirm = true;
        form.submit();
        logoutBypassConfirm = false;
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeLogoutConfirm();
        }
    });
}
