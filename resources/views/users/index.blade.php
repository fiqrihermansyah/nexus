@extends('layouts.app')
@section('title', 'User Management')

@section('content')
<div class="p-6" x-data="userManager()">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">User Management</h1>
            <p class="text-sm text-gray-400">Kelola akun pengguna sistem</p>
        </div>
        <button @click="showAddModal = true" class="btn-primary flex items-center gap-2 text-sm">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah User
        </button>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="w-full data-table text-sm">
            <thead>
                <tr class="bg-gray-50/60 border-b border-gray-100">
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Nama</th>
                    <th class="px-5 py-3 text-left">Username</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">Divisi</th>
                    <th class="px-5 py-3 text-left">Role</th>
                    <th class="px-5 py-3 text-left">Bergabung</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $i => $user)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3.5 text-gray-400 mono text-xs">{{ $users->firstItem() + $i }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-700 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-gray-500 mono text-xs">{{ $user->username }}</td>
                    <td class="px-5 py-3.5 text-gray-500">{{ $user->email }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $user->division ?? '—' }}</td>
                    <td class="px-5 py-3.5">
                        <span class="badge {{ $user->role === 'admin' ? 'badge-done' : 'badge-pending' }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1">
                            <button @click="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->division }}')"
                                    class="p-1.5 rounded-lg hover:bg-blue-50 text-gray-400 hover:text-blue-600 transition-colors">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            @if($user->id !== auth()->id())
                            <button @click="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                    class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $users->links() }}</div>
        @endif
    </div>

    <!-- Add Modal -->
    <div x-show="showAddModal" class="fixed inset-0 modal-backdrop flex items-center justify-center z-50" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl p-7 w-full max-w-md mx-4" @click.stop>
            <h3 class="font-bold text-gray-900 mb-5">Tambah User Baru</h3>
            <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Nama</label>
                    <input type="text" name="name" required class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Username</label>
                    <input type="text" name="username" required class="form-input" placeholder="username unik">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Email</label>
                    <input type="email" name="email" required class="form-input">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Role</label>
                        <select name="role" class="form-input">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Divisi</label>
                        <input type="text" name="division" class="form-input" placeholder="Opsional">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Password</label>
                    <input type="password" name="password" required class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="form-input">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" @click="showAddModal = false" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" class="btn-primary flex-1">Tambah User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" class="fixed inset-0 modal-backdrop flex items-center justify-center z-50" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl p-7 w-full max-w-md mx-4" @click.stop>
            <h3 class="font-bold text-gray-900 mb-5">Edit User</h3>
            <form :action="'/users/' + editUserId" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Nama</label>
                    <input type="text" name="name" x-model="editForm.name" required class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Username</label>
                    <input type="text" name="username" x-model="editForm.username" required class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Email</label>
                    <input type="email" name="email" x-model="editForm.email" required class="form-input">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Role</label>
                        <select name="role" x-model="editForm.role" class="form-input">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Divisi</label>
                        <input type="text" name="division" x-model="editForm.division" class="form-input">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Password Baru (kosongkan jika tidak berubah)</label>
                    <input type="password" name="password" class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" @click="showEditModal = false" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" class="btn-primary flex-1">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 modal-backdrop flex items-center justify-center z-50" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4" @click.stop>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Hapus User</h3>
                    <p class="text-sm text-gray-500"><span x-text="deleteUserName"></span> akan dihapus.</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button @click="showDeleteModal = false" class="btn-secondary flex-1">Batal</button>
                <form :action="'/users/' + deleteUserId" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white rounded-lg px-4 py-2 text-sm font-semibold">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function userManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        editUserId: null,
        deleteUserId: null,
        deleteUserName: '',
        editForm: { name: '', username: '', email: '', role: 'user', division: '' },
        editUser(id, name, username, email, role, division) {
            this.editUserId = id;
            this.editForm = { name, username, email, role, division };
            this.showEditModal = true;
        },
        confirmDelete(id, name) {
            this.deleteUserId = id;
            this.deleteUserName = name;
            this.showDeleteModal = true;
        }
    }
}
</script>
@endpush