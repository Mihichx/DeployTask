export default (phrases = []) => ({
    phrases,
    currentIndex: 0,
    visible: false,
    interval: null,

    init() {
        if (!this.phrases.length) return;

        this.currentIndex = Math.floor(Math.random() * this.phrases.length);
        setTimeout(() => { this.visible = true; }, 100);

        if (this.phrases.length > 1) {
            this.interval = setInterval(() => {
                this.visible = false;
                setTimeout(() => {
                    this.currentIndex = (this.currentIndex + 1) % this.phrases.length;
                    this.visible = true;
                }, 400);
            }, 4500);
        }
    },

    destroy() {
        if (this.interval) clearInterval(this.interval);
    }
});
