@extends('admin.layouts.app')
@push('backend_style')
    <style>
        .users-datatable {
            min-width: 0;
            max-width: 100%;
        }

        .users-datatable .dt-scroll-body {
            -webkit-overflow-scrolling: touch;
        }
    </style>
@endpush
@section('admin-content')
    <div class="row g-6 mb-6">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Session</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">21,459</h4>
                                <p class="text-success mb-0">(+29%)</p>
                            </div>
                            <small class="mb-0">Total Users</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="icon-base bx bx-group icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Paid Users</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">4,567</h4>
                                <p class="text-success mb-0">(+18%)</p>
                            </div>
                            <small class="mb-0">Last week analytics </small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="icon-base bx bx-user-plus icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Active Users</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">19,860</h4>
                                <p class="text-danger mb-0">(-14%)</p>
                            </div>
                            <small class="mb-0">Last week analytics</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="icon-base bx bx-user-check icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Pending Users</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">237</h4>
                                <p class="text-success mb-0">(+42%)</p>
                            </div>
                            <small class="mb-0">Last week analytics</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="icon-base bx bx-user-voice icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User List Table -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h5 class="card-title">All User List</h5>
            <button class="btn create-new btn-primary" tabindex="0" type="button"
                aria-controls="offcanvasAddUser"><span><i class="icon-base bx bx-plus icon-sm me-0 me-sm-2"></i><span
                        class="d-none d-sm-inline-block">Add New User</span></span></button>
        </div>
        <div class="card-datatable users-datatable text-nowrap">
            <table class="users-table table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Mobile</th>
                        <th>User Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $row)
                        <tr>
                            <td>
                                <div class="d-flex justify-content-start align-items-center user-name">

                                    @if ($row->image)
                                        <div class="avatar-wrapper">
                                            <div class="avatar avatar-sm me-4">
                                                <img src="{{ asset($row->image) }}" alt="Avatar" class="rounded-circle">
                                            </div>
                                        </div>
                                    @else
                                        <div class="avatar-wrapper">
                                            <div class="avatar avatar-sm me-4"><span
                                                    class="avatar-initial rounded-circle bg-label-danger">{{ Str::limit($row->name, 2, '') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-column">
                                        <a href="" class="text-heading text-truncate">
                                            <span class="fw-medium"> {{ $row->name }}</span>
                                        </a>
                                        <small>{{ $row->email }}</small>
                                    </div>
                                </div>
                            </td>

                            <td><span class="text-heading">{{ $row->mobile }}</span></td>
                            <td><span class="text-heading">{{ $row->username ?? 'N/A' }}</span></td>
                            <td>
                                <span class="text-truncate d-flex align-items-center text-heading"><i
                                        class="icon-base bx bx-user text-success me-2"></i>{{ Str::ucfirst($row->role) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $row->status ? 'success' : 'danger' }}"
                                    text-capitalized="">{{ $row->status ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <form class="delete-user-form" method="POST"
                                        action="{{ route('admin.users.destroy', $row->id) }}"
                                        data-user-name="{{ $row->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon delete-user" aria-label="Delete user">
                                            <i class="icon-base bx bx-trash icon-md"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.users.show', $row->id) }}" class="btn btn-icon">
                                        <i class="icon-base bx bx-show icon-md"></i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded icon-md"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="javascript:;" class="dropdown-item edit-button"
                                            data-update-url="{{ route('admin.users.update', $row->id) }}"
                                            data-user="{{ json_encode($row->only(['id', 'name', 'email', 'mobile', 'username', 'address', 'status', 'image'])) }}">Edit</a>
                                        <form class="suspend-user-form" method="POST"
                                            action="{{ route('admin.users.suspend', $row->id) }}"
                                            data-user-name="{{ $row->name }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item suspend-btn"
                                                @disabled(!$row->status)>Suspend</button>
                                        </form>
                                        <form class="unsuspend-user-form" method="POST"
                                            action="{{ route('admin.users.unsuspend', $row->id) }}"
                                            data-user-name="{{ $row->name }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item unsuspend-btn"
                                                @disabled($row->status)>Unsuspend</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Offcanvas to add new user -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
        <div class="offcanvas-header border-bottom">
            <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
            <form class="add-new-user pt-0" id="addNewUserForm" action="{{ route('admin.users.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="name">Full Name</label>
                    <input type="text" class="form-control" id="name" placeholder="John Doe" name="name"
                        aria-label="John Doe" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" class="form-control" placeholder="john.doe@example.com"
                        aria-label="email" name="email" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Enter Password"
                        aria-label="password" name="password" />
                </div>
                <div class="mb-6">
                    <label class="form-label" for="mobile">Mobile</label>
                    <input type="text" id="mobile" class="form-control phone-mask" placeholder="017xxxxxxxx"
                        aria-label="mobile" name="mobile" />
                </div>
                <div class="mb-6">
                    <label class="form-label" for="username">User Name</label>
                    <input type="text" id="username" class="form-control" placeholder="User Name"
                        aria-label="username" name="username" />
                </div>
                <div class="mb-6">
                    <label class="form-label" for="image">Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="mb-6">
                    <label class="form-label" for="address">Address</label>
                    <textarea class="form-control" name="address" id="" rows="3"></textarea>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" class="form-select" name="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary me-3 data-submit">Submit</button>
                <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Offcanvas To Edit User -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser" aria-labelledby="offcanvasEditUserLabel">
        <div class="offcanvas-header border-bottom">
            <h5 id="offcanvasEditUserLabel" class="offcanvas-title">Edit User</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
            <form class="edit-user pt-0" id="addEditUserForm" action="" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_user_id" id="edit-user-id">
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="edit-name">Full Name</label>
                    <input type="text" class="form-control" id="edit-name" placeholder="John Doe" name="name"
                        aria-label="John Doe" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="edit-email">Email</label>
                    <input type="email" id="edit-email" class="form-control" placeholder="john.doe@example.com"
                        aria-label="email" name="email" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="edit-password">Password</label>
                    <input type="password" id="edit-password" class="form-control"
                        placeholder="Leave blank to keep current password" aria-label="password" name="password" />
                </div>
                <div class="mb-6">
                    <label class="form-label" for="edit-mobile">Mobile</label>
                    <input type="text" id="edit-mobile" class="form-control phone-mask" placeholder="017xxxxxxxx"
                        aria-label="mobile" name="mobile" />
                </div>
                <div class="mb-6">
                    <label class="form-label" for="edit-username">User Name</label>
                    <input type="text" id="edit-username" class="form-control" placeholder="User Name"
                        aria-label="username" name="username" />
                </div>
                <div class="mb-6">
                    <label class="form-label" for="edit-image">Image</label>
                    <input type="file" id="edit-image" name="image" accept="image/*" class="form-control">
                    <img id="edit-user-image-preview" class="rounded mt-3" width="80" height="80"
                        alt="Current user image" hidden>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="edit-address">Address</label>
                    <textarea class="form-control" name="address" id="edit-address" rows="3"></textarea>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="edit-status">Status</label>
                    <select id="edit-status" class="form-select" name="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                @if ($errors->editUser->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->editUser->all() as $message)
                            <div>{{ $message }}</div>
                        @endforeach
                    </div>
                @endif
                <button type="submit" class="btn btn-primary me-3 data-submit">Submit</button>
                <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Cancel</button>
            </form>
        </div>
    </div>
@endsection

@push('backend_script')
    <script>
        const addUserForm = document.querySelector('#addNewUserForm');
        addUserForm.noValidate = true;

        const userValidationRules = {
            name: {
                label: 'Full Name',
                required: true,
                max: 255
            },
            email: {
                label: 'Email',
                required: true,
                max: 255
            },
            password: {
                label: 'Password',
                required: true,
                max: 255
            },
            mobile: {
                label: 'Mobile',
                required: true,
                max: 20
            },
            username: {
                label: 'User Name',
                required: false,
                max: 20
            }
        };

        function validateUserField(field, rules = userValidationRules) {
            const rule = rules[field.name];
            const value = field.name === 'password' ? field.value : field.value.trim();
            let message = '';

            if (rule.required && !value.trim()) {
                message = rule.label + ' is required.';
            } else if (value.length > rule.max) {
                message = rule.label + ' must not exceed ' + rule.max + ' characters.';
            } else if (value && field.name === 'email' && field.validity.typeMismatch) {
                message = 'Please enter a valid email address.';
            } else if (value && field.name === 'mobile' && !/^\+?[0-9]+(?:[ -][0-9]+)*$/.test(value)) {
                message = 'Please enter a valid mobile number using digits and an optional + prefix.';
            } else if (field.name === 'status' && !['0', '1'].includes(value)) {
                message = 'Please select a valid status.';
            } else if (field.name === 'image' && field.files.length) {
                const image = field.files[0];
                if (!['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/x-ms-bmp'].includes(image
                        .type)) {
                    message = 'Please choose a JPG, PNG, GIF, WebP or BMP image.';
                } else if (image.size > 2048 * 1024) {
                    message = 'Image must not exceed 2 MB.';
                }
            }

            let feedback = field.parentElement.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                field.insertAdjacentElement('afterend', feedback);
            }
            feedback.id = field.id + '-error';
            feedback.setAttribute('aria-live', 'polite');
            feedback.textContent = message;
            field.classList.toggle('is-invalid', Boolean(message));
            field.setAttribute('aria-invalid', String(Boolean(message)));
            field.setAttribute('aria-describedby', feedback.id);
            return !message;
        }

        Object.entries(userValidationRules).forEach(function([name, rule]) {
            const field = addUserForm.elements.namedItem(name);
            field.required = rule.required;
            field.maxLength = rule.max;
            field.addEventListener('blur', function() {
                validateUserField(field);
            });
            field.addEventListener('input', function() {
                if (field.classList.contains('is-invalid')) validateUserField(field);
            });
        });

        addUserForm.addEventListener('submit', function(event) {
            let firstInvalid = null;
            Object.keys(userValidationRules).forEach(function(name) {
                const field = addUserForm.elements.namedItem(name);
                if (!validateUserField(field) && !firstInvalid) firstInvalid = field;
            });
            if (firstInvalid) {
                event.preventDefault();
                firstInvalid.focus();
            }
        });

        addUserForm.addEventListener('reset', function() {
            Object.keys(userValidationRules).forEach(function(name) {
                const field = addUserForm.elements.namedItem(name);
                field.classList.remove('is-invalid');
                field.removeAttribute('aria-invalid');
                field.removeAttribute('aria-describedby');
                const feedback = field.parentElement.querySelector('.invalid-feedback');
                if (feedback) feedback.textContent = '';
            });
        });

        // Open the Add User panel from the create button.
        document.querySelectorAll('.create-new').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const offcanvas = document.querySelector('#offcanvasAddUser');
                bootstrap.Offcanvas.getOrCreateInstance(offcanvas).show(button);
            });
        });
        const editUserForm = document.querySelector('#addEditUserForm');
        document.querySelector('.users-table').addEventListener('submit', function(event) {
            const form = event.target.closest('.unsuspend-user-form');
            if (!form) return;
            event.preventDefault();
            const button = form.querySelector('.unsuspend-btn');
            if (button.disabled) return;
            button.disabled = true;
            Swal.fire({
                title: 'Unsuspend user?',
                text: 'Are you sure you want to unsuspend "' + form.dataset.userName + '"?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, unsuspend!',
                cancelButtonText: 'Cancel',
                focusCancel: true,
                customClass: {
                    confirmButton: 'btn btn-success me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    HTMLFormElement.prototype.submit.call(form);
                } else {
                    button.disabled = false;
                }
            });
        });
        document.querySelector('.users-table').addEventListener('submit', function(event) {
            const form = event.target.closest('.suspend-user-form');
            if (!form) return;
            event.preventDefault();
            const button = form.querySelector('.suspend-btn');
            if (button.disabled) return;
            button.disabled = true;
            Swal.fire({
                title: 'Suspend user?',
                text: 'Are you sure you want to suspend "' + form.dataset.userName + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, suspend!',
                cancelButtonText: 'Cancel',
                focusCancel: true,
                customClass: {
                    confirmButton: 'btn btn-warning me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    HTMLFormElement.prototype.submit.call(form);
                } else {
                    button.disabled = false;
                }
            });
        });
        document.querySelector('.users-table').addEventListener('submit', function(event) {
            const form = event.target.closest('.delete-user-form');
            if (!form) return;
            event.preventDefault();
            const button = form.querySelector('.delete-user');
            if (button.disabled) return;
            button.disabled = true;
            Swal.fire({
                title: 'Delete user?',
                text: 'Are you sure you want to delete "' + form.dataset.userName +
                    '"? This cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                focusCancel: true,
                customClass: {
                    confirmButton: 'btn btn-danger me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    HTMLFormElement.prototype.submit.call(form);
                } else {
                    button.disabled = false;
                }
            });
        });
        document.querySelector('.users-table').addEventListener('click', function(event) {
            const button = event.target.closest('.edit-button');
            if (!button) return;
            event.preventDefault();
            const user = JSON.parse(button.dataset.user);
            editUserForm.reset();
            editUserForm.action = button.dataset.updateUrl;
            editUserForm.elements.edit_user_id.value = user.id;
            ['name', 'email', 'mobile', 'username', 'address', 'status'].forEach(function(name) {
                editUserForm.elements.namedItem(name).value = user[name] ?? '';
            });
            editUserForm.elements.password.value = '';
            const preview = document.querySelector('#edit-user-image-preview');
            preview.hidden = !user.image;
            if (user.image) {
                preview.src = @json(asset('/')) + user.image.replace(/^\/+/, '');
            } else {
                preview.removeAttribute('src');
            }
            bootstrap.Offcanvas.getOrCreateInstance(document.querySelector('#offcanvasEditUser')).show(button);
        });
        const editValidationRules = {
            ...userValidationRules,
            password: {
                ...userValidationRules.password,
                required: false
            },
            address: {
                label: 'Address',
                required: true,
                max: 500
            },
            status: {
                label: 'Status',
                required: true
            },
            image: {
                label: 'Image',
                required: false
            }
        };
        editUserForm.noValidate = true;
        Object.entries(editValidationRules).forEach(function([name, rule]) {
            const field = editUserForm.elements.namedItem(name);
            field.required = rule.required;
            if (rule.max) field.maxLength = rule.max;
            field.addEventListener('blur', function() {
                validateUserField(field, editValidationRules);
            });
            field.addEventListener('input', function() {
                if (field.classList.contains('is-invalid')) validateUserField(field, editValidationRules);
            });
            field.addEventListener('change', function() {
                validateUserField(field, editValidationRules);
            });
        });
        editUserForm.addEventListener('submit', function(event) {
            let firstInvalid = null;
            Object.keys(editValidationRules).forEach(function(name) {
                const field = editUserForm.elements.namedItem(name);
                if (!validateUserField(field, editValidationRules) && !firstInvalid) firstInvalid = field;
            });
            if (firstInvalid) {
                event.preventDefault();
                firstInvalid.focus();
            }
        });
        editUserForm.addEventListener('reset', function() {
            Object.keys(editValidationRules).forEach(function(name) {
                const field = editUserForm.elements.namedItem(name);
                field.classList.remove('is-invalid');
                field.removeAttribute('aria-invalid');
                field.removeAttribute('aria-describedby');
                const feedback = field.parentElement.querySelector('.invalid-feedback');
                if (feedback) feedback.textContent = '';
            });
        });

        @if ($errors->editUser->any() && old('edit_user_id'))
            editUserForm.action = @json(route('admin.users.update', old('edit_user_id')));
            const previousEdit = @json(old());
            ['edit_user_id', 'name', 'email', 'mobile', 'username', 'address', 'status'].forEach(function(name) {
                editUserForm.elements.namedItem(name).value = previousEdit[name] ?? '';
            });
            bootstrap.Offcanvas.getOrCreateInstance(document.querySelector('#offcanvasEditUser')).show();
        @endif

        // USERS TABLE
        new DataTable('.users-table', {
            scrollX: true,
            sort: false,
            layout: {
                topStart: {
                    rowClass: 'row m-3 my-0 justify-content-between',
                    features: [{
                        pageLength: {
                            menu: [10, 25, 50, 100],
                            text: 'Show_MENU_entries'
                        }
                    }]
                },
                topEnd: {
                    search: {
                        placeholder: ''
                    }
                },
                bottomStart: {
                    rowClass: 'row mx-3 justify-content-between',
                    features: ['info']
                }
            },
        });
    </script>
@endpush
