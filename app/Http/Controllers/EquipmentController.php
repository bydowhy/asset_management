<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Models\Equipment;
use App\Models\Location;
use App\Services\EquipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EquipmentController extends Controller
{
    protected EquipmentService $service;

    public function __construct(EquipmentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = Equipment::with('location');

        // Filter by location
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Search by tag or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tag', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $equipment = $query->orderBy('tag')->paginate(20)->withQueryString();

        $locations = Location::orderBy('name')->get(['id', 'name', 'code']);

        return Inertia::render('Equipment/Index', [
            'equipment' => $equipment,
            'locations' => $locations,
            'filters' => $request->only(['location_id', 'search']),
        ]);
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('location');

        $currentAssets = $this->service->getCurrentAssets($equipment);
        $assetHistory = $this->service->getAssetHistory($equipment);

        return Inertia::render('Equipment/Show', [
            'equipment' => $equipment,
            'currentAssets' => $currentAssets,
            'assetHistory' => $assetHistory,
        ]);
    }

    public function create()
    {
        return Inertia::render('Equipment/Create', [
            'locations' => Location::orderBy('name')->get(['id', 'name', 'code']),
        ]);
    }

    public function store(StoreEquipmentRequest $request)
    {
        $data = $request->validated();
        $data['id'] = (string) Str::uuid();

        $equipment = Equipment::create($data);

        return redirect()
            ->route('equipment.show', $equipment->id)
            ->with('success', 'Equipment berhasil dibuat.');
    }

    public function edit(Equipment $equipment)
    {
        return Inertia::render('Equipment/Edit', [
            'equipment' => $equipment,
            'locations' => Location::orderBy('name')->get(['id', 'name', 'code']),
        ]);
    }

    public function update(StoreEquipmentRequest $request, Equipment $equipment)
    {
        $equipment->update($request->validated());

        return redirect()
            ->route('equipment.show', $equipment->id)
            ->with('success', 'Equipment berhasil diperbarui.');
    }

    public function destroy(Equipment $equipment)
    {
        // Business rule V1: hanya equipment tanpa asset assignment aktif yang boleh dihapus
        $activeAssignments = $equipment->assetAssignments()->whereNull('removed_at')->count();
        if ($activeAssignments > 0) {
            return back()->withErrors([
                'delete' => 'Tidak bisa menghapus equipment yang masih memiliki asset aktif.',
            ]);
        }

        $equipment->delete();

        return redirect()
            ->route('equipment.index')
            ->with('success', 'Equipment berhasil dihapus.');
    }
}