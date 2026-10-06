<x-app-layout>
    <x-slot name="title">Kullanıcı Yönetimi</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Sistem Kullanıcıları (Ustalar)</h2>
            <p class="text-sm text-gray-500">Sisteme erişebilen admin ve şube çalışanları</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 self-start sm:self-auto">+ Yeni Kullanıcı</a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left min-w-[560px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 font-semibold text-gray-500">Ad Soyad</th>
                    <th class="px-5 py-3 font-semibold text-gray-500">E-posta</th>
                    <th class="px-5 py-3 font-semibold text-gray-500">Rol</th>
                    <th class="px-5 py-3 font-semibold text-gray-500">Şube (Dükkan)</th>
                    <th class="px-5 py-3 text-right">İşlem</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="px-5 py-3">
                            @if($user->isAdmin())
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold">Admin</span>
                            @else
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold">Usta</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ $user->branch ? $user->branch->name : '-' }}
                        </td>
                        <td class="px-5 py-3 flex gap-2 justify-end">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:bg-blue-50 px-2 py-1 rounded text-xs">Düzenle</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:bg-red-50 px-2 py-1 rounded text-xs">Sil</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</x-app-layout>
