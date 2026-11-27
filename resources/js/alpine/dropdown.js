export default (direction = 'down') => ({
    open: false,

    toggle() {
        this.open = !this.open;
    },

    close() {
        this.open = false;
    },

    menu: {
        'x-cloak': true,
        'x-show'() { return this.open },
        '@click.outside'() { this.close() },
        '@keydown.escape.window'() { this.close() },
        'x-transition:enter': 'transition ease-out duration-150',
        'x-transition:enter-start'() {
            return `opacity-0 scale-95 ${direction === 'up' ? 'translate-y-1' : '-translate-y-1'}`;
        },
        'x-transition:enter-end': 'opacity-100 scale-100 translate-y-0',
        'x-transition:leave': 'transition ease-in duration-100',
        'x-transition:leave-start': 'opacity-100 scale-100 translate-y-0',
        'x-transition:leave-end'() {
            return `opacity-0 scale-95 ${direction === 'up' ? 'translate-y-1' : '-translate-y-1'}`;
        },
    }
});
