export default () => ({
    draggingTaskId: null,
    draggingFromStatus: null,
    dragOverStatus: null,

    startDrag(taskId, el) {
        this.draggingTaskId = taskId;
        this.draggingFromStatus = Number(el.closest('[data-column-body]').dataset.columnBody);
    },

    enterColumn(statusId) {
        if (this.draggingFromStatus !== statusId) {
            this.dragOverStatus = statusId;
        }
    },

    leaveColumn(statusId) {
        if (this.dragOverStatus === statusId) {
            this.dragOverStatus = null;
        }
    },

        dropTask(newStatusId) {
        const taskId = this.draggingTaskId;
        const oldStatusId = this.draggingFromStatus;

        if (!taskId || !oldStatusId || oldStatusId === newStatusId) {
            return this.resetDrag();
        }

        const card = this.$root.querySelector(`[data-task-id='${taskId}']`);
        const oldBody = this.$root.querySelector(`[data-column-body='${oldStatusId}']`);
        const newBody = this.$root.querySelector(`[data-column-body='${newStatusId}']`);

        if (card && oldBody && newBody) {
            // Вставляем карточку сразу в нужную позицию по двойному приоритету
            this.insertSorted(newBody, card);

            this.updateCounter(oldStatusId, -1);
            this.updateCounter(newStatusId, 1);
            this.toggleEmptyState(oldBody);
            this.toggleEmptyState(newBody);

            this.$wire.moveTask(taskId, newStatusId);
        }

        this.resetDrag();
    },

    insertSorted(columnBody, card) {
        const cardMine = Number(card.dataset.mine || 0);
        const cardPriority = Number(card.dataset.priority || 0);
        const existingCards = Array.from(columnBody.querySelectorAll('[data-task-id]'));

        // Ищем первую карточку, которая должна стоять НИЖЕ переносимой
        const insertBeforeEl = existingCards.find((other) => {
            const otherMine = Number(other.dataset.mine || 0);
            const otherPriority = Number(other.dataset.priority || 0);

            // Если наша задача "моя", а соседняя "чужая" — ставим перед ней
            if (cardMine !== otherMine) {
                return cardMine > otherMine;
            }

            // Если обе задачи в одной группе ("мои" или "чужие") — сравниваем приоритет
            return cardPriority >= otherPriority;
        });

        if (insertBeforeEl) {
            columnBody.insertBefore(card, insertBeforeEl);
        } else {
            // Если подходящей карточки ниже нет — вставляем перед заглушкой [data-empty] (в самый низ списка)
            const emptyEl = columnBody.querySelector('[data-empty]');
            columnBody.insertBefore(card, emptyEl);
        }
    },

    updateCounter(statusId, delta) {
        const el = this.$root.querySelector(`[data-count='${statusId}']`);
        if (el) el.textContent = Math.max(0, parseInt(el.textContent) + delta);
    },

    toggleEmptyState(columnBody) {
        const emptyEl = columnBody.querySelector('[data-empty]');
        const hasCards = columnBody.querySelectorAll('[data-task-id]').length > 0;
        if (emptyEl) emptyEl.classList.toggle('hidden', hasCards);
    },

    resetDrag() {
        this.draggingTaskId = null;
        this.draggingFromStatus = null;
        this.dragOverStatus = null;
    },

    headerFocus: {
        'x-show'() { return !this.focusMode },
        'x-transition:enter': 'transition ease-out duration-200',
        'x-transition:enter-start': 'opacity-0 -translate-y-2',
        'x-transition:enter-end': 'opacity-100 translate-y-0',
        'x-transition:leave': 'transition ease-in duration-150',
        'x-transition:leave-start': 'opacity-100 translate-y-0',
        'x-transition:leave-end': 'opacity-0 -translate-y-2',
    },
});
