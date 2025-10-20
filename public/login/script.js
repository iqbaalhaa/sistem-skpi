// Eco Wellness Login Form JavaScript
class EcoWellnessLoginForm {
    constructor() {
        // Support either a login form (id=loginForm) or a register form (id=registerForm)
        this.form = document.getElementById('loginForm') || document.getElementById('registerForm');

        // Common fields: there may be 'login' (login page) or 'username'/'email' (register page)
        this.loginInput = document.getElementById('login') || document.getElementById('username') || document.getElementById('email');

        // Password fields: some pages have one, register has password and password_confirmation
        this.passwordInput = document.getElementById('password');
        this.confirmPasswordInput = document.getElementById('password_confirmation');

        // There may be one or more password toggle buttons; collect them
        this.passwordToggles = Array.from(document.querySelectorAll('.nature-toggle')) || [];

        this.submitButton = this.form ? this.form.querySelector('.harmony-button') : null;
        this.successMessage = document.getElementById('successMessage');
        this.socialButtons = document.querySelectorAll('.earth-social');

        try {
            this.init();
        } catch (err) {
            // If initialization fails, surface an error to the console but don't break the page
            console.error('EcoWellnessLoginForm init error:', err);
        }
    }
    
    init() {
        console.log('EcoWellnessLoginForm: initializing. form=', this.form ? this.form.id : null);
        this.bindEvents();
        this.setupPasswordToggles();
        this.setupSocialButtons();
        this.setupWellnessEffects();
    }
    
    bindEvents() {
        if (!this.form) {
            console.warn('EcoWellnessLoginForm: no form found to bind events');
            return;
        }

        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        if (this.loginInput) {
            this.loginInput.addEventListener('blur', () => this.validateLogin());
            this.loginInput.addEventListener('input', () => this.clearError(this.loginInput.id || 'login'));
            // Add placeholder for label animations
            this.loginInput.setAttribute('placeholder', ' ');
            console.log('EcoWellnessLoginForm: bound login input=', this.loginInput.id);
        }

        if (this.passwordInput) {
            this.passwordInput.addEventListener('blur', () => this.validatePassword());
            this.passwordInput.addEventListener('input', () => this.clearError('password'));
            this.passwordInput.setAttribute('placeholder', ' ');
            console.log('EcoWellnessLoginForm: bound password input');
        }

        if (this.confirmPasswordInput) {
            this.confirmPasswordInput.addEventListener('input', () => this.clearError('password_confirmation'));
            this.confirmPasswordInput.setAttribute('placeholder', ' ');
            console.log('EcoWellnessLoginForm: bound confirm password input');
        }
    }
    
    setupPasswordToggles() {
        // Wire any toggle buttons found to their corresponding password input siblings
        if (!this.passwordToggles.length) {
            console.log('EcoWellnessLoginForm: no password toggles found');
            return;
        }

        this.passwordToggles.forEach(toggle => {
            try {
                toggle.addEventListener('click', (e) => {
                // Prefer toggling the adjacent input inside the same organic-field
                const field = toggle.closest('.organic-field');
                const input = field ? field.querySelector('input[type="password"], input[type="text"]') : null;

                if (!input) return;

                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';

                // Toggle visible/hidden eye icons if present
                toggle.classList.toggle('toggle-visible', isPassword);

                // keep focus on the input after toggle
                input.focus();
                });
            } catch (err) {
                console.warn('EcoWellnessLoginForm: error wiring password toggle', err);
            }
        });
    }
    
    setupSocialButtons() {
        if (!this.socialButtons || !this.socialButtons.length) return;
        this.socialButtons.forEach(button => {
            try {
                button.addEventListener('click', (e) => {
                    const span = button.querySelector('span');
                    const provider = span ? span.textContent.trim() : 'social';
                    this.handleSocialLogin(provider, button);
                });
            } catch (err) {
                console.warn('EcoWellnessLoginForm: error binding social button', err);
            }
        });
    }
    
    setupWellnessEffects() {
        // Add mindful focus effects
        // Only attach effects to inputs that exist
        [this.loginInput, this.passwordInput, this.confirmPasswordInput].forEach(input => {
            if (!input) return;
            input.addEventListener('focus', (e) => {
                const field = e.target.closest('.organic-field');
                if (field) this.triggerMindfulEffect(field);
            });

            input.addEventListener('blur', (e) => {
                const field = e.target.closest('.organic-field');
                if (field) this.resetMindfulEffect(field);
            });
        });
    }
    
    triggerMindfulEffect(field) {
        // Add gentle breathing effect to the field
        if (!field) return;
        const fieldNature = field.querySelector('.field-nature');
        if (!fieldNature) return;
        fieldNature.style.animation = 'gentleBreath 3s ease-in-out infinite';
    }
    
    resetMindfulEffect(field) {
        // Remove breathing effect
        if (!field) return;
        const fieldNature = field.querySelector('.field-nature');
        if (!fieldNature) return;
        fieldNature.style.animation = '';
    }
    
    validateLogin() {
        if (!this.loginInput) return true;

        const login = this.loginInput.value.trim();

        if (!login) {
            // choose appropriate error id depending on available field
            const fieldId = this.loginInput.id || 'login';
            this.showError(fieldId, 'Email / Username tidak boleh kosong');
            return false;
        }

        this.clearError(this.loginInput.id || 'login');
        return true;
    }
    
    validatePassword() {
        if (!this.passwordInput) return true;

        const password = this.passwordInput.value;

        if (!password) {
            this.showError('password', 'Password tidak boleh kosong');
            return false;
        }

        this.clearError('password');
        return true;
    }
    
    showError(field, message) {
        const fieldEl = document.getElementById(field);
        const organicField = fieldEl ? fieldEl.closest('.organic-field') : null;
        const errorElement = document.getElementById(`${field}Error`) || document.getElementById(`${field}_error`);

        if (organicField) organicField.classList.add('error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
    }
    
    clearError(field) {
        const fieldEl = document.getElementById(field);
        const organicField = fieldEl ? fieldEl.closest('.organic-field') : null;
        const errorElement = document.getElementById(`${field}Error`) || document.getElementById(`${field}_error`);

        if (organicField) organicField.classList.remove('error');
        if (errorElement) {
            errorElement.classList.remove('show');
            setTimeout(() => {
                errorElement.textContent = '';
            }, 300);
        }
    }
    
    handleSubmit(e) {
        const isLoginValid = this.validateLogin();
        const isPasswordValid = this.validatePassword();
        
        if (!isLoginValid || !isPasswordValid) {
            e.preventDefault();
            return;
        }
        
        this.setLoading(true);
    }
    
    async handleSocialLogin(provider, button) {
        console.log(`Connecting with ${provider} mindfully...`);
        
        // Organic loading state
        const originalHTML = button.innerHTML;
        button.style.pointerEvents = 'none';
        button.style.opacity = '0.7';
        
        const loadingHTML = `
            <div class="social-earth"></div>
            <div style="display: flex; gap: 4px;">
                <div style="width: 6px; height: 6px; background: #4caf50; border-radius: 50%; animation: organicGrow 1.5s ease-in-out infinite;"></div>
                <div style="width: 6px; height: 6px; background: #4caf50; border-radius: 50%; animation: organicGrow 1.5s ease-in-out infinite; animation-delay: 0.2s;"></div>
                <div style="width: 6px; height: 6px; background: #4caf50; border-radius: 50%; animation: organicGrow 1.5s ease-in-out infinite; animation-delay: 0.4s;"></div>
            </div>
            <span>Connecting...</span>
            <div class="social-glow"></div>
        `;
        
        button.innerHTML = loadingHTML;
        
        try {
            await new Promise(resolve => setTimeout(resolve, 2200));
            console.log(`Redirecting to ${provider} wellness connection...`);
            // window.location.href = `/auth/${provider.toLowerCase()}`;
        } catch (error) {
            console.error(`${provider} connection was interrupted: ${error.message}`);
        } finally {
            button.style.pointerEvents = 'auto';
            button.style.opacity = '1';
            button.innerHTML = originalHTML;
        }
    }
    
    setLoading(loading) {
        if (this.submitButton) {
            this.submitButton.classList.toggle('loading', loading);
            this.submitButton.disabled = loading;
        }

        // Disable social buttons during mindful processing
        this.socialButtons.forEach(button => {
            button.style.pointerEvents = loading ? 'none' : 'auto';
            button.style.opacity = loading ? '0.6' : '1';
        });
    }
    
    showHarmonySuccess() {
        // Hide form with organic transition
        this.form.style.transform = 'scale(0.95)';
        this.form.style.opacity = '0';
        
        setTimeout(() => {
            this.form.style.display = 'none';
            document.querySelector('.natural-social').style.display = 'none';
            document.querySelector('.nurture-signup').style.display = 'none';
            document.querySelector('.balance-divider').style.display = 'none';
            
            // Show harmony success
            this.successMessage.classList.add('show');
            
        }, 300);
        
        // Redirect after harmony established
        setTimeout(() => {
            console.log('Welcome to your wellness sanctuary...');
            // window.location.href = '/wellness-dashboard';
        }, 3500);
    }
}

// Add gentle breathing animation to CSS dynamically
if (!document.querySelector('#wellness-keyframes')) {
    const style = document.createElement('style');
    style.id = 'wellness-keyframes';
    style.textContent = `
        @keyframes gentleBreath {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.01); }
        }
    `;
    document.head.appendChild(style);
}

// Initialize the wellness form when DOM is loaded (handle both cases: already ready or not)
function initEcoWellness() {
    try {
        new EcoWellnessLoginForm();
    } catch (err) {
        console.error('Failed to initialize EcoWellnessLoginForm:', err);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initEcoWellness);
} else {
    // DOM already parsed
    initEcoWellness();
}