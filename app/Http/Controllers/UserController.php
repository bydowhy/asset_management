<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        protected AuditLogService $audit,
    ) {}

    public function index()
    {
        $users = User::orderBy('name')->get([
            'id', 'username', 'name', 'email', 'department', 'role', 'created_at',
        ]);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $actor = Auth::user();

        return Inertia::render('Users/Create', [
            'canPromote' => $actor->role === 'super_admin',
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $actor = Auth::user();
        $data = $request->validated();

        if ($data['role'] === 'super_admin' && $actor->role !== 'super_admin') {
            return back()->with('error', 'Hanya super admin yang bisa membuat akun super admin.');
        }

        $data['id'] = (string) Str::uuid();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $this->audit->log('create', 'user', $user->id, "Created user {$user->username}");

        return redirect()
            ->route('users.index')
            ->with('success', "User {$user->username} berhasil dibuat.");
    }

    public function edit(User $user)
    {
        $actor = Auth::user();

        // Admin biasa tidak boleh edit super_admin
        if ($actor->role === 'admin' && $user->role === 'super_admin') {
            abort(403, 'Anda tidak berhak mengedit super admin.');
        }

        return Inertia::render('Users/Edit', [
            'user' => $user->only(['id', 'username', 'name', 'email', 'department', 'role']),
            'canPromote' => $actor->role === 'super_admin',
        ]);
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $actor = Auth::user();

        // Admin biasa tidak boleh edit super_admin
        if ($actor->role === 'admin' && $user->role === 'super_admin') {
            abort(403, 'Anda tidak berhak mengedit super admin.');
        }

        $data = $request->validated();

        // Hanya super_admin yang boleh set role ke super_admin
        if ($data['role'] === 'super_admin' && $actor->role !== 'super_admin') {
            return back()->with('error', 'Hanya super admin yang bisa menetapkan role super admin.');
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        $this->audit->log('update', 'user', $user->id, "Updated user {$user->username}");

        return redirect()
            ->route('users.index')
            ->with('success', "User {$user->username} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $actor = Auth::user();

        // 1. Tidak bisa hapus diri sendiri (semua role)
        if ($user->id === $actor->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // 2. Admin (non-super) tidak bisa hapus super_admin
        if ($actor->role === 'admin' && $user->role === 'super_admin') {
            return back()->with('error', 'Anda tidak berhak menghapus super admin.');
        }

        // 3. Cek referensi (dokumen, foto, failure)
        $hasDocs = $user->uploadedDocuments()->exists();
        $hasPhotos = $user->uploadedPhotos()->exists();
        $hasFailures = $user->createdFailures()->exists();

        if ($hasDocs || $hasPhotos || $hasFailures) {
            return back()->with('error', 'Tidak bisa menghapus user yang memiliki dokumen, foto, atau failure terkait.');
        }

        $username = $user->username;
        $user->delete();

        $this->audit->log('delete', 'user', null, "Deleted user {$username}");

        return redirect()
            ->route('users.index')
            ->with('success', "User {$username} berhasil dihapus.");
    }
}