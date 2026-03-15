<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Leads
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" class="flex gap-4 mb-6">
                        <select name="status" class="rounded-md border-gray-300">
                            <option value="">All statuses</option>
                            @foreach(\App\Models\Lead::STATUSES as $s)
                                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <select name="source" class="rounded-md border-gray-300">
                            <option value="">All sources</option>
                            <option value="contact" {{ request('source') === 'contact' ? 'selected' : '' }}>Contact</option>
                            <option value="quote" {{ request('source') === 'quote' ? 'selected' : '' }}>Quote</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Filter</button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($leads as $lead)
                                    <tr>
                                        <td class="px-4 py-2">{{ $lead->name }}</td>
                                        <td class="px-4 py-2">{{ $lead->email }}</td>
                                        <td class="px-4 py-2">{{ ucfirst($lead->source ?? '-') }}</td>
                                        <td class="px-4 py-2">{{ ucfirst($lead->status) }}</td>
                                        <td class="px-4 py-2">{{ $lead->created_at->format('M j, Y') }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('admin.leads.show', $lead) }}" class="text-gray-600 hover:text-gray-900">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No leads yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $leads->withQueryString()->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
