import './focus-mode';
import kanbanBoard from './alpine/kanban-board';
import dropdown from './alpine/dropdown';
import phraseRotator from './alpine/phrase-rotator';

window.__viteImages = import.meta.glob('../img/**', { query: '?url', import: 'default' });

document.addEventListener('alpine:init', () => {
    Alpine.data('kanbanBoard', kanbanBoard);
    Alpine.data('dropdown', dropdown);
    Alpine.data('phraseRotator', phraseRotator);
});
