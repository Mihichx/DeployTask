// Снимаем блокировку анимаций после отрисовки первого кадра
window.addEventListener('DOMContentLoaded', () => {
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            document.documentElement.classList.remove('no-transitions');
        });
    });
});

// Восстанавливаем класс при SPA-переходах (wire:navigate)
document.addEventListener('livewire:navigated', () => {
    const isFocus = localStorage.getItem('deploytask_focus_mode') === '1';
    document.documentElement.classList.toggle('is-focus-mode', isFocus);
});

document.addEventListener('alpine:init', () => {
    // 1. Глобальное хранилище состояния фокуса
    Alpine.store('focus', {
        active: localStorage.getItem('deploytask_focus_mode') === '1',

        toggle(val = null) {
            this.active = val !== null ? val : !this.active;
            localStorage.setItem('deploytask_focus_mode', this.active ? '1' : '0');
            document.documentElement.classList.toggle('is-focus-mode', this.active);
        },

        handleKeydown(e) {
            if (e.key === 'Escape' && this.active) {
                this.toggle(false);
                return;
            }

            const isTyping = ['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)
                || e.target.isContentEditable
                || e.ctrlKey || e.metaKey || e.altKey;

            if (!isTyping && e.code === 'KeyF') {
                this.toggle();
            }
        }
    });

    // 2. Готовые анимации для сайдбара и шапки
    Alpine.data('focusLayout', () => ({
        sidebarTransition: {
            'x-show'() { return !this.$store.focus.active },
            'x-transition:enter': 'transition ease-out duration-200',
            'x-transition:enter-start': '-translate-x-full opacity-0',
            'x-transition:enter-end': 'translate-x-0 opacity-100',
            'x-transition:leave': 'transition ease-in duration-150',
            'x-transition:leave-start': 'translate-x-0 opacity-100',
            'x-transition:leave-end': '-translate-x-full opacity-0',
        },
        headerTransition: {
            'x-show'() { return !this.$store.focus.active },
            'x-transition:enter': 'transition ease-out duration-200',
            'x-transition:enter-start': 'opacity-0 -translate-y-2',
            'x-transition:enter-end': 'opacity-100 translate-y-0',
            'x-transition:leave': 'transition ease-in duration-150',
            'x-transition:leave-start': 'opacity-100 translate-y-0',
            'x-transition:leave-end': 'opacity-0 -translate-y-2',
        }
    }));
});
