<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Models\Location;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::with('parent')
            ->withCount('equipment')
            ->withCount('children')
            ->orderBy('code')
            ->get();

        return Inertia::render('Locations/Index', [
            'locations' => $locations,
        ]);
    }

    public function create()
    {
        return Inertia::render('Locations/Create', [
            'parents' => $this->flattenedParents(),
        ]);
    }

    public function store(StoreLocationRequest $request)
    {
        $data = $request->validated();
        $data['id'] = (string) Str::uuid();

        Location::create($data);

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location berhasil dibuat.');
    }

    public function edit(Location $location)
    {
        return Inertia::render('Locations/Edit', [
            'location' => $location,
            'parents' => $this->flattenedParents($location),
        ]);
    }

    public function update(StoreLocationRequest $request, Location $location)
    {
        $location->update($request->validated());

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location berhasil diperbarui.');
    }

    public function destroy(Location $location)
    {
        if ($location->children()->exists()) {
            return back()->withErrors([
                'delete' => 'Tidak bisa menghapus location yang masih memiliki sub-location.',
            ]);
        }

        if ($location->equipment()->exists()) {
            return back()->withErrors([
                'delete' => 'Tidak bisa menghapus location yang masih memiliki equipment.',
            ]);
        }

        $location->delete();

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location berhasil dihapus.');
    }

    /**
     * Bangun daftar lokasi dengan indentasi, opsional exclude subtree untuk edit.
     * Setiap item: ['id' => ..., 'label' => '  └ Paper Machine (PM)']
     */
    private function flattenedParents(?Location $excludeSubtreeOf = null): array
    {
        $excludeIds = [];

        if ($excludeSubtreeOf) {
            $excludeIds = $this->collectSubtreeIds($excludeSubtreeOf);
        }

        $all = Location::orderBy('name')->get();
        $byParent = $all->groupBy('parent_id');

        $result = [];

        $walk = function ($parentId, $depth) use (&$walk, &$result, $byParent, $excludeIds) {
            foreach ($byParent->get($parentId, collect()) as $node) {
                if (in_array($node->id, $excludeIds, true)) {
                    continue;
                }

                $prefix = str_repeat('— ', $depth);
                $result[] = [
                    'id' => $node->id,
                    'label' => $prefix . $node->name . ' (' . $node->code . ')',
                ];

                $walk($node->id, $depth + 1);
            }
        };

        $walk(null, 0);

        return $result;
    }

    /**
     * Kumpulkan semua ID di subtree dari location tertentu (termasuk dirinya).
     */
    private function collectSubtreeIds(Location $root): array
    {
        $ids = [$root->id];
        $queue = [$root->id];

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
}