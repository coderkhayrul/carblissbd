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
        </div>
        <div class="card-datatable users-datatable text-nowrap">
            <table class="data-table table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Mobile</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Register From</th>
                        <th>Lifetime Purchase</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $row)
                        <tr>
                            <td>
                                <div class="d-flex justify-content-start align-items-center user-name">

                                    @if ($row->image)
                                        <div class="avatar-wrapper">
                                            <div class="avatar avatar-sm me-4">
                                                <img src="{{ asset($row->image) }}" alt="Profile Image"
                                                    class="rounded-circle">
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
                                        <a href="#" class="text-heading text-truncate">
                                            <span class="fw-medium"> {{ $row->name }}</span>
                                        </a>
                                        <small>{{ $row->email ?? 'Not Available' }}</small>
                                    </div>
                                </div>
                            </td>

                            <td><span class="text-heading">{{ $row->mobile }}</span></td>
                            <td><span class="text-heading">{{ $row->gender ?? 'N/A' }}</span></td>
                            <td><span class="text-heading">{{ Str::limit($row->address, 50, '...') ?? 'N/A' }}</span></td>
                            <td><span class="text-heading">{{ $row->register_from ?? 'N/A' }}</span></td>
                            <td><span class="text-heading">{{ $row->lifetime_purchase ?? 'N/A' }}</span></td>

                            <td>
                                <span class="badge bg-label-{{ $row->status ? 'success' : 'danger' }}"
                                    text-capitalized="">{{ $row->status ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">

                                    <a href="{{ route('admin.customers.show', $row->id) }}" class="btn btn-icon">
                                        <i class="icon-base bx bx-show icon-md"></i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded icon-md"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="#sendSmsModal" class="dropdown-item send-sms" data-bs-toggle="modal"
                                            data-bs-target="#sendSmsModal" data-customer-name="{{ $row->name }}"
                                            data-customer-mobile="{{ $row->mobile }}">Send SMS</a>
                                        <a href="javascript:;" class="dropdown-item ">Purchase History</a>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="sendSmsModal" tabindex="-1" aria-labelledby="sendSmsModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
            <div class="modal-content">
                <form id="sendSmsForm" class="d-flex flex-column overflow-hidden">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sendSmsModalLabel">Send SMS</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label" for="smsCustomerName">Customer</label>
                            <input type="text" class="form-control" id="smsCustomerName" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="smsCustomerMobile">Mobile Number</label>
                            <input type="text" class="form-control" id="smsCustomerMobile" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="smsMessage">Message</label>
                            <textarea class="form-control" id="smsMessage" name="message" rows="6"
                                placeholder="Write your SMS message here..." required maxlength="1000" aria-describedby="smsCharacterCount"
                                style="resize: vertical; min-height: 150px;"></textarea>
                            <div class="form-text text-end" id="smsCharacterCount" aria-live="polite">0 / 1000 characters
                            </div>
                        </div>
                        <div class="alert alert-danger mb-0 d-none" id="smsError" role="alert"></div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn-label-secondary m-0"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary m-0" id="sendSmsButton">
                            <i class="bx bx-send me-2"></i> Send SMS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('backend_script')
    <script>
        const smsModal = document.getElementById('sendSmsModal');
        const smsForm = document.getElementById('sendSmsForm');
        const smsMessage = document.getElementById('smsMessage');
        const smsError = document.getElementById('smsError');
        const smsButton = document.getElementById('sendSmsButton');

        smsModal.addEventListener('show.bs.modal', function(event) {
            const trigger = event.relatedTarget;
            smsForm.reset();
            smsError.classList.add('d-none');
            document.getElementById('smsCharacterCount').textContent = '0 / 1000 characters';
            document.getElementById('smsCustomerName').value = trigger.dataset.customerName || '';
            document.getElementById('smsCustomerMobile').value = trigger.dataset.customerMobile || '';
            smsButton.disabled = !trigger.dataset.customerMobile;
            if (smsButton.disabled) {
                smsError.textContent = 'This customer does not have a mobile number.';
                smsError.classList.remove('d-none');
            }
        });

        smsModal.addEventListener('shown.bs.modal', function() {
            smsMessage.focus();
        });

        smsMessage.addEventListener('input', function() {
            smsMessage.setCustomValidity('');
            document.getElementById('smsCharacterCount').textContent = smsMessage.value.length +
                ' / 1000 characters';
        });

        smsForm.addEventListener('submit', function(event) {
            event.preventDefault();
            if (!smsMessage.value.trim()) {
                smsMessage.setCustomValidity('Please enter a message.');
                smsMessage.reportValidity();
                return;
            }
            smsError.textContent = 'SMS sending is not configured yet. Please connect your SMS gateway.';
            smsError.classList.remove('d-none');
        });

        // USERS TABLE
        new DataTable('.data-table', {
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
