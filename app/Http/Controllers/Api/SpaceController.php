<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpaceRequest;
use App\Http\Requests\UpdateSpaceRequest;
use App\Models\Space;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    // RF01: catálogo con filtros por tipo, capacidad y rango de fechas/horas
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'space_type_id' => ['nullable', 'integer', 'exists:space_types,id'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'start_time' => ['nullable', 'date', 'required_with:end_time'],
            'end_time' => ['nullable', 'date', 'required_with:start_time', 'after:start_time'],
        ], [
            'space_type_id.exists' => 'El tipo de espacio no existe',
            'capacity.integer' => 'La capacidad debe ser un número entero',
            'start_time.date' => 'La hora de inicio no es una fecha válida',
            'start_time.required_with' => 'Debes enviar también la hora de inicio',
            'end_time.date' => 'La hora de fin no es una fecha válida',
            'end_time.required_with' => 'Debes enviar también la hora de fin',
            'end_time.after' => 'La hora de fin debe ser posterior a la de inicio',
        ]);

        $query = Space::with('spaceType')->orderBy('name');

        // Solo el admin ve también los espacios inactivos
        if (! $request->user()->hasRole('admin')) {
            $query->active();
        }

        $query
            ->when($request->filled('space_type_id'), fn ($q) => $q->where('space_type_id', $request->space_type_id))
            ->when($request->filled('capacity'), fn ($q) => $q->where('capacity', '>=', $request->capacity))
            // Solo espacios libres en el rango pedido (usa la scope scopeOverlapping)
            ->when($request->filled('start_time'), fn ($q) => $q->whereDoesntHave(
                'reservations',
                fn ($r) => $r->overlapping($request->start_time, $request->end_time)
            ));

        return $this->successResponse($query->get(), 'Listado de espacios');
    }

    public function show(Request $request, Space $space): JsonResponse
    {
        if (! $space->is_active && ! $request->user()->hasRole('admin')) {
            return $this->errorResponse('El recurso solicitado no existe', 404);
        }

        return $this->successResponse($space->load('spaceType'), 'Detalle del espacio');
    }

    // RF08
    public function store(StoreSpaceRequest $request): JsonResponse
    {
        $space = Space::create($request->validated());

        return $this->successResponse($space->load('spaceType'), 'Espacio creado correctamente', 201);
    }

    public function update(UpdateSpaceRequest $request, Space $space): JsonResponse
    {
        $space->update($request->validated());

        return $this->successResponse($space->load('spaceType'), 'Espacio actualizado correctamente');
    }

    public function destroy(Space $space): JsonResponse
    {
        // Borrar un espacio borraría en cascada todo su historial de reservas
        if ($space->reservations()->exists()) {
            return $this->errorResponse(
                'No se puede eliminar un espacio con reservas. Desactívalo en su lugar (is_active = false)',
                409
            );
        }

        $space->delete();

        return $this->successResponse(null, 'Espacio eliminado correctamente');
    }
}