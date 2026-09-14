<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFailureRequest;
use App\Http\Requests\UpdateFailureRequest;
use App\Models\Asset;
use App\Models\Failure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Services\AuditLogService;

class FailureController extends Controller
{
    public function __construct(
        protected AuditLogService $audit,
    ) {}

    public function index(Request $request)
    {
        $query = Failure::with('asset.assetType', 'createdBy');

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->filled('failure_type')) {
            $query->where('failure_type', 'like', '%' . $request->failure_type . '%');
        }

        if ($request->filled('from')) {
            $query->where('failure_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->where('failure_date', '<=', $request->to);
        }

        $failures = $query->orderByDesc('failure_date')->paginate(20)->withQueryString();

        return Inertia::render('Failures/Index', [
            'failures' => $failures,
            'filters' => $request->only(['asset_id', 'failure_type', 'from', 'to']),
            'assets' => Asset::orderBy('asset_code')->get(['id', 'asset_code']),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Failures/Create', [
            'assets' => Asset::orderBy('asset_code')->get(['id', 'asset_code', 'asset_type_id']),
            'preselectedAssetId' => $request->asset_id,
        ]);
    }

    public function store(StoreFailureRequest $request)
    {
        $data = $request->validated();
        $data['id'] = (string) Str::uuid();
        $data['created_by'] = $request->user()->id;

        $failure = Failure::create($data);

        // ✅ Audit SEBELUM return
        $this->audit->log(
            'create',
            'failure',
            $failure->id,
            "Reported failure '{$failure->failure_type}' on asset {$failure->asset->asset_code}"
        );

        return redirect()->route('failures.index')->with('success', 'Failure berhasil dicatat.');
    }

    public function show(Failure $failure)
    {
        $failure->load('asset.assetType', 'createdBy');

        return Inertia::render('Failures/Show', ['failure' => $failure]);
    }

    public function edit(Failure $failure)
    {
        return Inertia::render('Failures/Edit', [
            'failure' => $failure,
            'assets' => Asset::orderBy('asset_code')->get(['id', 'asset_code']),
        ]);
    }

    public function update(UpdateFailureRequest $request, Failure $failure)
    {
        $failure->update($request->validated());

        // ✅ Audit SEBELUM return
        $this->audit->log('update', 'failure', $failure->id, "Updated failure {$failure->failure_type}");

        return redirect()->route('failures.show', $failure)->with('success', 'Failure berhasil diperbarui.');
    }
}