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
        return Inertia::render('Users/Create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
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
        return Inertia::render('Users/Edit', [
            'user' => $user->only(['id', 'username', 'name', 'email', 'department', 'role']),
        ]);
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $data = $request->validated();

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
        // Tidak bisa hapus diri sendiri
        if ($user->id === Auth::id()) {
            return back()->withErrors([
                'delete' => 'Anda tidak bisa menghapus akun Anda sendiri.',
            ]);
        }

        // Restrict: cek referensi
        $hasDocs = $user->uploadedDocuments()->exists();
        $hasPhotos = $user->uploadedPhotos()->exists();
        $hasFailures = $user->createdFailures()->exists();

        if ($hasDocs || $hasPhotos || $hasFailures) {
            return back()->withErrors([
                'delete' => 'Tidak bisa menghapus user yang memiliki dokumen, foto, atau failure terkait.',
            ]);
        }

        $username = $user->username;
        $user->delete();

        $this->audit->log('delete', 'user', null, "Deleted user {$username}");

        return redirect()
            ->route('users.index')
            ->with('success', "User {$username} berhasil dihapus.");
    }
}