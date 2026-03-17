<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('portal.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-1 inline-block">← Back to portal</a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $project->name }}</h2>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium
                @if($project->status === 'active') bg-green-100 text-green-800
                @elseif($project->status === 'completed') bg-blue-100 text-blue-800
                @elseif($project->status === 'review') bg-amber-100 text-amber-800
                @else bg-gray-100 text-gray-700
                @endif">
                {{ ucfirst($project->status) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-lg">{{ session('success') }}</div>
            @endif

            <div class="grid lg:grid-cols-3 gap-6">
                {{-- Left: Overview --}}
                <div class="lg:col-span-2 space-y-6">
                    @if($project->publications->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="font-semibold text-gray-900">Publications</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Your placements for this project</p>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            @foreach($project->publications as $pub)
                                <li class="px-6 py-4 flex items-center justify-between">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $pub->name }}</span>
                                        @if($pub->genre || $pub->region)
                                            <span class="text-sm text-gray-500 ml-2">{{ $pub->genre }} @if($pub->region) · {{ $pub->region }} @endif</span>
                                        @endif
                                    </div>
                                    <span class="font-medium text-gray-700">
                                        @if($pub->pivot->price_cents)
                                            ${{ number_format($pub->pivot->price_cents / 100, 2) }}
                                        @else
                                            Quote
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="font-semibold text-gray-900">Documents</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Upload and download project files</p>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('portal.projects.documents.store', $project) }}" method="POST" enctype="multipart/form-data" class="mb-6 p-4 bg-gray-50 rounded-lg">
                                @csrf
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <div class="flex-1">
                                        <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-200 file:text-gray-700" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                                        @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <input type="text" name="name" placeholder="Display name (optional)" class="rounded-lg border-gray-300 shadow-sm">
                                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium">Upload</button>
                                </div>
                            </form>
                            @if($project->documents->isEmpty())
                                <p class="text-gray-500">No documents yet.</p>
                            @else
                                <ul class="space-y-2">
                                    @foreach($project->documents as $doc)
                                        <li class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50">
                                            <span class="text-gray-400">📄</span>
                                            <span class="flex-1 ml-2">{{ $doc->name }}</span>
                                            <a href="{{ route('portal.projects.documents.download', [$project, $doc]) }}" class="text-blue-600 hover:underline text-sm font-medium">Download</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="font-semibold text-gray-900">Messages</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Communicate with your team</p>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('portal.projects.messages.store', $project) }}" method="POST" class="mb-6">
                                @csrf
                                <textarea name="content" rows="3" required class="w-full rounded-lg border-gray-300 shadow-sm" placeholder="Send a message..."></textarea>
                                @error('content')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                <button type="submit" class="mt-2 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium">Send</button>
                            </form>
                            @if($project->messages->isEmpty())
                                <p class="text-gray-500">No messages yet.</p>
                            @else
                                <ul class="space-y-3">
                                    @foreach($project->messages as $msg)
                                        <li class="p-4 rounded-lg {{ $msg->sender_type === 'client' ? 'bg-blue-50 ml-4' : 'bg-gray-50 mr-4' }}">
                                            <span class="text-xs text-gray-500">{{ $msg->created_at->format('M j, H:i') }} · {{ ucfirst($msg->sender_type) }}</span>
                                            <p class="mt-1 text-gray-800">{{ $msg->content }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Summary --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Project Summary</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-500">Status</dt>
                                <dd class="font-medium">{{ ucfirst($project->status) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Value</dt>
                                <dd class="font-medium">{{ $project->formatted_value ?? 'Quote' }}</dd>
                            </div>
                            @if($project->due_date)
                            <div>
                                <dt class="text-sm text-gray-500">Due Date</dt>
                                <dd class="font-medium">{{ $project->due_date->format('M j, Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
