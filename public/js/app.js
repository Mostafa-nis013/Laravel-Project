/* ── LaravelCMS – app.js ─────────────────────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', () => {

    // ── Sidebar toggle ─────────────────────────────────────────────────────────
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const toggle   = document.getElementById('menuToggle');
    const closeBtn = document.getElementById('sidebarClose');

    function openSidebar()  { sidebar?.classList.add('open'); overlay?.classList.add('open'); document.body.style.overflow = 'hidden'; }
    function closeSidebar() { sidebar?.classList.remove('open'); overlay?.classList.remove('open'); document.body.style.overflow = ''; }

    toggle?.addEventListener('click', openSidebar);
    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);

    // ── Alert auto-dismiss ─────────────────────────────────────────────────────
    document.querySelectorAll('.alert').forEach(alert => {
        const close = alert.querySelector('.alert-close');
        close?.addEventListener('click', () => fadeOut(alert));
        setTimeout(() => fadeOut(alert), 5000);
    });

    function fadeOut(el) {
        if (!el) return;
        el.style.transition = 'opacity .4s, max-height .4s, padding .4s, margin .4s';
        el.style.opacity    = '0';
        el.style.maxHeight  = '0';
        el.style.padding    = '0';
        el.style.margin     = '0';
        setTimeout(() => el.remove(), 420);
    }

    // ── Delete modal ───────────────────────────────────────────────────────────
    const deleteModal   = document.getElementById('deleteModalBackdrop');
    const deleteForm    = document.getElementById('deleteModalForm');
    const deleteMessage = document.getElementById('deleteModalMessage');
    const deleteClose   = document.getElementById('deleteModalClose');
    const deleteCancel  = document.getElementById('deleteModalCancel');

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const url  = btn.dataset.url;
            const name = btn.dataset.name || 'this item';
            if (deleteForm)    deleteForm.action    = url;
            if (deleteMessage) deleteMessage.textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
            openModal(deleteModal);
        });
    });

    deleteClose?.addEventListener('click',  () => closeModal(deleteModal));
    deleteCancel?.addEventListener('click', () => closeModal(deleteModal));
    deleteModal?.addEventListener('click', e => { if (e.target === deleteModal) closeModal(deleteModal); });

    function openModal(modal)  { modal?.classList.add('open'); modal && (modal.style.display = 'flex'); }
    function closeModal(modal) { modal?.classList.remove('open'); modal && (modal.style.display = 'none'); }

    // ── Toggle password visibility ─────────────────────────────────────────────
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.target);
            if (!target) return;
            const isText = target.type === 'text';
            target.type = isText ? 'password' : 'text';
            btn.textContent = isText ? '👁' : '🙈';
        });
    });

    // ── Demo account buttons (login page) ─────────────────────────────────────
    document.querySelectorAll('.demo-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const emailField    = document.getElementById('email');
            const passwordField = document.getElementById('password');
            if (emailField)    emailField.value    = btn.dataset.email;
            if (passwordField) passwordField.value = 'password';

            // Visual feedback
            btn.textContent = '✓ Filled';
            btn.style.background = '#d1fae5';
            btn.style.borderColor = '#6ee7b7';
            setTimeout(() => {
                btn.textContent  = btn.dataset.email.split('@')[0].replace(/(^\w)/g, c => c.toUpperCase()).replace(/([a-z])([A-Z])/g, '$1 $2');
                btn.style.background = '';
                btn.style.borderColor = '';
            }, 1200);
        });
    });

    // Fix demo btn labels
    document.querySelectorAll('.demo-btn').forEach(btn => {
        const map = {
            'superadmin@demo.com': 'Super Admin',
            'admin@demo.com':      'Admin',
            'editor@demo.com':     'Editor',
            'user@demo.com':       'User',
        };
        if (map[btn.dataset.email]) btn.textContent = map[btn.dataset.email];
    });

    // ── Password strength meter ────────────────────────────────────────────────
    const passwordInput = document.getElementById('password');
    const strengthBar   = document.querySelector('.strength-bar');

    if (passwordInput && strengthBar) {
        passwordInput.addEventListener('input', () => {
            const val = passwordInput.value;
            let score = 0;
            if (val.length >= 8)              score++;
            if (/[A-Z]/.test(val))            score++;
            if (/[0-9]/.test(val))            score++;
            if (/[^A-Za-z0-9]/.test(val))     score++;

            const colors = ['', '#ef4444', '#f59e0b', '#10b981', '#6366f1'];
            const widths = ['0%', '25%', '50%', '75%', '100%'];
            strengthBar.style.width      = val.length ? widths[score] : '0%';
            strengthBar.style.background = val.length ? colors[score] : '';
        });
    }

    // ── Login form validation ──────────────────────────────────────────────────
    const loginForm = document.getElementById('loginForm');
    loginForm?.addEventListener('submit', e => {
        let valid = true;
        clearErrors(loginForm);

        const email    = loginForm.querySelector('#email');
        const password = loginForm.querySelector('#password');

        if (!email?.value.trim()) {
            showError(email, 'Email is required.');
            valid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            showError(email, 'Enter a valid email address.');
            valid = false;
        }
        if (!password?.value) {
            showError(password, 'Password is required.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        } else {
            const btn = loginForm.querySelector('#loginBtn');
            btn?.querySelector('.btn-text')?.classList.add('hidden');
            btn?.querySelector('.btn-spinner')?.classList.remove('hidden');
        }
    });

    // ── Register form validation ───────────────────────────────────────────────
    const registerForm = document.getElementById('registerForm');
    registerForm?.addEventListener('submit', e => {
        let valid = true;
        clearErrors(registerForm);

        const name     = registerForm.querySelector('#name');
        const email    = registerForm.querySelector('#email');
        const password = registerForm.querySelector('#password');
        const confirm  = registerForm.querySelector('#password_confirmation');

        if (!name?.value.trim()) {
            showError(name, 'Name is required.');
            valid = false;
        }
        if (!email?.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            showError(email, 'Enter a valid email address.');
            valid = false;
        }
        if (!password?.value || password.value.length < 8) {
            showError(password, 'Password must be at least 8 characters.');
            valid = false;
        }
        if (confirm?.value !== password?.value) {
            showError(confirm, 'Passwords do not match.');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });

    // ── Article form validation ────────────────────────────────────────────────
    const articleForm = document.getElementById('articleForm');
    articleForm?.addEventListener('submit', e => {
        let valid = true;
        clearErrors(articleForm);

        const title   = articleForm.querySelector('#title');
        const content = articleForm.querySelector('#content');

        if (!title?.value.trim()) {
            showError(title, 'Title is required.');
            valid = false;
        }
        if (!content?.value.trim()) {
            showError(content, 'Content is required.');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });

    // ── User form validation ───────────────────────────────────────────────────
    const userForm = document.getElementById('userForm');
    userForm?.addEventListener('submit', e => {
        let valid = true;
        clearErrors(userForm);

        const name     = userForm.querySelector('#name');
        const email    = userForm.querySelector('#email');
        const password = userForm.querySelector('#password');
        const confirm  = userForm.querySelector('#password_confirmation');

        if (!name?.value.trim()) {
            showError(name, 'Name is required.');
            valid = false;
        }
        if (!email?.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            showError(email, 'Enter a valid email address.');
            valid = false;
        }
        // Password is only required on create (check if action doesn't contain /{id})
        const isCreate = !userForm.querySelector('input[name="_method"]');
        if (isCreate && (!password?.value || password.value.length < 8)) {
            showError(password, 'Password must be at least 8 characters.');
            valid = false;
        }
        if (password?.value && confirm?.value !== password?.value) {
            showError(confirm, 'Passwords do not match.');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });

    // ── Category form validation ───────────────────────────────────────────────
    const categoryForm = document.getElementById('categoryForm');
    categoryForm?.addEventListener('submit', e => {
        let valid = true;
        clearErrors(categoryForm);

        const name = categoryForm.querySelector('#name');
        if (!name?.value.trim()) {
            showError(name, 'Category name is required.');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });

    // ── Filter form auto-submit on select change ───────────────────────────────
    document.querySelectorAll('.filter-form select').forEach(sel => {
        sel.addEventListener('change', () => sel.closest('form').submit());
    });

    // ── Helper functions ───────────────────────────────────────────────────────
    function showError(input, message) {
        if (!input) return;
        input.classList.add('is-invalid');
        const err = document.createElement('span');
        err.className   = 'form-error js-error';
        err.textContent = message;
        input.closest('.form-group, .input-wrapper')?.appendChild(err)
            || input.insertAdjacentElement('afterend', err);
    }

    function clearErrors(form) {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.js-error').forEach(el => el.remove());
    }

    // ── Char counters ──────────────────────────────────────────────────────────
    document.querySelectorAll('[data-max]').forEach(counter => {
        const targetId = counter.dataset.target;
        const max      = parseInt(counter.dataset.max);
        const input    = document.getElementById(targetId);
        if (!input) return;

        const update = () => {
            const len = input.value.length;
            counter.textContent = `${len} / ${max}`;
            counter.style.color = len > max * .9 ? 'var(--color-red)' : 'var(--text-muted)';
        };
        input.addEventListener('input', update);
        update();
    });
});
