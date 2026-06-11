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

// --- XÁC THỰC DỮ LIỆU FORM (VALIDATION) ---
function showError(input, message) {
    let container = input.closest('.field-group');
    if (!container) return;
    let errorDiv = container.querySelector('.error-text');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-text';
        errorDiv.style.color = '#dc3545';
        errorDiv.style.fontSize = '12px';
        errorDiv.style.marginTop = '4px';
        container.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
    input.classList.add('is-invalid');
    input.style.borderColor = '#dc3545';
}

function clearError(input) {
    let container = input.closest('.field-group');
    if (!container) return;
    let errorDiv = container.querySelector('.error-text');
    if (errorDiv) {
        errorDiv.remove();
    }
    input.classList.remove('is-invalid');
    input.style.borderColor = '';
}

document.addEventListener('DOMContentLoaded', function() {
    const loginContainer = document.getElementById('login-form');
    const registerContainer = document.getElementById('register-form');

    // Xác thực form Đăng nhập
    if (loginContainer) {
        const loginForm = loginContainer.tagName === 'FORM' ? loginContainer : loginContainer.querySelector('form');
        if (loginForm) {
            loginForm.setAttribute('novalidate', 'true'); // Tắt validation mặc định của HTML5
            loginForm.addEventListener('submit', function(e) {
                if (!validateLoginForm(loginForm)) {
                    e.preventDefault(); // Chặn việc gửi form nếu có lỗi
                }
            });

            loginForm.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', () => {
                    clearError(input);
                });
                input.addEventListener('blur', () => {
                    validateLoginField(input);
                });
            });
        }
    }

    // Xác thực form Đăng ký
    if (registerContainer) {
        const registerForm = registerContainer.tagName === 'FORM' ? registerContainer : registerContainer.querySelector('form');
        if (registerForm) {
            registerForm.setAttribute('novalidate', 'true'); // Tắt validation mặc định của HTML5
            registerForm.addEventListener('submit', function(e) {
                if (!validateRegisterForm(registerForm)) {
                    e.preventDefault(); // Chặn việc gửi form nếu có lỗi
                }
            });

            registerForm.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', () => {
                    clearError(input);
                    // Để trải nghiệm tốt, có thể kiểm tra luôn trên sự kiện input với email
                    if (input.name === 'email' && input.value.trim() !== '') {
                         const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                         if (!emailRegex.test(input.value.trim())) {
                             showError(input, 'Email không hợp lệ (VD: example@mail.com)!');
                         }
                    }
                });
                input.addEventListener('blur', () => {
                    validateRegisterField(input);
                });
            });
        }
    }
});

function validateLoginField(input) {
    const name = input.name;
    const value = input.value.trim();
    let valid = true;

    if (name === 'username') {
        if (value === '') {
            showError(input, 'Vui lòng nhập tên đăng nhập hoặc email!');
            valid = false;
        } else {
            clearError(input);
        }
    } else if (name === 'password') {
        if (value === '') {
            showError(input, 'Vui lòng nhập mật khẩu!');
            valid = false;
        } else {
            clearError(input);
        }
    }
    return valid;
}

function validateLoginForm(form) {
    const inputs = form.querySelectorAll('input');
    let isValid = true;

    inputs.forEach(input => {
        if (!validateLoginField(input)) {
            isValid = false;
        }
    });

    return isValid;
}

function validateRegisterField(input) {
    const name = input.name;
    const value = input.value.trim();
    let valid = true;

    if (name === 'fullname') {
        if (value === '') {
            showError(input, 'Vui lòng nhập họ và tên!');
            valid = false;
        } else {
            clearError(input);
        }
    } else if (name === 'username') {
        if (value === '') {
            showError(input, 'Vui lòng nhập tên đăng nhập!');
            valid = false;
        } else {
            clearError(input);
        }
    } else if (name === 'email') {
        if (value === '') {
            showError(input, 'Vui lòng nhập email!');
            valid = false;
        } else {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                showError(input, 'Email không hợp lệ (VD: example@mail.com)!');
                valid = false;
            } else {
                clearError(input);
            }
        }
    } else if (name === 'password') {
        if (value === '') {
            showError(input, 'Vui lòng nhập mật khẩu!');
            valid = false;
        } else if (value.length < 6) {
            showError(input, 'Mật khẩu phải có ít nhất 6 ký tự!');
            valid = false;
        } else {
            clearError(input);
        }
    }

    return valid;
}

function validateRegisterForm(form) {
    const inputs = form.querySelectorAll('input');
    let isValid = true;

    inputs.forEach(input => {
        if (!validateRegisterField(input)) {
            isValid = false;
        }
    });

    return isValid;
}