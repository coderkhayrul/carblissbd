@extends('admin.layouts.app')
@push('backend_style')
@endpush
@section('admin-content')
    <!-- Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-baseline border-bottom">
            <h5>Social Link List</h5>
            <div>
                <a href="{{ route('admin.social-links.create') }}" class="btn btn-primary create-new" id="add-new-record">
                    <span class="d-flex align-items-center gap-2"><i class="icon-base bx bx-plus icon-sm"></i>
                        <span class="d-none d-sm-inline-block">Add New Record</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="card-datatable text-nowrap">
            <table class="data-table table table-bordered">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Url</th>
                        <th>Icon</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($socials as $key => $row)
                        <tr>
                            <td>
                                {{ $key + 1 }}
                            </td>
                            <td>
                                {{ $row->name }}
                            </td>
                            <td>{{ $row->url }}</td>
                            <td>{{ Str::limit($row->icon, 50, '...') }}</td>
                            <td>
                                <span class="text-truncate">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input social-status-switch"
                                            id="social-status-{{ $row->id }}" name="status"
                                            data-url="{{ route('admin.social-links.status.update', $row->id) }}"
                                            @checked($row->status)>
                                        <span class="switch-toggle-slider">
                                            <span class="switch-off"></span>
                                        </span>
                                    </label>
                                    <span class="d-none">Status</span>
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.social-links.edit', $row) }}" class="btn btn-icon item-edit"><i
                                        class="icon-base bx bx-edit icon-sm"></i></a>
                                <div class="d-inline-block">
                                    <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end m-0">
                                        <li>
                                            <form method="POST"
                                                action="{{ route('admin.social-links.destroy', $row->id) }}"
                                                class="delete-form" data-social-name="{{ $row->name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="dropdown-item text-danger delete-record">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('backend_script')
    <script>
        document.querySelector('.data-table').addEventListener('submit', function(event) {
            const form = event.target.closest('.delete-form');
            if (!form) return;

            event.preventDefault();
            Swal.fire({
                title: 'Delete Social Link?',
                text: 'Delete "' + form.dataset.socialName + '"? This cannot be undone.',
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
                    form.querySelector('.delete-record').disabled = true;
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });

        // STATUS UPDATE
        $('.data-table').on('change', '.social-status-switch', function() {
            const input = this;
            const status = input.checked;
            input.disabled = true;

            $.ajax({
                url: input.dataset.url,
                method: 'PATCH',
                contentType: 'application/json',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': @json(csrf_token())
                },
                data: JSON.stringify({
                    status
                }),
                success: function(result) {
                    input.checked = result.status;
                    notyf.success('Social Link status updated successfully.');
                },
                error: function() {
                    input.checked = !status;
                    notyf.error('Unable to update Social Link status. Please try again.');
                },
                complete: function() {
                    input.disabled = false;
                }
            });
        });

        // SLIDER TABLE
        new DataTable('.data-table', {
            scrollX: true,
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
