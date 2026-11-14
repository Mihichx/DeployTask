document.documentElement.classList.add('no-transitions');
if (localStorage.getItem('deploytask_focus_mode') === '1') {
    document.documentElement.classList.add('is-focus-mode');
}
window.addEventListener('DOMContentLoaded', () => {
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            document.documentElement.classList.remove('no-transitions');
        });
    });
});
