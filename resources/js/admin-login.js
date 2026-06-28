export function initAdminLogin() {
    initCursor();
    initParticles();
    initPasswordToggle();
    initLiveFeed();
    initLoginForm();
}

function initCursor() {
    const cur = document.getElementById('cur');
    const ring = document.getElementById('cur-ring');

    if (!cur || !ring || window.matchMedia('(max-width: 768px)').matches) {
        return;
    }

    let mx = 0;
    let my = 0;
    let rx = 0;
    let ry = 0;

    document.addEventListener('mousemove', (e) => {
        mx = e.clientX;
        my = e.clientY;
    });

    const tick = () => {
        rx += (mx - rx) * 0.11;
        ry += (my - ry) * 0.11;
        cur.style.left = `${mx}px`;
        cur.style.top = `${my}px`;
        ring.style.left = `${rx}px`;
        ring.style.top = `${ry}px`;
        requestAnimationFrame(tick);
    };

    tick();

    document.addEventListener('mousedown', () => cur.classList.add('clk'));
    document.addEventListener('mouseup', () => cur.classList.remove('clk'));

    document.querySelectorAll('a, button, input').forEach((el) => {
        el.addEventListener('mouseenter', () => {
            cur.classList.add('hov');
            ring.classList.add('hov');
        });
        el.addEventListener('mouseleave', () => {
            cur.classList.remove('hov');
            ring.classList.remove('hov');
        });
    });
}

function initParticles() {
    const canvas = document.getElementById('particles');

    if (!canvas) {
        return;
    }

    const ctx = canvas.getContext('2d');
    let W = 0;
    let H = 0;
    const pts = [];

    const resize = () => {
        W = canvas.width = canvas.offsetParent?.offsetWidth || window.innerWidth / 2;
        H = canvas.height = window.innerHeight;
    };

    class Pt {
        constructor(initial = false) {
            this.reset(initial);
        }

        reset(initial = false) {
            this.x = Math.random() * W;
            this.y = initial ? Math.random() * H : H + 5;
            this.r = Math.random() * 1.6 + 0.3;
            this.vy = -(Math.random() * 0.45 + 0.15);
            this.vx = (Math.random() - 0.5) * 0.2;
            this.o = Math.random() * 0.3 + 0.05;
        }

        tick() {
            this.y += this.vy;
            this.x += this.vx;

            if (this.y < -4) {
                this.reset(false);
            }
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(34,197,94,${this.o})`;
            ctx.fill();
        }
    }

    resize();
    window.addEventListener('resize', resize);

    for (let i = 0; i < 60; i++) {
        pts.push(new Pt(true));
    }

    const loop = () => {
        ctx.clearRect(0, 0, W, H);
        pts.forEach((p) => {
            p.tick();
            p.draw();
        });
        requestAnimationFrame(loop);
    };

    loop();
}

function initPasswordToggle() {
    const pwd = document.getElementById('pwdInput');
    const toggle = document.getElementById('pwdToggle');
    const eyeIcon = document.getElementById('eyeIcon');

    if (!pwd || !toggle || !eyeIcon) {
        return;
    }

    toggle.addEventListener('click', () => {
        const show = pwd.type === 'password';
        pwd.type = show ? 'text' : 'password';
        eyeIcon.innerHTML = show
            ? '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
            : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    });
}

function initLiveFeed() {
    const feedEl = document.getElementById('live-feed-data');

    if (!feedEl) {
        return;
    }

    let feeds = [];

    try {
        feeds = JSON.parse(feedEl.textContent || '[]');
    } catch {
        return;
    }

    if (feeds.length < 2) {
        return;
    }

    let fi = 0;

    setInterval(() => {
        fi = (fi + 1) % feeds.length;
        const items = document.querySelectorAll('.lf-item');

        if (!items.length) {
            return;
        }

        const f = feeds[fi];
        const first = items[0];

        first.style.opacity = '0';
        first.style.transform = 'translateY(-8px)';

        setTimeout(() => {
            first.querySelector('.lf-item-dot').className = `lf-item-dot ${f.color}`;
            first.querySelector('.lf-item-id').textContent = f.id;
            first.querySelector('.lf-item-type').textContent = f.type;
            first.querySelector('.lf-item-time').textContent = f.time;
            first.style.transition = 'none';
            first.style.transform = 'translateY(8px)';

            setTimeout(() => {
                first.style.transition = 'all 0.4s ease';
                first.style.opacity = '1';
                first.style.transform = 'none';
            }, 20);
        }, 300);
    }, 3000);
}

function initLoginForm() {
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('submitBtn');
    const emailIn = document.getElementById('emailInput');
    const pwdIn = document.getElementById('pwdInput');
    const emailErr = document.getElementById('emailError');
    const pwdErr = document.getElementById('pwdError');

    if (!form || !btn || !emailIn || !pwdIn) {
        return;
    }

    const hasServerEmailError = emailIn.classList.contains('error');
    const hasServerPwdError = pwdIn.classList.contains('error');

    if (hasServerEmailError && emailErr) {
        emailErr.classList.add('show');
    }

    if (hasServerPwdError && pwdErr) {
        pwdErr.classList.add('show');
    }

    const validate = () => {
        let ok = true;

        if (!emailIn.value || !emailIn.value.includes('@')) {
            emailIn.classList.add('error');
            emailErr?.classList.add('show');
            emailIn.style.animation = 'admin-shake 0.4s ease';
            setTimeout(() => { emailIn.style.animation = ''; }, 400);
            ok = false;
        } else {
            emailIn.classList.remove('error');
            emailErr?.classList.remove('show');
        }

        if (!pwdIn.value || pwdIn.value.length < 8) {
            pwdIn.classList.add('error');
            pwdErr?.classList.add('show');
            pwdIn.style.animation = 'admin-shake 0.4s ease';
            setTimeout(() => { pwdIn.style.animation = ''; }, 400);
            ok = false;
        } else {
            pwdIn.classList.remove('error');
            pwdErr?.classList.remove('show');
        }

        return ok;
    };

    emailIn.addEventListener('input', () => {
        if (emailIn.value.includes('@')) {
            emailIn.classList.remove('error');
            emailErr?.classList.remove('show');
        }
    });

    pwdIn.addEventListener('input', () => {
        if (pwdIn.value.length >= 8) {
            pwdIn.classList.remove('error');
            pwdErr?.classList.remove('show');
        }
    });

    form.addEventListener('submit', (e) => {
        if (!validate()) {
            e.preventDefault();
            return;
        }

        btn.classList.add('loading');
        btn.disabled = true;
    });
}

document.addEventListener('DOMContentLoaded', initAdminLogin);
