<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpaceTypeRequest;
use App\Http\Requests\UpdateSpaceTypeRequest;
use App\Models\SpaceType;
use Illuminate\Http\JsonResponse;

class SpaceTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $types = SpaceType::withCount('spaces')->orderBy('name')->get();

        return $this->successResponse($types, 'Listado de tipos de espacio');
    }

    public function show(SpaceType $spaceType): JsonResponse
    {
        return $this->successResponse($spaceType->loadCount('spaces'), 'Detalle del tipo de espacio');
    }

    public function store(StoreSpaceTypeRequest $request): JsonResponse
    {
        $spaceType = SpaceType::create($request->validated());

        return $this->successResponse($spaceType, 'Tipo de espacio creado correctamente', 201);
    }

    public function update(UpdateSpaceTypeRequest $request, SpaceType $spaceType): JsonResponse
    {
        $spaceType->update($request->validated());

        return $this->successResponse($spaceType, 'Tipo de espacio actualizado correctamente');
    }

    public function destroy(SpaceType $spaceType): JsonResponse
    {
        if ($spaceType->spaces()->exists()) {
            return $this->errorResponse(
                'No se puede eliminar un tipo de espacio que tiene espacios asociados',
                409
            );
        }

        $spaceType->delete();

        return $this->successResponse(null, 'Tipo de espacio eliminado correctamente');
    }
}