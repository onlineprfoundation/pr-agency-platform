<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $project->name }}</h2>
            <a href="{{ route('admin.projects.edit', $project) }}" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Edit</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><dt class="text-sm text-gray-500">Client</dt><dd><a href="{{ route('admin.clients.show', $project->client) }}" class="text-blue-600 hover:underline">{{ $project->client->name }}</a></dd></div>
                        <div><dt class="text-sm text-gray-500">Status</dt><dd>{{ ucfirst($project->status) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Value</dt><dd>{{ $project->formatted_value ?? '-' }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Due Date</dt><dd>{{ $project->due_date?->format('M j, Y') ?? '-' }}</dd></div>
                    </dl>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Publications</h3>
                    @if($project->publications->isEmpty())
                        <p class="text-gray-500">No publications selected.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($project->publications as $pub)
                                <li>{{ $pub->name }} – {{ $pub->pivot->price_cents ? '$' . number_format($pub->pivot->price_cents / 100, 2) : 'Quote' }} ({{ $pub->pivot->status }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @if($project->style_guide)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Style / Message Guide</h3>
                    <p class="text-gray-600 whitespace-pre-wrap">{{ $project->style_guide }}</p>
                </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Documents</h3>
                    <form action="{{ route('admin.projects.documents.store', $project) }}" method="POST" enctype="multipart/form-data" class="mb-4 flex gap-2 flex-wrap items-end">
                        @csrf
                        <div>
                            <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-gray-100 file:text-gray-700" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                            @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <input type="text" name="name" placeholder="Display name (optional)" class="rounded-md border-gray-300 shadow-sm">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Upload</button>
                    </form>
                    @if($project->documents->isEmpty())
                        <p class="text-gray-500">No documents yet.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($project->documents as $doc)
                                <li class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                    <span>{{ $doc->name }}</span>
                                    <span class="flex gap-2">
                                        <a href="{{ route('admin.projects.documents.download', [$project, $doc]) }}" class="text-blue-600 hover:underline text-sm">Download</a>
                                        <form action="{{ route('admin.projects.documents.destroy', [$project, $doc]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                        </form>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Messages</h3>
                    <form action="{{ route('admin.projects.messages.store', $project) }}" method="POST" class="mb-4">
                        @csrf
                        <textarea name="content" rows="3" required class="w-full rounded-md border-gray-300 shadow-sm mb-2" placeholder="Send a message..."></textarea>
                        @error('content')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Send</button>
                    </form>
                    @if($project->messages->isEmpty())
                        <p class="text-gray-500">No messages yet.</p>
                    @else
                        <ul class="space-y-3">
                            @foreach($project->messages as $msg)
                                <li class="p-3 bg-gray-50 rounded">
                                    <span class="text-xs text-gray-500">{{ $msg->created_at->format('M j, H:i') }} – {{ $msg->sender_type }}</span>
                                    <p class="mt-1">{{ $msg->content }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Invoices</h3>
                    <form action="{{ route('admin.projects.invoices.store', $project) }}" method="POST" class="mb-4 flex gap-2 flex-wrap items-end">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Amount (cents)</label>
                            <input type="number" name="amount_cents" step="1" min="100" required placeholder="5000 = $50" class="rounded-md border-gray-300 shadow-sm w-32">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Description</label>
                            <input type="text" name="description" placeholder="Optional" class="rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Due date</label>
                            <input type="date" name="due_date" class="rounded-md border-gray-300 shadow-sm">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Create invoice</button>
                    </form>
                    @if($project->invoices->isEmpty())
                        <p class="text-gray-500">No invoices yet.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($project->invoices as $inv)
                                <li class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                    <span>{{ $inv->formatted_amount }} – {{ $inv->status }}</span>
                                    @if(in_array($inv->status, ['draft', 'sent']) && $inv->stripe_payment_link)
                                        <a href="{{ $inv->stripe_payment_link }}" target="_blank" class="text-blue-600 hover:underline text-sm">Payment link</a>
                                    @elseif($inv->status === 'draft')
                                        <form action="{{ route('admin.projects.invoices.send-payment-link', [$project, $inv]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:underline text-sm">Send payment link</button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
