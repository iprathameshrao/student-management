<div class="mb-3">
    <label class="form-label fw-semibold">State</label>
    <select name="{{ $name ?? 'state' }}" class="form-select" required>
        <option value="">-- Select State --</option>

        @foreach ($states as $state)
            <option value="{{ $state }}"
                @selected(isset($selected) && $selected == $state)>
                {{ $state }}
            </option>
        @endforeach
    </select>
</div>
