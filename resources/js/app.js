import Alpine from 'alpinejs';
import axios from 'axios';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
window.axios = axios;
Alpine.plugin(collapse);

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content;

document.addEventListener('alpine:init', () => {

    // ─── Header Scroll Animation ──────────────────────────
    Alpine.data('headerScroll', () => ({
        scrolled: false,
        ticking: false,
        mobileOpen: false,
        init() {
            this.scrolled = window.scrollY > 80;
            this.onScroll = () => {
                if (!this.ticking) {
                    window.requestAnimationFrame(() => {
                        const y = window.scrollY;
                        if (y > 100 && !this.scrolled) {
                            this.scrolled = true;
                        } else if (y < 30 && this.scrolled) {
                            this.scrolled = false;
                        }
                        this.ticking = false;
                    });
                    this.ticking = true;
                }
            };
            window.addEventListener('scroll', this.onScroll, { passive: true });
        },
    }));

    // ─── Hero Slider ────────────────────────────────────────
    Alpine.data('slider', (slides, intervalMs = 5000, barOpacity = 0.4, barHeight = 20) => ({
        slides: slides || [],
        current: 0,
        timerId: null,
        barHeight: barHeight,

        init() {
            if (this.slides.length > 1) this.startAutoplay();
        },
        startAutoplay() {
            this.stopAutoplay();
            this.timerId = setInterval(() => this.next(), intervalMs);
        },
        stopAutoplay() {
            if (this.timerId) { clearInterval(this.timerId); this.timerId = null; }
        },
        next() {
            this.current = (this.current + 1) % this.slides.length;
            this.restartAutoplay();
        },
        prev() {
            this.current = (this.current - 1 + this.slides.length) % this.slides.length;
            this.restartAutoplay();
        },
        restartAutoplay() {
            if (this.slides.length > 1) {
                this.stopAutoplay();
                this.timerId = setInterval(() => this.next(), intervalMs);
            }
        },
        /** Return font size in px for a slide title */
        titleSize(slide) {
            if (slide && slide.font_size) return Math.max(8, Math.min(48, parseFloat(slide.font_size)));
            return Math.max(12, barHeight * 0.9);
        },
        textSize(slide) {
            return Math.max(10, this.titleSize(slide) - 4);
        },
        /** Font weight/italic for title */
        titleWeight(slide) { return (slide && slide.title_bold) ? 'bold' : 'normal'; },
        titleStyle(slide) { return (slide && slide.title_italic) ? 'italic' : 'normal'; },
        /** Font weight/italic for text */
        textWeight(slide) { return (slide && slide.text_bold) ? 'bold' : 'normal'; },
        textStyle(slide) { return (slide && slide.text_italic) ? 'italic' : 'normal'; },
        /** Parse hex color (#fff or #ffffff) to RGB array */
        hexToRgb(hex) {
            if (!hex) return [255, 255, 255];
            hex = hex.replace('#', '');
            if (hex.length === 3) hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
            const num = parseInt(hex, 16);
            if (isNaN(num)) return [255, 255, 255];
            return [(num >> 16) & 255, (num >> 8) & 255, num & 255];
        },
        /** Build inline style for the info bar */
        barStyle(index) {
            const slide = this.slides[index] || {};
            const rgb = this.hexToRgb(slide.bar_color || '#ffffff');
            return 'background-color: rgba(' + rgb.join(',') + ',' + barOpacity + ');';
        },
        startAutoplay() {
            this.stopAutoplay();
            this.timerId = setInterval(() => this.next(), intervalMs);
        },
        stopAutoplay() {
            if (this.timerId) { clearInterval(this.timerId); this.timerId = null; }
        },
        next() {
            this.current = (this.current + 1) % this.slides.length;
            this.restartAutoplay();
        },
        prev() {
            this.current = (this.current - 1 + this.slides.length) % this.slides.length;
            this.restartAutoplay();
        },
        restartAutoplay() {
            if (this.slides.length > 1) {
                this.stopAutoplay();
                this.timerId = setInterval(() => this.next(), intervalMs);
            }
        },
    }));

    // ─── TypesSlider (TypesSkinali carousel) ──────────────
    Alpine.data('typesSlider', (slides) => ({
        slides: slides || [],
        current: 2,

        init() {},
        next() {
            if (this.current < this.slides.length - 1) this.current++;
        },
        prev() {
            if (this.current > 0) this.current--;
        },
        goTo(i) {
            this.current = i;
        },
    }));

    // ─── LookApp (Примерка kitchen interactive) ─────
    Alpine.data('lookApp', () => ({
        colors: [],
        catalog: [],
        topColor: 'black',
        bottomColor: 'black',
        selectedImage: '/images/mainprod/skinali.jpg',
        catalogOpen: false,

        colorHex: {
            red: '#e74c3c', orange: '#f39c12', yellow: '#f1c40f', green: '#27ae60',
            blue: '#2980b9', darkblue: '#1a3a5c', purple: '#8e44ad', pink: '#e91e9b',
            brown: '#795548', beige: '#f5e6d3', white: '#f0f0f0', gray: '#95a5a6', black: '#2c3e50'
        },

        init() {
            const el = this.$el;
            this.colors = el.dataset.colors ? JSON.parse(el.dataset.colors) : [];
            this.catalog = el.dataset.catalog ? JSON.parse(el.dataset.catalog) : [];
        },

        hexColor(name) {
            return this.colorHex[name] || '#333';
        },

        topImage() {
            return '/images/kitchen/kitchen1-' + this.topColor + '.png';
        },

        bottomImage() {
            return '/images/kitchen/kitchen2-' + this.bottomColor + '.png';
        },

        selectImage(url) {
            this.selectedImage = url;
            this.catalogOpen = false;
        },
    }));

    // ─── Testimonial Slider ─────────────────────────────────
    Alpine.data('testimonials', (items) => ({
        items: items || [],
        current: 0,
        next() { this.current = (this.current + 1) % this.items.length; },
        prev() { this.current = (this.current - 1 + this.items.length) % this.items.length; },
    }));

    // ─── Tabs ───────────────────────────────────────────────
    Alpine.data('tabs', (defaultTab = 0) => ({
        active: defaultTab,
    }));

    // ─── Accordion ──────────────────────────────────────────
    Alpine.data('accordion', () => ({
        openIndex: null,
        toggle(i) { this.openIndex = this.openIndex === i ? null : i; },
    }));

    // ─── Catalog Grid ───────────────────────────────────────
    Alpine.data('catalogGrid', () => ({
        images: [], page: 1, hasMore: true, loading: false,
        filters: { category: '', color: '', search: '' },

        init() {
            this.loadImages();
            window.addEventListener('filter-change', (e) => {
                Object.assign(this.filters, e.detail);
                this.page = 1; this.images = []; this.hasMore = true;
                this.loadImages();
            });
        },

        async loadImages() {
            if (!this.hasMore) return;
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page: this.page,
                    category: String(this.filters.category || ''),
                    color: String(this.filters.color || ''),
                    search: this.filters.search || '',
                });
                const { data } = await axios.get(`/api/catalog?${params}`);
                this.images = [...this.images, ...data.data];
                this.hasMore = data.current_page < data.last_page;
                this.page++;
            } catch (e) { console.error('Failed to load catalog:', e); }
            finally { this.loading = false; }
        },

        loadMore() { if (!this.loading && this.hasMore) this.loadImages(); },
    }));

    // ─── Works Grid ─────────────────────────────────────────
    Alpine.data('worksGrid', () => ({
        works: [], page: 1, hasMore: true, loading: false, category: '',

        init() {
            this.loadWorks();
            window.addEventListener('filter-works', (e) => {
                this.category = e.detail.category || '';
                this.page = 1; this.works = []; this.loadWorks();
            });
        },

        async loadWorks() {
            this.loading = true;
            try {
                const params = new URLSearchParams({ page: this.page });
                if (this.category) params.set('category', this.category);
                const { data } = await axios.get(`/api/works?${params}`);
                this.works = [...this.works, ...data.data];
                this.hasMore = data.current_page < data.last_page;
                this.page++;
            } catch (e) { console.error('Failed to load works:', e); }
            finally { this.loading = false; }
        },

        loadMore() { if (!this.loading && this.hasMore) this.loadWorks(); },
    }));

    // ─── Favorites ──────────────────────────────────────────
    Alpine.data('favorites', () => ({
        items: [], sessionId: '',

        init() {
            this.sessionId = this.getSessionId();
            this.load();
            window.addEventListener('toggle-favorite', (e) => this.toggle(e.detail.imageId));
        },

        getSessionId() {
            let sid = localStorage.getItem('primerka_session');
            if (!sid) { sid = 'sid_' + Math.random().toString(36).substr(2, 9); localStorage.setItem('primerka_session', sid); }
            return sid;
        },

        async load() {
            try {
                const { data } = await axios.get('/api/favorites', { params: { session_id: this.sessionId } });
                this.items = data;
            } catch (e) { console.error(e); }
        },

        async toggle(imageId) {
            const exists = this.items.find(i => i.id === imageId);
            try {
                if (exists) {
                    await axios.delete(`/api/favorites/${imageId}`, { params: { session_id: this.sessionId } });
                    this.items = this.items.filter(i => i.id !== imageId);
                } else {
                    await axios.post('/api/favorites', { image_id: imageId, session_id: this.sessionId });
                    await this.load();
                }
            } catch (e) { console.error(e); }
        },

        isFavorite(imageId) { return this.items.some(i => i.id === imageId); },
    }));

    // ─── Primerka ───────────────────────────────────────────
    Alpine.data('primerka', () => ({
        categories: [], colors: [], images: [],
        selectedCategory: null, selectedImage: null,
        topColor: null, bottomColor: null,
        selectedTopColor: '#E8D5B7', selectedBottomColor: '#E8D5B7',
        orderForm: { name: '', phone: '', message: '', article: '' },

        async init() {
            const [catRes, colRes] = await Promise.all([
                axios.get('/api/catalog/categories'),
                axios.get('/api/catalog/colors'),
            ]);
            this.categories = catRes.data;
            this.colors = colRes.data;
            if (this.colors.length > 0) {
                const dc = this.colors.find(c => c.slug === 'beige') || this.colors[0];
                this.topColor = dc.id; this.bottomColor = dc.id;
                this.selectedTopColor = dc.hex; this.selectedBottomColor = dc.hex;
            }
            if (this.categories.length > 0) {
                this.selectedCategory = this.categories[0].id;
                await this.loadImages();
            }
        },

        async selectCategory(catId) { this.selectedCategory = catId; this.images = []; this.selectedImage = null; await this.loadImages(); },
        async loadImages() {
            if (!this.selectedCategory) return;
            try {
                const { data } = await axios.get('/api/catalog', { params: { category: this.selectedCategory, limit: 50 } });
                this.images = data.data || [];
            } catch (e) { console.error(e); }
        },

        selectTopColor(colorId) { const c = this.colors.find(x => x.id === colorId); if (c) { this.topColor = colorId; this.selectedTopColor = c.hex; } },
        selectBottomColor(colorId) { const c = this.colors.find(x => x.id === colorId); if (c) { this.bottomColor = colorId; this.selectedBottomColor = c.hex; } },
        selectImage(img) { this.selectedImage = img; },

        addToFavorites() { if (!this.selectedImage) return; window.dispatchEvent(new CustomEvent('toggle-favorite', { detail: { imageId: this.selectedImage.id } })); },
        openOrderForm() { if (!this.selectedImage) return; this.orderForm.article = this.selectedImage.title || ''; window.dispatchEvent(new CustomEvent('open-modal', { detail: 'order-form' })); },

        async submitOrder() {
            try {
                await axios.post('/api/order', {
                    name: this.orderForm.name, phone: this.orderForm.phone,
                    message: this.orderForm.message, source: 'primerka',
                    article_ids: this.orderForm.article ? [this.orderForm.article] : [],
                    facade_top_color: this.selectedTopColor, facade_bottom_color: this.selectedBottomColor,
                });
                this.orderForm = { name: '', phone: '', message: '', article: '' };
                window.dispatchEvent(new CustomEvent('close-modal'));
                alert('Спасибо! Заказ принят. Мы перезвоним вам в течение 2 часов.');
            } catch (e) {
                alert(e.response?.data?.message || 'Ошибка при отправке.');
            }
        },
    }));

    // ─── Lightbox ───────────────────────────────────────────
    Alpine.data('lightbox', () => ({
        open: false, currentImage: null,
        init() { window.addEventListener('open-lightbox', (e) => { this.currentImage = e.detail; this.open = true; }); },
        close() { this.open = false; this.currentImage = null; },
    }));
});

Alpine.start();
