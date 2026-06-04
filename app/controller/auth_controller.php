<script>
function toggleForm(formType, event) {
    event.preventDefault();
    const loginEl = document.getElementById('login-form');
    const regEl   = document.getElementById('register-form');

    if (formType === 'register') {
        loginEl.classList.add('d-none');
        regEl.classList.remove('d-none');
        // re-trigger animation
        regEl.style.animation = 'none';
        regEl.offsetHeight;
        regEl.style.animation = '';
        window.history.pushState({}, '', 'index.php?page=register');
    } else {
        regEl.classList.add('d-none');
        loginEl.classList.remove('d-none');
        loginEl.style.animation = 'none';
        loginEl.offsetHeight;
        loginEl.style.animation = '';
        window.history.pushState({}, '', 'index.php?page=login');
    }
}

// Sync carousel dots
(function () {
    const el = document.getElementById('authCarousel');
    if (!el) return;
    el.addEventListener('slid.bs.carousel', function (e) {
        document.querySelectorAll('[id^="dot-"]').forEach(d => d.classList.remove('active'));
        const active = document.getElementById('dot-' + e.to);
        if (active) active.classList.add('active');
    });
})();
</script>