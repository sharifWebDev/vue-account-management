<form><div class="row"><div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="bank_name">Bank Name</label><br><input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $query->bank_name ?? "") }}" placeholder="Enter Bank Name..." id="view_bank_name" disabled>@error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                        <label for="fax">Fax</label><br><input type="text" name="fax" class="form-control @error('fax') is-invalid @enderror" value="{{ old('fax', $query->fax ?? "") }}" placeholder="Enter Fax..." id="view_fax" disabled>@error('fax')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Email</label><br><input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $query->email ?? "") }}" placeholder="Enter Email..." id="view_email" disabled>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    </div>
<div class="mb-3 col-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="website">Website</label><br><input type="text" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $query->website ?? "") }}" placeholder="Enter Website..." id="view_website" disabled>@error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                              <a type="button" class="btn bg-danger text-white" href="{{ route('admin.banks.index') }}">Close</a>
                          </div>
                      </div>
                  </form>