(() => {
    const ready = (cb) => {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', cb);
        } else {
            cb();
        }
    };

    const dispatchCloseToLivewire = () => {
        if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
            window.Livewire.dispatch('admin-calendar-close');
        } else if (window.Livewire && typeof window.Livewire.emit === 'function') {
            window.Livewire.emit('admin-calendar-close');
        }
    };

    const initModalBridge = () => {
        const modal = document.querySelector('[data-calendar-modal]');
        if (!modal) {
            return;
        }

        const lockBody = (open) => {
            document.body.classList.toggle('overflow-hidden', open);
        };

        window.addEventListener('admin-calendar:modal-visible', (event) => {
            const detail = event.detail || {};
            lockBody(Boolean(detail.open));
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                dispatchCloseToLivewire();
            }
        });
    };

    const initDayHints = () => {
        const dayCards = document.querySelectorAll('[data-calendar-day]');
        dayCards.forEach((card) => {
            const hasReservations = card.dataset.hasReservations === 'true';
            card.title = hasReservations ? 'Ver reservas del día' : 'Sin reservas registradas';
        });
    };

    ready(() => {
        initModalBridge();
        initDayHints();
        document.addEventListener('livewire:navigated', initDayHints);
        document.addEventListener('livewire:load', initDayHints);
    });
})();
