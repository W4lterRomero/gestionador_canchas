<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{

    public function index()
    {
        return view('clientes.index');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|string|max:50',
            'equipo' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'notas' => 'nullable|string',
            'es_frecuente' => 'nullable|boolean',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            $data['fecha_registro'] = now();

            $cliente = Cliente::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Cliente creado exitosamente',
                'cliente' => $cliente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el cliente: ' . $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, Cliente $cliente)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|string|max:50',
            'equipo' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'notas' => 'nullable|string',
            'es_frecuente' => 'nullable|boolean',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cliente->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Cliente actualizado exitosamente',
                'cliente' => $cliente->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el cliente: ' . $e->getMessage()
            ], 500);
        }
    }


    public function destroy(Cliente $cliente)
    {
        try {
            // Verificar si tiene reservas asociadas
            if ($cliente->reservas()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el cliente porque tiene reservas asociadas'
                ], 400);
            }

            $cliente->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cliente eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el cliente: ' . $e->getMessage()
            ], 500);
        }
    }


    public function toggleFrecuente(Request $request, Cliente $cliente)
    {
        $validator = Validator::make($request->all(), [
            'es_frecuente' => 'required|boolean',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if ($request->es_frecuente) {
                $cliente->marcarComoFrecuente($request->descuento_porcentaje ?? 0);
                $message = 'Cliente marcado como frecuente';
            } else {
                $cliente->desmarcarComoFrecuente();
                $message = 'Cliente desmarcado como frecuente';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'cliente' => $cliente->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado del cliente: ' . $e->getMessage()
            ], 500);
        }
    }


    public function actualizarEstadisticas(Cliente $cliente)
    {
        try {
            $cliente->actualizarEstadisticas();

            return response()->json([
                'success' => true,
                'message' => 'Estadísticas actualizadas exitosamente',
                'cliente' => $cliente->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }


    public function historial(Cliente $cliente)
    {
        return view('clientes.historial', compact('cliente'));
    }
}
