<?php

namespace App\Http\Controllers;

use App\Models\CanchaPrecio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CanchaPrecioController extends Controller
{
    /**
     * @return array<string, array<int, string>>
     */
    private function rules(): array
    {
        return [
            'cancha_id' => ['required', 'exists:canchas,id'],
            'precio_hora' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'fecha_desde' => ['required', 'date'],
            'fecha_hasta' => ['nullable', 'date', 'after:fecha_desde'],
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function sanitize(array $data): array
    {
        $fechaHasta = $data['fecha_hasta'] ?? null;

        return [
            'cancha_id' => (int) $data['cancha_id'],
            'precio_hora' => (float) $data['precio_hora'],
            'fecha_desde' => $this->normalizeDate($data['fecha_desde']),
            'fecha_hasta' => $fechaHasta ? $this->normalizeDate($fechaHasta) : null,
        ];
    }

    private function normalizeDate(string $value): string
    {
        return $this->parseDateTime($value)
            ->setSecond(0)
            ->toDateTimeString();
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

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator, 'crearPrecio')
                ->with('error', $validator->errors()->first());
        }

        $payload = $this->sanitize($validator->validated());

        if ($overlap = $this->findOverlap($payload)) {
            $message = $this->buildOverlapMessage($overlap);

            return back()
                ->withInput()
                ->withErrors([
                    'fecha_desde' => $message,
                    'fecha_hasta' => $message,
                ], 'crearPrecio')
                ->with('error', $message);
        }

        CanchaPrecio::create($payload);

        return redirect()->route('admin.index')->with('status', 'Precio registrado correctamente.');
    }

    public function update(Request $request, CanchaPrecio $precio): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('editarPrecioId', $precio->id)
                ->withErrors($validator, 'editarPrecio')
                ->with('error', $validator->errors()->first());
        }

        $payload = $this->sanitize($validator->validated());

        if ($overlap = $this->findOverlap($payload, $precio->id)) {
            $message = $this->buildOverlapMessage($overlap);

            return back()
                ->withInput()
                ->with('editarPrecioId', $precio->id)
                ->withErrors([
                    'fecha_desde' => $message,
                    'fecha_hasta' => $message,
                ], 'editarPrecio')
                ->with('error', $message);
        }

        $precio->update($payload);

        return redirect()->route('admin.index')->with('status', 'Precio actualizado correctamente.');
    }

    public function destroy(CanchaPrecio $precio): RedirectResponse
    {
        $precio->delete();

        return redirect()->route('admin.index')->with('status', 'Precio eliminado correctamente.');
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'fecha_hasta.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function findOverlap(array $payload, ?int $ignoreId = null): ?CanchaPrecio
    {
        $inicioNuevo = Carbon::parse($payload['fecha_desde']);
        $finNuevo = isset($payload['fecha_hasta']) ? Carbon::parse($payload['fecha_hasta']) : null;

        return CanchaPrecio::query()
            ->where('cancha_id', $payload['cancha_id'])
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->orderBy('fecha_desde')
            ->get()
            ->first(function (CanchaPrecio $precio) use ($inicioNuevo, $finNuevo) {
                $inicioExistente = $precio->fecha_desde instanceof Carbon
                    ? $precio->fecha_desde->copy()
                    : Carbon::parse($precio->fecha_desde);
                $finExistente = $precio->fecha_hasta
                    ? ($precio->fecha_hasta instanceof Carbon
                        ? $precio->fecha_hasta->copy()
                        : Carbon::parse($precio->fecha_hasta))
                    : null;

                return $this->intervalsOverlap($inicioExistente, $finExistente, $inicioNuevo, $finNuevo);
            });
    }

    private function intervalsOverlap(Carbon $startA, ?Carbon $endA, Carbon $startB, ?Carbon $endB): bool
    {
        $aEndsAfterBStarts = $endA === null || $endA->gt($startB);
        $bEndsAfterAStarts = $endB === null || $endB->gt($startA);

        return $aEndsAfterBStarts && $bEndsAfterAStarts;
    }

    private function buildOverlapMessage(CanchaPrecio $precio): string
    {
        $desde = $precio->fecha_desde instanceof Carbon
            ? $precio->fecha_desde->format('d/m/Y H:i')
            : Carbon::parse($precio->fecha_desde)->format('d/m/Y H:i');
        if ($precio->fecha_hasta instanceof Carbon) {
            $hasta = $precio->fecha_hasta->format('d/m/Y H:i');
        } elseif ($precio->fecha_hasta) {
            $hasta = Carbon::parse($precio->fecha_hasta)->format('d/m/Y H:i');
        } else {
            $hasta = 'sin fecha de cierre (vigente)';
        }

        return "La cancha ya tiene un precio vigente del {$desde} al {$hasta}. Ajusta las fechas para evitar solapamientos.";
    }
}
