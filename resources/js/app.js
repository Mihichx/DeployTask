const images = import.meta.glob('../img/**', { query: '?url', import: 'default' });
window.__viteImages = images;
