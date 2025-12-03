(() => {
    const {
        shouldOpenCreateModal = false,
        serverEditId = null,
        shouldOpenBloqueoModal = false,
        serverBloqueoEditId = null,
        shouldOpenPrecioModal = false,
        serverPrecioEditId = null,
        shouldOpenUsuarioModal = false,
        serverUsuarioEditId = null,
    } = window.reservasConfig || {};

    const initNav = () => {
        const navButtons = document.querySelectorAll('#admin-panels-nav [data-section-target]');
        const sections = document.querySelectorAll('[data-section]');

        if (!navButtons.length) {
            return;
        }

        const activeClasses = ['bg-emerald-200', 'text-emerald-900', 'shadow'];
        const inactiveClasses = ['bg-white', 'text-gray-900', 'hover:bg-gray-100'];

        const activateSection = (target) => {
            sections.forEach((section) => {
                section.classList.toggle('hidden', section.dataset.section !== target);
            });

            navButtons.forEach((btn) => {
                const isActive = btn.dataset.sectionTarget === target;
                btn.classList.remove(...activeClasses, ...inactiveClasses);
                btn.classList.add(...(isActive ? activeClasses : inactiveClasses));
            });
        };

        navButtons.forEach((btn) => btn.addEventListener('click', () => activateSection(btn.dataset.sectionTarget)));

        const initialTarget =
            document.querySelector('#admin-panels-nav [data-default]')?.dataset.sectionTarget ||
            navButtons[0]?.dataset.sectionTarget;

        activateSection(initialTarget);
    };

    const initModal = () => {
        const modal = document.getElementById('modal');
        const openButton = document.getElementById('abrirModal');
        if (!modal) {
            return;
        }

        const closeButtons = modal.querySelectorAll('[data-modal-close]');
        const toggleModal = (show) => {
            modal.classList.toggle('hidden', !show);
            modal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
        };

        openButton?.addEventListener('click', () => toggleModal(true));
        closeButtons.forEach((btn) => btn.addEventListener('click', () => toggleModal(false)));
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                toggleModal(false);
            }
        });

        if (shouldOpenCreateModal) {
            toggleModal(true);
        }
    };

    const initBloqueoModal = () => {
        const modal = document.getElementById('bloqueo-modal');
        const openButton = document.getElementById('abrirBloqueoModal');
        if (!modal) {
            return;
        }

        const closeButtons = modal.querySelectorAll('[data-bloqueo-modal-close]');
        const toggleModal = (show) => {
            modal.classList.toggle('hidden', !show);
            modal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
        };

        openButton?.addEventListener('click', () => toggleModal(true));
        closeButtons.forEach((btn) => btn.addEventListener('click', () => toggleModal(false)));
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                toggleModal(false);
            }
        });

        if (shouldOpenBloqueoModal) {
            toggleModal(true);
        }
    };

    const initPrecioModal = () => {
        const modal = document.getElementById('precio-modal');
        const openButton = document.getElementById('abrirPrecioModal');
        if (!modal) {
            return;
        }

        const closeButtons = modal.querySelectorAll('[data-precio-modal-close]');
        const toggleModal = (show) => {
            modal.classList.toggle('hidden', !show);
            modal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
        };

        openButton?.addEventListener('click', () => toggleModal(true));
        closeButtons.forEach((btn) => btn.addEventListener('click', () => toggleModal(false)));
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                toggleModal(false);
            }
        });

        if (shouldOpenPrecioModal) {
            toggleModal(true);
        }
    };

    const initUsuarioModal = () => {
        const modal = document.getElementById('usuario-modal');
        const openButton = document.getElementById('abrirUsuarioModal');
        if (!modal) {
            return;
        }

        const closeButtons = modal.querySelectorAll('[data-usuario-modal-close]');
        const toggleModal = (show) => {
            modal.classList.toggle('hidden', !show);
            modal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
        };

        openButton?.addEventListener('click', () => toggleModal(true));
        closeButtons.forEach((btn) => btn.addEventListener('click', () => toggleModal(false)));
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                toggleModal(false);
            }
        });

        if (shouldOpenUsuarioModal) {
            toggleModal(true);
        }
    };

    const initEditModals = () => {
        const modals = document.querySelectorAll('[data-edit-modal]');
        if (!modals.length) {
            return;
        }

        let activeModal = null;
        const setBodyScroll = (locked) => document.body.classList.toggle('overflow-hidden', locked);

        const openModal = (modal) => {
            if (activeModal === modal) {
                return;
            }
            if (activeModal) {
                activeModal.classList.add('hidden');
                activeModal.classList.remove('flex');
            }
            activeModal = modal;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setBodyScroll(true);
        };

        const openModalById = (id) => {
            const modal = document.getElementById(id);
            if (modal) {
                openModal(modal);
            }
        };

        const closeModal = (modal) => {
            if (!modal) {
                return;
            }
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (activeModal === modal) {
                activeModal = null;
                setBodyScroll(false);
            }
        };

        document.querySelectorAll('[data-edit-target]').forEach((btn) => {
            btn.addEventListener('click', () => openModalById(btn.dataset.editTarget));
        });

        document.querySelectorAll('[data-edit-modal-close]').forEach((btn) => {
            btn.addEventListener('click', () => closeModal(btn.closest('[data-edit-modal]')));
        });

        modals.forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal(modal);
                }
            });

            if (modal.dataset.openDefault === 'true') {
                openModal(modal);
            }
        });

        if (!activeModal && serverEditId) {
            openModalById(`edit-modal-${serverEditId}`);
        }
    };

    const initBloqueoEditModals = () => {
        const modals = document.querySelectorAll('[data-bloqueo-edit-modal]');
        if (!modals.length) {
            return;
        }

        let activeModal = null;
        const setBodyScroll = (locked) => document.body.classList.toggle('overflow-hidden', locked);

        const openModal = (modal) => {
            if (activeModal === modal) {
                return;
            }
            if (activeModal) {
                activeModal.classList.add('hidden');
                activeModal.classList.remove('flex');
            }
            activeModal = modal;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setBodyScroll(true);
        };

        const openModalById = (id) => {
            const modal = document.getElementById(id);
            if (modal) {
                openModal(modal);
            }
        };

        const closeModal = (modal) => {
            if (!modal) {
                return;
            }
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (activeModal === modal) {
                activeModal = null;
                setBodyScroll(false);
            }
        };

        document.querySelectorAll('[data-bloqueo-edit-target]').forEach((btn) => {
            btn.addEventListener('click', () => openModalById(btn.dataset.bloqueoEditTarget));
        });

        document.querySelectorAll('[data-bloqueo-edit-modal-close]').forEach((btn) => {
            btn.addEventListener('click', () => closeModal(btn.closest('[data-bloqueo-edit-modal]')));
        });

        modals.forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal(modal);
                }
            });

            if (modal.dataset.openDefault === 'true') {
                openModal(modal);
            }
        });

        if (!activeModal && serverBloqueoEditId) {
            openModalById(`bloqueo-edit-modal-${serverBloqueoEditId}`);
        }
    };

    const initPrecioEditModals = () => {
        const modals = document.querySelectorAll('[data-precio-edit-modal]');
        if (!modals.length) {
            return;
        }

        let activeModal = null;
        const setBodyScroll = (locked) => document.body.classList.toggle('overflow-hidden', locked);

        const openModal = (modal) => {
            if (activeModal === modal) {
                return;
            }
            if (activeModal) {
                activeModal.classList.add('hidden');
                activeModal.classList.remove('flex');
            }
            activeModal = modal;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setBodyScroll(true);
        };

        const openModalById = (id) => {
            const modal = document.getElementById(id);
            if (modal) {
                openModal(modal);
            }
        };

        const closeModal = (modal) => {
            if (!modal) {
                return;
            }
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (activeModal === modal) {
                activeModal = null;
                setBodyScroll(false);
            }
        };

        document.querySelectorAll('[data-precio-edit-target]').forEach((btn) => {
            btn.addEventListener('click', () => openModalById(btn.dataset.precioEditTarget));
        });

        document.querySelectorAll('[data-precio-edit-modal-close]').forEach((btn) => {
            btn.addEventListener('click', () => closeModal(btn.closest('[data-precio-edit-modal]')));
        });

        modals.forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal(modal);
                }
            });

            if (modal.dataset.openDefault === 'true') {
                openModal(modal);
            }
        });

        if (!activeModal && serverPrecioEditId) {
            openModalById(`precio-edit-modal-${serverPrecioEditId}`);
        }
    };

    const initUsuarioEditModals = () => {
        const modals = document.querySelectorAll('[data-usuario-edit-modal]');
        if (!modals.length) {
            return;
        }

        let activeModal = null;
        const setBodyScroll = (locked) => document.body.classList.toggle('overflow-hidden', locked);

        const openModal = (modal) => {
            if (activeModal === modal) {
                return;
            }
            if (activeModal) {
                activeModal.classList.add('hidden');
                activeModal.classList.remove('flex');
            }
            activeModal = modal;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setBodyScroll(true);
        };

        const openModalById = (id) => {
            const modal = document.getElementById(id);
            if (modal) {
                openModal(modal);
            }
        };

        const closeModal = (modal) => {
            if (!modal) {
                return;
            }
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (activeModal === modal) {
                activeModal = null;
                setBodyScroll(false);
            }
        };

        document.querySelectorAll('[data-usuario-edit-target]').forEach((btn) => {
            btn.addEventListener('click', () => openModalById(btn.dataset.usuarioEditTarget));
        });

        document.querySelectorAll('[data-usuario-edit-modal-close]').forEach((btn) => {
            btn.addEventListener('click', () => closeModal(btn.closest('[data-usuario-edit-modal]')));
        });

        modals.forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal(modal);
                }
            });

            if (modal.dataset.openDefault === 'true') {
                openModal(modal);
            }
        });

        if (!activeModal && serverUsuarioEditId) {
            openModalById(`usuario-edit-modal-${serverUsuarioEditId}`);
        }
    };

    const initReservaDeleteModal = () => {
        const deleteModal = document.getElementById('reserva-delete-modal');
        if (!deleteModal) {
            return;
        }

        const nameHolder = deleteModal.querySelector('[data-reserva-delete-name]');
        const idHolder = deleteModal.querySelector('[data-reserva-delete-id]');
        let pendingForm = null;

        const toggleModal = (show) => {
            deleteModal.classList.toggle('hidden', !show);
            deleteModal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
            if (!show) {
                pendingForm = null;
            }
        };

        document.querySelectorAll('[data-reserva-delete-target]').forEach((btn) => {
            btn.addEventListener('click', () => {
                pendingForm = document.getElementById(btn.dataset.reservaDeleteTarget);
                const reservaName = btn.dataset.reservaDeleteName ?? 'Cliente no disponible';
                const reservaId = btn.dataset.reservaDeleteId ?? '';
                if (nameHolder) {
                    nameHolder.textContent = reservaName;
                }
                if (idHolder) {
                    idHolder.textContent = reservaId ? `ID #${reservaId}` : '';
                }
                toggleModal(true);
            });
        });

        deleteModal.querySelectorAll('[data-reserva-delete-cancel]').forEach((btn) => {
            btn.addEventListener('click', () => toggleModal(false));
        });

        const confirmBtn = deleteModal.querySelector('[data-reserva-delete-confirm]');
        confirmBtn?.addEventListener('click', () => {
            if (pendingForm) {
                pendingForm.submit();
            }
        });

        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                toggleModal(false);
            }
        });
    };

    const initFeedbackModal = () => {
        const feedbackModal = document.getElementById('feedback-modal');
        if (!feedbackModal) {
            return;
        }

        const shouldShow = feedbackModal.dataset.feedbackVisible === 'true';
        const toggleModal = (show) => {
            feedbackModal.classList.toggle('hidden', !show);
            feedbackModal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
        };

        feedbackModal.querySelectorAll('[data-feedback-close]').forEach((btn) => {
            btn.addEventListener('click', () => toggleModal(false));
        });

        feedbackModal.addEventListener('click', (event) => {
            if (event.target === feedbackModal) {
                toggleModal(false);
            }
        });

        if (shouldShow) {
            toggleModal(true);
        }
    };

    const initDeleteModal = () => {
        const deleteModal = document.getElementById('delete-modal');
        if (!deleteModal) {
            return;
        }

        let pendingForm = null;
        const toggleModal = (show) => {
            deleteModal.classList.toggle('hidden', !show);
            deleteModal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
            if (!show) {
                pendingForm = null;
            }
        };

        document.querySelectorAll('[data-delete-target]').forEach((btn) => {
            btn.addEventListener('click', () => {
                pendingForm = document.getElementById(btn.dataset.deleteTarget);
                toggleModal(true);
            });
        });

        deleteModal.querySelectorAll('[data-delete-cancel]').forEach((btn) => {
            btn.addEventListener('click', () => toggleModal(false));
        });

        const confirmBtn = deleteModal.querySelector('[data-delete-confirm]');
        confirmBtn?.addEventListener('click', () => {
            if (pendingForm) {
                pendingForm.submit();
            }
        });

        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                toggleModal(false);
            }
        });
    };

    const initBloqueoDeleteModal = () => {
        const deleteModal = document.getElementById('bloqueo-delete-modal');
        if (!deleteModal) {
            return;
        }

        let pendingForm = null;
        const toggleModal = (show) => {
            deleteModal.classList.toggle('hidden', !show);
            deleteModal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
            if (!show) {
                pendingForm = null;
            }
        };

        document.querySelectorAll('[data-bloqueo-delete-target]').forEach((btn) => {
            btn.addEventListener('click', () => {
                pendingForm = document.getElementById(btn.dataset.bloqueoDeleteTarget);
                toggleModal(true);
            });
        });

        deleteModal.querySelectorAll('[data-bloqueo-delete-cancel]').forEach((btn) => {
            btn.addEventListener('click', () => toggleModal(false));
        });

        const confirmBtn = deleteModal.querySelector('[data-bloqueo-delete-confirm]');
        confirmBtn?.addEventListener('click', () => {
            if (pendingForm) {
                pendingForm.submit();
            }
        });

        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                toggleModal(false);
            }
        });
    };

    const initPrecioDeleteModal = () => {
        const deleteModal = document.getElementById('precio-delete-modal');
        if (!deleteModal) {
            return;
        }

        let pendingForm = null;
        const toggleModal = (show) => {
            deleteModal.classList.toggle('hidden', !show);
            deleteModal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
            if (!show) {
                pendingForm = null;
            }
        };

        document.querySelectorAll('[data-precio-delete-target]').forEach((btn) => {
            btn.addEventListener('click', () => {
                pendingForm = document.getElementById(btn.dataset.precioDeleteTarget);
                toggleModal(true);
            });
        });

        deleteModal.querySelectorAll('[data-precio-delete-cancel]').forEach((btn) => {
            btn.addEventListener('click', () => toggleModal(false));
        });

        const confirmBtn = deleteModal.querySelector('[data-precio-delete-confirm]');
        confirmBtn?.addEventListener('click', () => {
            if (pendingForm) {
                pendingForm.submit();
            }
        });

        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                toggleModal(false);
            }
        });
    };

    const initUsuarioDeleteModal = () => {
        const deleteModal = document.getElementById('usuario-delete-modal');
        if (!deleteModal) {
            return;
        }

        const nameHolder = deleteModal.querySelector('[data-usuario-delete-name]');
        const emailHolder = deleteModal.querySelector('[data-usuario-delete-email]');
        let pendingForm = null;

        const toggleModal = (show) => {
            deleteModal.classList.toggle('hidden', !show);
            deleteModal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
            if (!show) {
                pendingForm = null;
            }
        };

        document.querySelectorAll('[data-usuario-delete-target]').forEach((btn) => {
            btn.addEventListener('click', () => {
                pendingForm = document.getElementById(btn.dataset.usuarioDeleteTarget);
                if (nameHolder) {
                    nameHolder.textContent = btn.dataset.usuarioDeleteName ?? 'Usuario sin nombre';
                }
                if (emailHolder) {
                    emailHolder.textContent = btn.dataset.usuarioDeleteEmail ?? '';
                }
                toggleModal(true);
            });
        });

        deleteModal.querySelectorAll('[data-usuario-delete-cancel]').forEach((btn) => {
            btn.addEventListener('click', () => toggleModal(false));
        });

        const confirmBtn = deleteModal.querySelector('[data-usuario-delete-confirm]');
        confirmBtn?.addEventListener('click', () => {
            if (pendingForm) {
                pendingForm.submit();
            }
        });

        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                toggleModal(false);
            }
        });
    };

    const initPage = () => {
        document.body.classList.add('bg-slate-900');
        initNav();
        initModal();
        initBloqueoModal();
        initPrecioModal();
        initUsuarioModal();
        initEditModals();
        initBloqueoEditModals();
        initPrecioEditModals();
        initUsuarioEditModals();
        initFeedbackModal();
        initDeleteModal();
        initBloqueoDeleteModal();
        initPrecioDeleteModal();
        initReservaDeleteModal();
        initUsuarioDeleteModal();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPage, { once: true });
    } else {
        initPage();
    }
})();
