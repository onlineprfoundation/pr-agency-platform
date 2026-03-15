<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Module Marketplace</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <p class="mb-6 text-gray-600">Browse and manage modules. Enable or disable modules to extend platform functionality.</p>

            <div class="space-y-4">
                @foreach($available as $identifier => $info)
                    @php
                        $module = $installed[$identifier] ?? null;
                        $enabled = $module && $module->enabled;
                    @endphp
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $info['name'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $identifier }} · v{{ $info['version'] ?? '1.0.0' }}</p>
                        </div>
                        <div>
                            @if($enabled)
                                <form action="{{ route('admin.modules.disable') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="identifier" value="{{ $identifier }}">
                                    <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Disable</button>
                                </form>
                            @else
                                <form action="{{ route('admin.modules.enable') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="identifier" value="{{ $identifier }}">
                                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Enable</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if(empty($available))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center text-gray-500">
                    <p>No modules available. Modules are registered by the application.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
