<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('portal.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-1 inline-block">← Back to projects</a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $project->name }}</h2>
            </div>
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
                        <div><dt class="text-sm text-gray-500">Status</dt><dd>{{ ucfirst($project->status) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Value</dt><dd>{{ $project->formatted_value ?? '-' }}</dd></div>
                    </dl>
                </div>

                @if($project->publications->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Publications</h3>
                    <ul class="space-y-2">
                        @foreach($project->publications as $pub)
                            <li>{{ $pub->name }} – {{ $pub->pivot->price_cents ? '$' . number_format($pub->pivot->price_cents / 100, 2) : 'Quote' }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Documents</h3>
                    <form action="{{ route('portal.projects.documents.store', $project) }}" method="POST" enctype="multipart/form-data" class="mb-4 flex gap-2 flex-wrap items-end">
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
                                    <a href="{{ route('portal.projects.documents.download', [$project, $doc]) }}" class="text-blue-600 hover:underline text-sm">Download</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Messages</h3>
                    <form action="{{ route('portal.projects.messages.store', $project) }}" method="POST" class="mb-4">
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
            </div>
        </div>
    </div>
</x-app-layout>
