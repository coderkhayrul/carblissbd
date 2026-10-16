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
            <h5>Slider List</h5>
            <div>
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary create-new" id="add-new-record">
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
                        <th>Button Feature</th>
                        <th>Order By</th>
                        <th>Short Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($slides as $row)
                        <tr>
                            <td>
                                <div class="d-flex justify-content-start align-items-center product-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar avatar me-2 me-sm-4 rounded-2 bg-label-secondary"><img
                                                src="{{ asset('backend') }}/assets/img/ecommerce-images/product-9.png"
                                                alt="Product-9" class="rounded"></div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="text-nowrap mb-0">{{ $row->tagline }}</h6>
                                        <small class="text-truncate d-none d-sm-block">{{ $row->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-start align-items-center product-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar avatar me-2 me-sm-4 rounded-2 bg-label-secondary"><img
                                                src="{{ asset('backend') }}/assets/img/ecommerce-images/product-9.png"
                                                alt="Product-9" class="rounded"></div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="text-nowrap mb-0">{{ $row->button_title }}</h6>
                                        <small class="text-truncate d-none d-sm-block">{{ $row->button_url }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $row->orderBy }}</td>
                            <td>
                                <div class="slide-short-description">{{ $row->short_description }}</div>
                            </td>
                            <td>
                                <span class="text-truncate">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input slider-status-switch"
                                            id="slider-status-{{ $row->id }}" name="status"
                                            data-url="{{ route('admin.sliders.status.update', $row->id) }}"
                                            @checked($row->status)>
                                        <span class="switch-toggle-slider">
                                            <span class="switch-off"></span>
                                        </span>
                                    </label>
                                    <span class="d-none">Status</span>
                                </span>
                            </td>
                            <td>
                                <a href="javascript:;" class="btn btn-icon item-edit"><i
                                        class="icon-base bx bx-edit icon-sm"></i></a>
                                <div class="d-inline-block">
                                    <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end m-0">
                                        <li>
                                            <form method="POST" action="#" class="shipping-charge-delete-form">
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
        // STATUS UPDATE
        $('.data-table').on('change', '.slider-status-switch', function() {
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
                    notyf.success('Slider status updated successfully.');
                },
                error: function() {
                    input.checked = !status;
                    notyf.error('Unable to update slider status. Please try again.');
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
