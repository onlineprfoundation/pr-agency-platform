<div>
    <label for="client_id" class="block text-sm font-medium text-gray-700">Client *</label>
    <select name="client_id" id="client_id" required class="mt-1 block w-full rounded-md border-gray-300">
        <option value="">Select client</option>
        @foreach($clients as $c)
            <option value="{{ $c->id }}" {{ old('client_id', $project->client_id ?? $selectedClientId ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->email }})</option>
        @endforeach
    </select>
</div>
<div>
    <label for="name" class="block text-sm font-medium text-gray-700">Project Name *</label>
    <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required class="mt-1 block w-full rounded-md border-gray-300">
</div>
<div>
    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300">
        @foreach(\App\Models\Project::STATUSES as $s)
            <option value="{{ $s }}" {{ old('status', $project->status ?? 'draft') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
</div>
<div>
    <label for="value_dollars" class="block text-sm font-medium text-gray-700">Value (USD)</label>
    <input type="number" name="value_dollars" id="value_dollars" value="{{ old('value_dollars', $project->value_cents ? $project->value_cents / 100 : '') }}" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300" placeholder="e.g. 99.99">
    <p class="mt-1 text-xs text-gray-500">Enter amount in dollars (e.g. 99.99)</p>
</div>
<div>
    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $project->due_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300">
</div>
<div>
    <label for="publications" class="block text-sm font-medium text-gray-700">Publications (select one or multiple)</label>
    <select name="publications[]" id="publications" multiple class="mt-1 block w-full rounded-md border-gray-300" size="8">
        @foreach($publications as $pub)
            <option value="{{ $pub->id }}" {{ in_array($pub->id, old('publications', $project->publications->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                {{ $pub->name }} {{ $pub->formatted_price ? "({$pub->formatted_price})" : '' }}
            </option>
        @endforeach
    </select>
    <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple</p>
</div>
<div>
    <label for="style_guide" class="block text-sm font-medium text-gray-700">Style / Message Guide</label>
    <textarea name="style_guide" id="style_guide" rows="3" class="mt-1 block w-full rounded-md border-gray-300">{{ old('style_guide', $project->style_guide) }}</textarea>
</div>
<div>
    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
    <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300">{{ old('notes', $project->notes) }}</textarea>
</div>
