@php($isAdmin = auth()->user()->isAdmin())

<div class="form-grid">
    <div class="field">
        <label for="candidate_name">Candidate Name</label>
        <input id="candidate_name" name="candidate_name" type="text" value="{{ old('candidate_name', $assignment->candidate_name) }}" required>
        @error('candidate_name')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="field">
        <label for="submission_link">Submission Link</label>
        <input id="submission_link" name="submission_link" type="url" value="{{ old('submission_link', $assignment->submission_link) }}" placeholder="https://example.com/submission" required>
        @error('submission_link')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    @if ($isAdmin)
        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $assignment->status ?? 'pending') === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="reviewer_id">Reviewer</label>
            <select id="reviewer_id" name="reviewer_id">
                <option value="">Select reviewer</option>
                @foreach ($reviewers as $reviewer)
                    <option value="{{ $reviewer->id }}" @selected((string) old('reviewer_id', $assignment->reviewer_id) === (string) $reviewer->id)>
                        {{ $reviewer->name }}
                    </option>
                @endforeach
            </select>
            @error('reviewer_id')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
    @endif

    <div class="field">
        <label for="remarks">Remarks</label>
        <textarea id="remarks" name="remarks" placeholder="Add notes or review context">{{ old('remarks', $assignment->remarks) }}</textarea>
        @error('remarks')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="page-actions">
    <button class="button" type="submit">{{ $submitLabel }}</button>
    <a class="button-link secondary" href="{{ route('assignments.index') }}">Cancel</a>
</div>
