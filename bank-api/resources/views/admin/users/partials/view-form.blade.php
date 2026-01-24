<form><div class="row"><div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label><br><input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $query->first_name ?? "") }}" placeholder="Enter First Name..." id="view_first_name" disabled>@error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label><br><input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $query->last_name ?? "") }}" placeholder="Enter Last Name..." id="view_last_name" disabled>@error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="address">Address</label><br><textarea name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Enter Address..." id="view_address" disabled>{{ old('address', $query->address ?? "") }}</textarea>@error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="phone">Phone</label><br><input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $query->phone ?? "") }}" placeholder="Enter Phone..." id="view_phone" disabled>@error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label><br><input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $query->mobile ?? "") }}" placeholder="Enter Mobile..." id="view_mobile" disabled>@error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Email</label><br><input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $query->email ?? "") }}" placeholder="Enter Email..." id="view_email" disabled>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="password">Password</label><br><input type="text" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password', $query->password ?? "") }}" placeholder="Enter Password..." id="view_password" disabled>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="date_of_birth">Date Of Birth</label><br><input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $query->date_of_birth ?? "") }}" placeholder="Enter Date Of Birth..." id="view_date_of_birth" disabled>@error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="joining_date">Joining Date</label><br><input type="date" name="joining_date" class="form-control @error('joining_date') is-invalid @enderror" value="{{ old('joining_date', $query->joining_date ?? "") }}" placeholder="Enter Joining Date..." id="view_joining_date" disabled>@error('joining_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="image">Image</label><br><input type="file" name="image" class="form-control @error('image') is-invalid @enderror" value="{{ old('image', $query->image ?? "") }}" id="view_image" disabled>@error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="facebook">Facebook</label><br><input type="text" name="facebook" class="form-control @error('facebook') is-invalid @enderror" value="{{ old('facebook', $query->facebook ?? "") }}" placeholder="Enter Facebook..." id="view_facebook" disabled>@error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="twitter">Twitter</label><br><input type="text" name="twitter" class="form-control @error('twitter') is-invalid @enderror" value="{{ old('twitter', $query->twitter ?? "") }}" placeholder="Enter Twitter..." id="view_twitter" disabled>@error('twitter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="instagram">Instagram</label><br><input type="text" name="instagram" class="form-control @error('instagram') is-invalid @enderror" value="{{ old('instagram', $query->instagram ?? "") }}" placeholder="Enter Instagram..." id="view_instagram" disabled>@error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="google_plus">Google Plus</label><br><input type="text" name="google_plus" class="form-control @error('google_plus') is-invalid @enderror" value="{{ old('google_plus', $query->google_plus ?? "") }}" placeholder="Enter Google Plus..." id="view_google_plus" disabled>@error('google_plus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="linkedin">Linkedin</label><br><input type="text" name="linkedin" class="form-control @error('linkedin') is-invalid @enderror" value="{{ old('linkedin', $query->linkedin ?? "") }}" placeholder="Enter Linkedin..." id="view_linkedin" disabled>@error('linkedin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="company_ids">Company Ids</label><br><input type="text" name="company_ids" class="form-control @error('company_ids') is-invalid @enderror" value="{{ old('company_ids', $query->company_ids ?? "") }}" placeholder="Enter Company Ids..." id="view_company_ids" disabled>@error('company_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="user_type">User Type</label><br><input type="text" name="user_type" class="form-control @error('user_type') is-invalid @enderror" value="{{ old('user_type', $query->user_type ?? "") }}" placeholder="Enter User Type..." id="view_user_type" disabled>@error('user_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="created">Created</label><br><input type="datetime-local" name="created" class="form-control @error('created') is-invalid @enderror" value="{{ old('created', $query->created ?? "") }}" placeholder="Enter Created..." id="view_created" disabled>@error('created')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="modified">Modified</label><br><input type="datetime-local" name="modified" class="form-control @error('modified') is-invalid @enderror" value="{{ old('modified', $query->modified ?? "") }}" placeholder="Enter Modified..." id="view_modified" disabled>@error('modified')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="status">Status</label><br><input type="radio" name="status" value="1" {{ old('status', $query->status ?? "") == 1 ? "checked" : "" }} id="view_status_yes" disabled> 
                                   <label for="view_status_yes" disabled>Status Yes </label>
                                   <input type="radio" name="status" value="0" {{ old('status', $query->status ?? "") == 0 ? "checked" : "" }} id="view_status_no" disabled> 
                                   <label for="view_status_no" disabled>Status No </label>@error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div><div class="mb-3 text-right">
                              <a type="button" class="btn bg-danger text-white" href="{{ route('admin.users.index') }}">Close</a>
                          </div>
                      </div>
                  </form>