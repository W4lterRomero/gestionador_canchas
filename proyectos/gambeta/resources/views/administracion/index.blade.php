@extends('components.layouts.app')

@php
    $canchas = $canchas ?? collect();
    $reservas = $reservas ?? collect();
    $bloqueos = $bloqueos ?? collect();
    $precios = $precios ?? collect();
    $editarCanchaId = session('editarCanchaId');
    $editarBloqueoId = session('editarBloqueoId');
    $editarPrecioId = session('editarPrecioId');
    $shouldOpenCreateModal = $errors->crearCancha->any();
    $shouldOpenBloqueoModal = $errors->crearBloqueo->any();
    $shouldOpenPrecioModal = $errors->crearPrecio->any();
    $crearBloqueoErrors = $errors->crearBloqueo ?? null;
    $editarBloqueoErrors = $errors->editarBloqueo ?? null;
    $crearPrecioErrors = $errors->crearPrecio ?? null;
    $editarPrecioErrors = $errors->editarPrecio ?? null;
    $feedbackStatus = session('status');
    $feedbackError = session('error');
    $feedbackMessage = $feedbackStatus ?: $feedbackError;
    $feedbackType = $feedbackStatus ? 'success' : ($feedbackError ? 'error' : null);
    $isErrorFeedback = $feedbackType === 'error';
    $feedbackTitle = $isErrorFeedback ? 'Ocurrió un problema' : 'Operación exitosa';
@endphp

@section('content')

<div class="container py-4">

    <!-- Header -->
    <div class="hero mb-4"
        style="background:linear-gradient(90deg,#0b3d91,#1565c0);color:white;padding:18px;border-radius:8px;">
        <h3 class="mb-0">Panel de Administración</h3>
        <div class="small">Gestión general del sistema</div>
    </div>


    <!-- ============================================================= -->
    <!-- 🔵 SECCIÓN 1: VER TODAS LAS RESERVAS -->
    <!-- ============================================================= -->
    <section class="mb-5">

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Ver todas las reservas</h5>
            </div>

            <div class="card-body">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Filtrar por fecha</label>
                        <input type="date" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Filtrar por cancha</label>
                        <select class="form-select">
                            <option>Todas</option>
                            <option>Cancha Central</option>
                            <option>Indoor Arena</option>
                            <option>Rápida Norte</option>
                        </select>
                    </div>
                </div>

                <!-- Tabla de ejemplo (solo diseño) -->
                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>Fecha</th>
                            <th>Cancha</th>
                            <th>Cliente</th>
                            <th>Horario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2025-01-10</td>
                            <td>Cancha Central</td>
                            <td>Juan Pérez</td>
                            <td>10:00 - 11:00</td>
                        </tr>
                        <tr>
                            <td>2025-01-11</td>
                            <td>Indoor Arena</td>
                            <td>María López</td>
                            <td>14:00 - 15:00</td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('reservas.index') }}"
               class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full px-6">
                Ver todas las reservas
            </a>
        </div>

    </section>



    <!-- ============================================================= -->
    <!-- 🔵 SECCIÓN 2: BLOQUEAR HORARIOS -->
    <!-- ============================================================= -->
    <section class="mb-5">

        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Bloquear horarios especiales</h5>
            </div>

            <div class="card-body">

                <p class="text-muted">
                    Configure bloqueos temporales para mantenimiento, eventos o actividades exclusivas.
                </p>

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Cancha</label>
                        <select class="form-select">
                            <option>Cancha Central</option>
                            <option>Indoor Arena</option>
                            <option>Rápida Norte</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha del bloqueo</label>
                        <input type="date" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Motivo</label>
                        <input type="text" class="form-control" placeholder="Mantenimiento, evento, etc.">
                    </div>

                </div>

                <div class="mt-3 text-end">
                    <button class="btn btn-warning">Guardar bloqueo</button>
                </div>

                <hr class="my-4">

                <h6>Bloqueos recientes (ejemplo)</h6>
                <ul class="list-group">
                    <li class="list-group-item">
                        <b>Cancha Central</b> — 2025-01-15 <br>
                        Motivo: Mantenimiento general
                    </li>
                </ul>

            </div>
        </div>

    </section>



    <!-- ============================================================= -->
    <!-- 🔵 SECCIÓN 3: GESTIONAR PRECIOS -->
    <!-- ============================================================= -->
    <section class="mb-5">

        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Gestionar precios</h5>
            </div>

            <div class="card-body">

                <p class="text-muted">
                    Ajusta el precio por hora de cada cancha.
                </p>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Cancha</label>
                        <select class="form-select">
                            <option value="f1">Cancha Central</option>
                            <option value="f2">Indoor Arena</option>
                            <option value="f3">Rápida Norte</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nuevo precio (USD)</label>
                        <input type="number" class="form-control">
                    </div>

                </div>

                <div class="mt-3 text-end">
                    <button class="btn btn-success">Guardar precio</button>
                </div>

                <hr class="my-4">

                <h6>Precios actuales (ejemplo)</h6>

                <ul class="list-group mt-2">
                    <li class="list-group-item">
                        <b>Cancha Central</b>: $20/h
                    </li>
                    <li class="list-group-item">
                        <b>Indoor Arena</b>: $35/h
                    </li>
                    <li class="list-group-item">
                        <b>Rápida Norte</b>: $18/h
                    </li>
                </ul>

            </div>

        </div>
    </section>

    <!-- SECCIÓN RESERVAS -->
    <section id="reservas" data-section="reservas" class="scroll-mt-32 hidden">
        <!-- ... (todo tu contenido de reservas tal como lo tienes) ... -->
    </section>

    <!-- SECCIÓN BLOQUEOS -->
    <section id="bloqueos" data-section="bloqueos" class="scroll-mt-32 hidden">
        <!-- ... (todo tu contenido de bloqueos tal como lo tienes) ... -->
    </section>

    <!-- SECCIÓN PRECIOS -->
    <section id="precios" data-section="precios" class="scroll-mt-32 hidden">
        <!-- ... (todo tu contenido de precios tal como lo tienes) ... -->
    </section>
</div>

{{-- SCRIPT PARA ACTIVAR SECCIONES Y ESTILOS (TEXTO NEGRO SIEMPRE) --}}
<script>
    (() => {
        const shouldOpenCreateModal = @json($shouldOpenCreateModal);
        const serverEditId = @json($editarCanchaId);

        const initNav = () => {
            const navButtons = document.querySelectorAll('#admin-panels-nav [data-section-target]');
            const sections = document.querySelectorAll('[data-section]');

            if (!navButtons.length) {
                return;
            }

            const activeClasses = ['bg-indigo-200', 'shadow'];
            const inactiveClasses = ['bg-white', 'hover:bg-gray-100'];

            const activateSection = (target) => {
                sections.forEach((section) => {
                    section.classList.toggle('hidden', section.dataset.section !== target);
                });

                navButtons.forEach((btn) => {
                    const isActive = btn.dataset.sectionTarget === target;
                    btn.classList.remove(...activeClasses, ...inactiveClasses);
                    btn.classList.add(...(isActive ? activeClasses : inactiveClasses), 'text-gray-900');
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

        const initPage = () => {
            initNav();
            initModal();
            initEditModals();
            initFeedbackModal();
            initDeleteModal();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPage, { once: true });
        } else {
            initPage();
        }
    })();
</script>

{{-- logica --}}
<script src="{{ asset('js/reservas.js') }}"></script>
@endsection
