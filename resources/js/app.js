import './bootstrap';
import Alpine from 'alpinejs';
import gsap from 'gsap';

window.Alpine = Alpine;
window.gsap = gsap;

// Global RFQ & Quotation State Manager
Alpine.data('rfqManager', () => ({
    open: false,
    items: [],
    count: 0,
    isSubmitting: false,
    toastMessage: '',
    toastTimeout: null,
    successData: null,
    errorMessage: '',

    init() {
        this.fetchItems();
    },

    showToast(msg) {
        this.toastMessage = msg;
        clearTimeout(this.toastTimeout);
        this.toastTimeout = setTimeout(() => {
            this.toastMessage = '';
        }, 4000);
    },

    async fetchItems() {
        try {
            const res = await fetch('/rfq/items');
            if (res.ok) {
                const data = await res.json();
                this.items = data.items || [];
                this.count = data.count || 0;
            }
        } catch (e) {
            // Silently fall back to empty
        }
    },

    async addProduct(id, name = '', qty = '', notes = '', openModalAfter = false) {
        if (!id) {
            this.openModal();
            return;
        }

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                || document.querySelector('input[name="_token"]')?.value;

            const res = await fetch('/rfq/items', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    product_id: id,
                    quantity: qty || '1 x 20ft FCL',
                    notes: notes || ''
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                this.items = data.items || [];
                this.count = data.count || 0;
                this.showToast(data.message || `Added ${name} to quotation list.`);
                if (openModalAfter) {
                    this.openModal();
                }
            } else {
                this.showToast(data.message || 'Could not add product.');
            }
        } catch (e) {
            this.showToast('Network error adding product.');
        }
    },

    triggerQuote(id = '', name = '') {
        if (id) {
            this.addProduct(id, name, '1 x 20ft FCL', '', true);
        } else {
            this.openModal();
        }
    },

    async updateQty(productId, qty) {
        if (!qty) return;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || document.querySelector('input[name="_token"]')?.value;

            const res = await fetch(`/rfq/items/${productId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ quantity: qty })
            });
            const data = await res.json();
            if (res.ok && data.success) {
                this.items = data.items;
                this.count = data.count;
            }
        } catch (e) {}
    },

    async updateNotes(productId, notes) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || document.querySelector('input[name="_token"]')?.value;

            const current = this.items.find(i => i.product_id == productId);
            const res = await fetch(`/rfq/items/${productId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ 
                    quantity: current ? current.quantity : '1 x 20ft FCL', 
                    notes: notes 
                })
            });
            const data = await res.json();
            if (res.ok && data.success) {
                this.items = data.items;
                this.count = data.count;
            }
        } catch (e) {}
    },

    async removeItem(productId) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || document.querySelector('input[name="_token"]')?.value;

            const res = await fetch(`/rfq/items/${productId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            });
            const data = await res.json();
            if (res.ok && data.success) {
                this.items = data.items;
                this.count = data.count;
                this.showToast(data.message || 'Removed product.');
            }
        } catch (e) {
            this.showToast('Could not remove product.');
        }
    },

    async clearAll() {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || document.querySelector('input[name="_token"]')?.value;

            const res = await fetch('/rfq/items', {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            });
            const data = await res.json();
            if (res.ok && data.success) {
                this.items = [];
                this.count = 0;
                this.showToast('Quotation list cleared.');
            }
        } catch (e) {}
    },

    openModal() {
        this.open = true;
        this.errorMessage = '';
        this.successData = null;
    },

    closeModal() {
        this.open = false;
    },

    async submitRfq(formElement) {
        if (this.isSubmitting) return;

        if (this.items.length === 0) {
            this.errorMessage = 'Please add at least one product or commodity to your quotation list before submitting.';
            return;
        }

        this.isSubmitting = true;
        this.errorMessage = '';

        try {
            const formData = new FormData(formElement);
            // Append items array
            this.items.forEach((item, index) => {
                formData.set(`items[${index}][product_id]`, item.product_id);
                formData.set(`items[${index}][quantity]`, item.quantity);
                if (item.notes) {
                    formData.set(`items[${index}][notes]`, item.notes);
                }
            });

            const res = await fetch(formElement.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await res.json();

            if (res.ok && data.success) {
                this.items = [];
                this.count = 0;
                this.successData = {
                    ref: data.reference_no,
                    message: data.message
                };
                formElement.reset();
            } else {
                if (data.errors) {
                    const firstErr = Object.values(data.errors)[0];
                    this.errorMessage = Array.isArray(firstErr) ? firstErr[0] : firstErr;
                } else {
                    this.errorMessage = data.message || 'Validation failed. Please verify required fields.';
                }
            }
        } catch (e) {
            this.errorMessage = 'A network connection error occurred. Please verify your connection and try again.';
        } finally {
            this.isSubmitting = false;
        }
    }
}));

// Backward compatibility alias
Alpine.data('quoteModal', () => Alpine.data('rfqManager')());

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
