<?php

namespace App\Http\Controllers;

use App\Models\BloqueoHorario;
use App\Models\Cancha;
use App\Models\CanchaPrecio;
use App\Models\Reserva;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CanchaController extends Controller
{
    private function rules(?int $ignoreId = null, bool $isUpdate = false): array
    {
        $uniqueNombre = 'unique:canchas,nombre';

        if ($ignoreId) {
            $uniqueNombre .= ',' . $ignoreId;
        }

        return [
            'nombre' => ['required', 'string', 'max:255', $uniqueNombre],
            'tipo' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'precio_hora' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'activa' => ['nullable', 'boolean'],
            'imagen' => [$isUpdate ? 'nullable' : 'required', 'image', 'max:4096'],
        ];
    }

    private function sanitizeData(array $validated, Request $request, ?Cancha $cancha = null): array
    {
        $data = [
            'nombre' => $validated['nombre'],
            'tipo' => $validated['tipo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'precio_hora' => (float) $validated['precio_hora'],
            'activa' => $request->boolean('activa'),
        ];

        $imagenUrl = $this->storeUploadedImage($request);

        if ($imagenUrl) {
            $data['imagen_url'] = $imagenUrl;
        } elseif ($cancha) {
            $data['imagen_url'] = $cancha->imagen_url;
        }

        return $data;
    }

 private function storeUploadedImage(Request $request): ?string
{
    if (! $request->hasFile('imagen')) {
        return null;
    }

    $path = $request->file('imagen')->store('canchas', 'public');

    return Storage::url($path); // /storage/canchas/archivo.jpg
}


    public function index(): View
    {
        $canchas = Cancha::orderBy('nombre')->get();
        $reservas = Reserva::with(['cancha', 'cliente', 'creador', 'actualizador'])
            ->latest('fecha_reserva')
            ->get();
        $bloqueos = BloqueoHorario::with(['cancha', 'creador'])
            ->latest('fecha_inicio')
            ->get();
        $precios = CanchaPrecio::with('cancha')
            ->latest('fecha_desde')
            ->get();

        return view('administracion.index', compact('canchas', 'reservas', 'bloqueos', 'precios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->sanitizeData(
            $request->validateWithBag('crearCancha', $this->rules()),
            $request
        );

        Cancha::create($validated);

        return redirect()->route('admin.index')->with('status', 'Cancha creada correctamente.');
    }

    public function update(Request $request, Cancha $cancha): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->rules($cancha->id, true));

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('editarCanchaId', $cancha->id)
                ->withErrors($validator, 'editarCancha');
        }

        $validated = $this->sanitizeData($validator->validated(), $request, $cancha);

        $cancha->update($validated);

        return redirect()->route('admin.index')->with('status', 'Cancha actualizada correctamente.');
    }

    public function destroy(Cancha $cancha): RedirectResponse
    {
        $cancha->delete();

        return redirect()->route('admin.index')->with('status', 'Cancha eliminada correctamente.');
    }
}
