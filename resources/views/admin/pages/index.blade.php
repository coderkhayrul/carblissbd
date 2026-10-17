@extends('admin.layouts.app')
@push('backend_style')
    <style>
        .slide-short-description {
            width: 260px;
            max-width: 260px;
            white-space: normal;
            overflow-wrap: anywhere;
        }
    </style>
@endpush
@section('admin-content')
    <!-- Slider List Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-baseline border-bottom">
            <h5>Page List</h5>
            <div>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary create-new" id="add-new-record">
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
                        <th>Image</th>
                        <th>Seo Title</th>
                        <th>Order By</th>
                        <th>Quick Link</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $row)
                        <tr>
                            <td>
                                <div class="d-flex justify-content-start align-items-center product-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar avatar me-2 me-sm-4 rounded-2 bg-label-secondary"><img
                                                src="{{ $row->image ? asset($row->image) : asset('backend/assets/extra/preview-image.png') }}"
                                                alt="{{ $row->name }}" class="rounded"></div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="text-nowrap mb-0">{{ $row->name }}</h6>
                                        <small class="text-truncate d-none d-sm-block">{{ $row->group_name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $row->meta_title }}
                            </td>
                            <td>{{ $row->orderBy }}</td>
                            <td>
                                <span class="text-truncate">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input page-quick-link-switch" id="page-quickLink-{{ $row->id }}"
                                            name="show_in_quick_links"
                                            data-url="{{ route('admin.pages.quick-link.update', $row->id) }}"
                                            @checked($row->show_in_quick_links)>
                                        <span class="switch-toggle-slider">
                                            <span class="switch-off"></span>
                                        </span>
                                    </label>
                                    <span class="d-none">Status</span>
                                </span>
                            </td>
                            <td>
                                <span class="text-truncate">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input page-status-switch"
                                            id="page-status-{{ $row->id }}" name="status"
                                            data-url="{{ route('admin.pages.status.update', $row->id) }}"
                                            @checked($row->status)>
                                        <span class="switch-toggle-slider">
                                            <span class="switch-off"></span>
                                        </span>
                                    </label>
                                    <span class="d-none">Status</span>
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pages.edit', $row) }}" class="btn btn-icon item-edit"><i
                                        class="icon-base bx bx-edit icon-sm"></i></a>
                                <div class="d-inline-block">
                                    <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end m-0">
                                        <li>
                                            <form method="POST" action="{{ route('admin.pages.destroy', $row->id) }}"
                                                class="delete-form" data-page-name="{{ $row->name }}">
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
                title: 'Delete slider?',
                text: 'Delete "' + form.dataset.sliderName + '" and its image? This cannot be undone.',
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

        // Update each switch independently, including after table pagination.
        $('.data-table').on('change', '.page-status-switch, .page-quick-link-switch', function() {
            const input = this;
            const field = input.name;
            const value = input.checked;
            const label = field === 'status' ? 'Page status' : 'Quick link';
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
                    [field]: value
                }),
                success: function(result) {
                    input.checked = result[field];
                    notyf.success(label + ' updated successfully.');
                },
                error: function() {
                    input.checked = !value;
                    notyf.error('Unable to update ' + label.toLowerCase() + '. Please try again.');
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
