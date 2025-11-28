<?php

namespace App\Http\Controllers;

use App\Models\BloqueoHorario;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class BloqueoHorarioController extends Controller
{
    private function rules(): array
    {
        return [
            'cancha_id' => ['required', 'exists:canchas,id'],
            'fecha_inicio' => ['required', 'date', 'before:fecha_fin'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
            'motivo' => ['required', 'string', 'max:255'],
        ];
    }

    private function sanitize(array $data): array
    {
        return [
            'cancha_id' => (int) $data['cancha_id'],
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin' => $data['fecha_fin'],
            'motivo' => $data['motivo'],
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('crearBloqueo', $this->rules());

        BloqueoHorario::create($this->sanitize($validated) + [
            'creado_por' => $this->resolveCreatorId($request),
        ]);

        return redirect()->route('admin.index')->with('status', 'Bloqueo creado correctamente.');
    }

    public function update(Request $request, BloqueoHorario $bloqueo): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('editarBloqueoId', $bloqueo->id)
                ->withErrors($validator, 'editarBloqueo');
        }

        $bloqueo->update($this->sanitize($validator->validated()));

        return redirect()->route('admin.index')->with('status', 'Bloqueo actualizado correctamente.');
    }

    public function destroy(BloqueoHorario $bloqueo): RedirectResponse
    {
        $bloqueo->delete();

        return redirect()->route('admin.index')->with('status', 'Bloqueo eliminado correctamente.');
    }

    private function resolveCreatorId(Request $request): int
    {
        $userId = $request->user()?->id ?? Auth::id();

        if ($userId) {
            return $userId;
        }

        $fallbackId = User::query()->orderBy('id')->value('id');

        if ($fallbackId) {
            return (int) $fallbackId;
        }

        throw new RuntimeException('No hay usuarios disponibles para registrar bloqueos.');
    }
}
