<div class="container m-0 p-0">
        <!-- Modern Table with Export & Column Visibility -->
         <div class="dt-container m-0 p-0">
            <table id="leadType" class="table table-modern"
                data-dt
                data-title="Users List"
                data-index-url="{{ url('api/v1/users') }}"
                data-create-url="{{ url('admin/users/create') }}"
                data-edit-url="{{ route('admin.users.edit', ':id') }}"
                data-delete-url="{{ url('api/v1/users/destroy') }}/:id"
                data-show-url="{{ route('admin.users.show', ':id') }}"
                data-fields='["first_name", "last_name", "address", "phone", "mobile", "email", "password", "date_of_birth", "joining_date", "image", "facebook", "twitter", "instagram", "google_plus", "linkedin", "company_ids", "user_type", "created", "modified", "status"]'
                data-headers='["First Name", "Last Name", "Address", "Phone", "Mobile", "Email", "Password", "Date Of Birth", "Joining Date", "Image", "Facebook", "Twitter", "Instagram", "Google Plus", "Linkedin", "Company Ids", "User Type", "Created", "Modified", "Status"]'
                data-filters=''
                {{-- data-filters=']' --}}
                data-export="true"
                data-colvis="true"
                data-csv="true"
                data-excel="true"
                data-pdf="true"
                data-print="true">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>

        {{-- <!-- Quick Actions -->
        <div class="mt-3">
            <button class="btn btn-primary me-2" onclick="dtHelpers.exportData('User', 'excel')">
                <i class="fas fa-file-excel me-1"></i>Export Excel
            </button>
        </div> --}}
    </div>


    <script>
       // Custom status renderer for boolean status values
        setTimeout(() => {
            if (window.dtHelpers) {
                dtHelpers.registerRenderer('User', 'status', function(data, row) {
                    if (data === true || data === 1 || data === '1') {
                        return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>';
                    } else if (data === false || data === 0 || data === '0') {
                        return '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactive</span>';
                    }
                    return '<span class="badge bg-secondary">' + data + '</span>';
                });
            }
        }, 1000);
    </script>
    {{-- @include('components.datatables'); --}}
    