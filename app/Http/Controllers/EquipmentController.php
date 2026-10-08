<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Models\Equipment;
use App\Models\Location;
use App\Services\AuditLogService;
use App\Services\EquipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EquipmentController extends Controller
{
    public function __construct(
        protected EquipmentService $service,
        protected AuditLogService $audit,
    ) {}

    public function index(Request $request)
    {
        $query = Equipment::with('location');

        // Filter by location — RECURSIVE (termasuk semua descendant)
        if ($request->filled('location_id')) {
            $locationIds = $this->collectLocationSubtreeIds($request->location_id);
            $query->whereIn('location_id', $locationIds);
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

        return Inertia::render('Equipment/Index', [
            'equipment' => $equipment,
            'locations' => $this->flattenedLocationsWithCounts(),
            'filters' => $request->only(['location_id', 'search']),
        ]);
    }

    /**
     * Kumpulkan ID lokasi + semua turunannya (recursive).
     */
    private function collectLocationSubtreeIds(string $rootId): array
    {
        $ids = [$rootId];
        $queue = [$rootId];

        while (! empty($queue)) {
            $parentId = array_shift($queue);
            $children = Location::where('parent_id', $parentId)->pluck('id')->all();

            foreach ($children as $childId) {
                $ids[] = $childId;
                $queue[] = $childId;
            }
        }

        return $ids;
    }

    /**
     * Bangun daftar lokasi flat dengan indentasi + count equipment di subtree.
     * Hanya tampilkan lokasi yang punya equipment > 0 di subtree-nya.
     */
    private function flattenedLocationsWithCounts(): array
    {
        $all = Location::orderBy('name')->get();

        // Hitung equipment langsung per lokasi
        $directCounts = Equipment::selectRaw('location_id, count(*) as c')
            ->groupBy('location_id')
            ->pluck('c', 'location_id')
            ->toArray();

        $byParent = $all->groupBy('parent_id');
        $subtreeCounts = [];

        // Post-order: hitung total equipment di subtree dengan memoization
        $computeSubtree = function ($node) use (&$computeSubtree, $byParent, $directCounts, &$subtreeCounts) {
            if (isset($subtreeCounts[$node->id])) {
                return $subtreeCounts[$node->id];
            }

            $total = $directCounts[$node->id] ?? 0;
            foreach ($byParent->get($node->id, collect()) as $child) {
                $total += $computeSubtree($child);
            }

            $subtreeCounts[$node->id] = $total;
            return $total;
        };

        foreach ($all as $node) {
            $computeSubtree($node);
        }

        // Bangun flat list dengan indentasi, skip lokasi yang subtree count = 0
        $result = [];
        $walk = function ($parentId, $depth) use (&$walk, &$result, $byParent, $subtreeCounts) {
            foreach ($byParent->get($parentId, collect()) as $node) {
                if (($subtreeCounts[$node->id] ?? 0) === 0) {
                    // Skip lokasi ini + turunannya (karena subtree count = 0)
                    continue;
                }

                $prefix = str_repeat('— ', $depth);
                $result[] = [
                    'id' => $node->id,
                    'label' => $prefix . $node->name . ' (' . $node->code . ')',
                    'count' => $subtreeCounts[$node->id],
                ];

                $walk($node->id, $depth + 1);
            }
        };

        $walk(null, 0);

        return $result;
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('location');

        $currentAssets = $this->service->getCurrentAssets($equipment);
        $assetHistory = $this->service->getAssetHistory($equipment);

        $documents = \App\Models\DocumentLink::with('document.type')
            ->where('entity_type', 'equipment')
            ->where('entity_id', $equipment->id)
            ->get()
            ->pluck('document');

        $photos = \App\Models\PhotoLink::with('photo')
            ->where('entity_type', 'equipment')
            ->where('entity_id', $equipment->id)
            ->get()
            ->pluck('photo');

        return Inertia::render('Equipment/Show', [
            'equipment' => $equipment,
            'currentAssets' => $currentAssets,
            'assetHistory' => $assetHistory,
            'documents' => $documents,
            'photos' => $photos,
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

        $this->audit->log('create', 'equipment', $equipment->id, "Created equipment {$equipment->tag}");

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

        $this->audit->log('update', 'equipment', $equipment->id, "Updated equipment {$equipment->tag}");

        return redirect()
            ->route('equipment.show', $equipment->id)
            ->with('success', 'Equipment berhasil diperbarui.');
    }

    public function destroy(Equipment $equipment)
    {
        $activeAssignments = $equipment->assetAssignments()->whereNull('removed_at')->count();
        if ($activeAssignments > 0) {
            return back()->with('error', 'Tidak bisa menghapus equipment yang masih memiliki asset aktif.');
        }

        $tag = $equipment->tag;
        $equipmentId = $equipment->id;

        $equipment->delete();

        $this->audit->log('delete', 'equipment', $equipmentId, "Deleted equipment {$tag}");

        return redirect()
            ->route('equipment.index')
            ->with('success', 'Equipment berhasil dihapus.');
    }
}