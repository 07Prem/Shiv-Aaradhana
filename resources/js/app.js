import './bootstrap';
import Alpine from 'alpinejs';
import gsap from 'gsap';

window.Alpine = Alpine;
window.gsap = gsap;

// Global inquiry modal state helper
Alpine.data('quoteModal', () => ({
    open: false,
    productId: '',
    productName: '',
    quantity: '',
    country: '',
    name: '',
    email: '',
    phone: '',
    message: '',
    packaging: '',
    port: '',
    isSubmitting: false,
    successMessage: '',
    errorMessage: '',

    triggerQuote(id = '', name = '') {
        this.productId = id;
        this.productName = name;
        this.open = true;
        this.successMessage = '';
        this.errorMessage = '';
    },

    closeModal() {
        this.open = false;
    }
}));

Alpine.start();

// Selective entrance animations with GSAP
document.addEventListener('DOMContentLoaded', () => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    if (document.querySelector('.hero-animate')) {
        gsap.from('.hero-animate', {
            opacity: 0,
            y: 24,
            duration: 0.9,
            stagger: 0.15,
            ease: 'power3.out'
        });
    }
});
