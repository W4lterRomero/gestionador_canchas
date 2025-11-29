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
}
