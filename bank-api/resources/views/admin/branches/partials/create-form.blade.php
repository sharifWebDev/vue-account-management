<form method="POST" id="createbranchesForm" action="{{ url('api/v1/branches') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row">
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="branch_name">Branch Name</label>
        <input type="text" name="branch_name" class="form-control @error('branch_name') is-invalid @enderror" value="{{ old('branch_name') }}" placeholder="Enter Branch Name..." id="create_branch_name" required>
        @error('branch_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="created">Created</label>
        <input type="datetime-local" name="created" class="form-control @error('created') is-invalid @enderror" value="{{ old('created') }}" id="create_created" required>
        @error('created')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="modified">Modified</label>
        <input type="datetime-local" name="modified" class="form-control @error('modified') is-invalid @enderror" value="{{ old('modified') }}" id="create_modified" required>
        @error('modified')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="status">Status</label>
        <input type="radio" name="status" id="create_status_yes" value="1" {{ old('status') == 1 ? "checked" : "" }} checked> 
                            <label for="create_status_yes">Status Yes</label>
<input type="radio" name="status" id="create_status_no" value="0" {{ old('status') == 0 ? "checked" : "" }}> 
                            <label for="create_status_no">Status No</label>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
        <div class="mb-3 text-right">
            <a type="button" class="btn bg-danger text-white" href="{{ route('admin.branches.index') }}">Cancel</a>
            <button type="submit" id="submitButton" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>