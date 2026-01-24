<form method="POST" id="createusersForm" action="{{ url('api/v1/users') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row">
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" placeholder="Enter First Name..." id="create_first_name" required>
        @error('first_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" placeholder="Enter Last Name..." id="create_last_name" required>
        @error('last_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="address">Address</label>
        <textarea name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Enter Address..." id="create_address" required>{{ old('address') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Enter Phone..." id="create_phone" required>
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="mobile">Mobile</label>
        <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" placeholder="Enter Mobile..." id="create_mobile" required>
        @error('mobile')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter Email..." id="create_email" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="password">Password</label>
        <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="Enter Password..." id="create_password" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="date_of_birth">Date Of Birth</label>
        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}" id="create_date_of_birth" required>
        @error('date_of_birth')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="joining_date">Joining Date</label>
        <input type="date" name="joining_date" class="form-control @error('joining_date') is-invalid @enderror" value="{{ old('joining_date') }}" id="create_joining_date" required>
        @error('joining_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" value="{{ old('image') }}" id="create_image" required>
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="facebook">Facebook</label>
        <input type="text" name="facebook" class="form-control @error('facebook') is-invalid @enderror" value="{{ old('facebook') }}" placeholder="Enter Facebook..." id="create_facebook" required>
        @error('facebook')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="twitter">Twitter</label>
        <input type="text" name="twitter" class="form-control @error('twitter') is-invalid @enderror" value="{{ old('twitter') }}" placeholder="Enter Twitter..." id="create_twitter" required>
        @error('twitter')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="instagram">Instagram</label>
        <input type="text" name="instagram" class="form-control @error('instagram') is-invalid @enderror" value="{{ old('instagram') }}" placeholder="Enter Instagram..." id="create_instagram" required>
        @error('instagram')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="google_plus">Google Plus</label>
        <input type="text" name="google_plus" class="form-control @error('google_plus') is-invalid @enderror" value="{{ old('google_plus') }}" placeholder="Enter Google Plus..." id="create_google_plus" required>
        @error('google_plus')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="linkedin">Linkedin</label>
        <input type="text" name="linkedin" class="form-control @error('linkedin') is-invalid @enderror" value="{{ old('linkedin') }}" placeholder="Enter Linkedin..." id="create_linkedin" required>
        @error('linkedin')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="company_ids">Company Ids</label>
        <input type="text" name="company_ids" class="form-control @error('company_ids') is-invalid @enderror" value="{{ old('company_ids') }}" placeholder="Enter Company Ids..." id="create_company_ids" required>
        @error('company_ids')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
    <div class="form-group">
        <label for="user_type">User Type</label>
        <input type="text" name="user_type" class="form-control @error('user_type') is-invalid @enderror" value="{{ old('user_type') }}" placeholder="Enter User Type..." id="create_user_type" required>
        @error('user_type')
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
            <a type="button" class="btn bg-danger text-white" href="{{ route('admin.users.index') }}">Cancel</a>
            <button type="submit" id="submitButton" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>