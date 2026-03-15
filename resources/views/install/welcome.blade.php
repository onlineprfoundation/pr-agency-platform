<x-install-layout :step="true">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Welcome</h2>
            <p class="text-gray-600 mt-2">Let's check your server meets the requirements before we begin.</p>
        </div>

        <div class="space-y-6">
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">PHP Requirements</h3>
                <ul class="space-y-2">
                    @foreach($requirements as $req)
                        <li class="flex items-center gap-3">
                            @if($req['passed'])
                                <span class="text-green-600">✓</span>
                            @else
                                <span class="text-red-600">✗</span>
                            @endif
                            <span>{{ $req['name'] }}</span>
                            <span class="text-gray-500 text-sm">{{ $req['current'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Directory Permissions</h3>
                <ul class="space-y-2">
                    @foreach($permissions as $perm)
                        <li class="flex items-center gap-3">
                            @if($perm['passed'])
                                <span class="text-green-600">✓</span>
                            @else
                                <span class="text-red-600">✗</span>
                            @endif
                            <span>{{ $perm['path'] }}</span>
                            @if(!$perm['exists'])
                                <span class="text-red-600 text-sm">Missing</span>
                            @elseif(!$perm['writable'])
                                <span class="text-red-600 text-sm">Not writable</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            @if($passed)
                <a href="{{ route('install.database') }}" class="inline-flex items-center px-6 py-3 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-medium">
                    Continue to Database Setup →
                </a>
            @else
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-md">
                    <p class="text-amber-800 font-medium">Please fix the issues above before continuing.</p>
                    <p class="text-amber-700 text-sm mt-1">Ensure all required PHP extensions are installed and the listed directories are writable.</p>
                </div>
            @endif
        </div>
    </div>
</x-install-layout>
