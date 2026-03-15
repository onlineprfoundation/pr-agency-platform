<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Lead: {{ $lead->name }}
            </h2>
            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4">
                        <div><dt class="text-sm text-gray-500">Name</dt><dd>{{ $lead->name }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Email</dt><dd><a href="mailto:{{ $lead->email }}" class="text-blue-600 hover:underline">{{ $lead->email }}</a></dd></div>
                        <div><dt class="text-sm text-gray-500">Phone</dt><dd>{{ $lead->phone ?? '-' }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Company</dt><dd>{{ $lead->company ?? '-' }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Source</dt><dd>{{ ucfirst($lead->source ?? '-') }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Status</dt><dd>{{ ucfirst($lead->status) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Created</dt><dd>{{ $lead->created_at->format('M j, Y H:i') }}</dd></div>
                        @if($lead->message)
                            <div class="col-span-full"><dt class="text-sm text-gray-500">Message</dt><dd class="mt-1 p-3 bg-gray-50 rounded">{{ nl2br(e($lead->message)) }}</dd></div>
                        @endif
                        @if($lead->notes)
                            <div class="col-span-full"><dt class="text-sm text-gray-500">Notes</dt><dd class="mt-1 p-3 bg-gray-50 rounded">{{ nl2br(e($lead->notes)) }}</dd></div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-medium text-gray-900 mb-4">Update lead</h3>
                    <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300">
                                @foreach(\App\Models\Lead::STATUSES as $s)
                                    <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300">{{ old('notes', $lead->notes) }}</textarea>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
