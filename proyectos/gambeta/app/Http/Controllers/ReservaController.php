<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RuntimeException;

class ReservaController extends Controller
{
    /**
     * Regla universal para formularios de reservas.
     *
     * @return array<string, mixed>
     */
    private function rules(): array
    {
        return [
            'cancha_id' => ['required', 'exists:canchas,id'],
            'cliente_id' => ['required', 'exists:clientes,id'],
            'fecha_reserva' => ['required', 'date'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
            'duracion_minutos' => ['required', 'integer', 'min:15', 'max:1440'],
            'precio_hora' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'total' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'estado' => ['required', Rule::in(Reserva::ESTADOS)],
            'observaciones' => ['nullable', 'string', 'max:2000'],
            'creado_por' => ['required', 'exists:users,id'],
        ];
    }

    /**
     * Mensajes específicos para reglas complejas.
     *
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'fecha_fin.after' => 'La fecha y hora de fin debe ser posterior a la hora de inicio.',
        ];
    }

    /**
     * Normaliza los datos antes de persistirlos.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function sanitize(array $validated): array
    {
        $inicio = Carbon::parse($validated['fecha_inicio'])->seconds(0);
        $fin = Carbon::parse($validated['fecha_fin'])->seconds(0);

        return [
            'cancha_id' => (int) $validated['cancha_id'],
            'cliente_id' => (int) $validated['cliente_id'],
            'fecha_reserva' => Carbon::parse($validated['fecha_reserva'])->toDateString(),
            'fecha_inicio' => $inicio->toDateTimeString(),
            'fecha_fin' => $fin->toDateTimeString(),
            'duracion_minutos' => (int) $validated['duracion_minutos'],
            'precio_hora' => round((float) $validated['precio_hora'], 2),
            'total' => round((float) $validated['total'], 2),
            'estado' => $validated['estado'],
            'observaciones' => $validated['observaciones'] ?? null,
            'creado_por' => (int) $validated['creado_por'],
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator, 'crearReserva')
                ->with('error', $validator->errors()->first());
        }

        $payload = $this->sanitize($validator->validated());

        Reserva::create($payload + [
            'actualizado_por' => $this->resolveUpdaterId($request, $payload['creado_por']),
        ]);

        return redirect()->route('admin.index')->with('status', 'Reserva creada correctamente.');
    }

    public function update(Request $request, Reserva $reserva): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('editarReservaId', $reserva->id)
                ->withErrors($validator, 'editarReserva')
                ->with('error', $validator->errors()->first());
        }

        $payload = $this->sanitize($validator->validated());

        $reserva->update($payload + [
            'actualizado_por' => $this->resolveUpdaterId($request, $payload['creado_por']),
        ]);

        return redirect()->route('admin.index')->with('status', 'Reserva actualizada correctamente.');
    }

    public function destroy(Reserva $reserva): RedirectResponse
    {
        $reserva->delete();

        return redirect()->route('admin.index')->with('status', 'Reserva eliminada correctamente.');
    }

    /**
     * Obtiene el usuario que realizará la acción.
     */
    private function resolveUpdaterId(Request $request, ?int $fallback = null): int
    {
        $authId = $request->user()?->id ?? Auth::id();

        if ($authId) {
            return (int) $authId;
        }

        if ($fallback) {
            return $fallback;
        }

        $firstUser = User::query()->orderBy('id')->value('id');

        if ($firstUser) {
            return (int) $firstUser;
        }

        throw new RuntimeException('No hay usuarios disponibles para registrar reservas.');
    }
}

