<?php

namespace App\Http\Controllers;

use App\Models\BloqueoHorario;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
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
            'fecha_inicio' => $this->parseDateTime($data['fecha_inicio']),
            'fecha_fin' => $this->parseDateTime($data['fecha_fin']),
            'motivo' => $data['motivo'],
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('crearBloqueo', $this->rules());
        $payload = $this->sanitize($validated);

        if ($this->hasOverlap($payload)) {
            return back()
                ->withInput()
                ->withErrors([
                    'fecha_inicio' => 'La cancha ya tiene un bloqueo asignado en esa franja horaria.',
                ], 'crearBloqueo')
                ->with('error', 'No se pudo crear el bloqueo porque ya existe uno en ese mismo horario.');
        }

        BloqueoHorario::create($this->formatForPersistence($payload) + [
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

        $payload = $this->sanitize($validator->validated());

        if ($this->hasOverlap($payload, $bloqueo->id)) {
            return back()
                ->withInput()
                ->with('editarBloqueoId', $bloqueo->id)
                ->withErrors([
                    'fecha_inicio' => 'La cancha ya tiene un bloqueo asignado en esa franja horaria.',
                ], 'editarBloqueo')
                ->with('error', 'No se pudo actualizar el bloqueo porque existe otro que coincide con el horario.');
        }

        $bloqueo->update($this->formatForPersistence($payload));

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

    private function hasOverlap(array $payload, ?int $ignoreId = null): bool
    {
        $inicio = $payload['fecha_inicio'];
        $fin = $payload['fecha_fin'];

        return BloqueoHorario::query()
            ->where('cancha_id', $payload['cancha_id'])
            ->whereDate('fecha_inicio', $inicio->toDateString())
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('fecha_inicio', '<', $fin->toDateTimeString())
            ->where('fecha_fin', '>', $inicio->toDateTimeString())
            ->exists();
    }

    private function parseDateTime(string $value): Carbon
    {
        $value = trim($value);

        $formats = [
            'Y-m-d\TH:i',
            'Y-m-d H:i',
            'd/m/Y H:i',
            'd-m-Y H:i',
        ];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value);
            } catch (\Throwable $e) {
                continue;
            }
        }

        return Carbon::parse($value);
    }

    private function formatForPersistence(array $payload): array
    {
        return [
            'cancha_id' => $payload['cancha_id'],
            'fecha_inicio' => $payload['fecha_inicio']->copy()->setSecond(0)->toDateTimeString(),
            'fecha_fin' => $payload['fecha_fin']->copy()->setSecond(0)->toDateTimeString(),
            'motivo' => $payload['motivo'],
        ];
    }
}
