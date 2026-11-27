export default () => ({
    focusMode: localStorage.getItem('deploytask_focus_mode') === '1',

    toggleFocus(val = null) {
        this.focusMode = val !== null ? val : !this.focusMode;
        localStorage.setItem('deploytask_focus_mode', this.focusMode ? '1' : '0');
        document.documentElement.classList.toggle('is-focus-mode', this.focusMode);
    },

    handleKeydown(e) {
        if (e.key === 'Escape' && this.focusMode) {
            return this.toggleFocus(false);
        }

        const isInput = ['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName) || e.target.isContentEditable;
        if (isInput || e.ctrlKey || e.metaKey || e.altKey) return;

        if (e.code === 'KeyF') this.toggleFocus();
    },

    sidebarTransition: {
        'x-show'() { return !this.focusMode },
        'x-transition:enter': 'transition ease-out duration-200',
        'x-transition:enter-start': '-translate-x-full opacity-0',
        'x-transition:enter-end': 'translate-x-0 opacity-100',
        'x-transition:leave': 'transition ease-in duration-150',
        'x-transition:leave-start': 'translate-x-0 opacity-100',
        'x-transition:leave-end': '-translate-x-full opacity-0',
    }
});
