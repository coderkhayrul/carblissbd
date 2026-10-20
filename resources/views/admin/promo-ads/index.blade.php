@extends('admin.layouts.app')
@section('admin-content')
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0">Promo Ad List</h5>
            <a href="{{ route('admin.promo-ads.create') }}" class="btn btn-primary">Add Promo Ad</a>
        </div>
        <div class="table-responsive">
            <table class="data-table table table-bordered align-middle">
                <thead><tr><th>SL</th><th>Title</th><th>Subtitle</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($promoAds as $promoAd)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><div class="d-flex align-items-center gap-3"><img class="rounded" width="64" height="48" style="object-fit: contain;" src="{{ $promoAd->image ? asset($promoAd->image) : asset('backend/assets/extra/preview-image.png') }}" alt="{{ $promoAd->title }}"><span>{{ $promoAd->title }}</span></div></td>
                            <td style="min-width: 180px; max-width: 360px; overflow-wrap: anywhere;">{{ $promoAd->sub_title }}</td>
                            <td>{{ $promoAd->orderBy }}</td>
                            <td class="text-nowrap">
                                <form action="{{ route('admin.promo-ads.status.update', $promoAd) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $promoAd->status ? 0 : 1 }}">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input promo-ad-status" @checked($promoAd->status) aria-label="Toggle status for {{ $promoAd->title }}">
                                        <span class="switch-toggle-slider"></span>
                                        <span class="switch-label">{{ $promoAd->status ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.promo-ads.edit', $promoAd) }}" class="btn btn-sm btn-label-primary">Edit</a>
                                    <form action="{{ route('admin.promo-ads.destroy', $promoAd) }}" method="POST" class="promo-ad-delete" data-name="{{ $promoAd->title }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-label-danger">Delete</button>
                                    </form>
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
        const promoAdTable = document.querySelector('.data-table');
        promoAdTable.addEventListener('change', function(event) {
            const input = event.target.closest('.promo-ad-status');
            if (!input) return;
            input.disabled = true;
            input.form.requestSubmit();
        });
        promoAdTable.addEventListener('submit', function(event) {
            const form = event.target.closest('.promo-ad-delete');
            if (!form) return;
            event.preventDefault();
            Swal.fire({
                title: 'Delete promo ad?',
                text: 'Delete "' + form.dataset.name + '"? This cannot be undone.',
                icon: 'warning', showCancelButton: true, focusCancel: true,
                confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel',
                customClass: {confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary'},
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.querySelector('button').disabled = true;
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });
        new DataTable('.data-table', {
            scrollX: true,
            order: [],
            columnDefs: [{targets: [4, 5], orderable: false}],
            language: {emptyTable: 'No promo ads found.'},
            layout: {
                topStart: {rowClass: 'row m-3 my-0 justify-content-between', features: [{pageLength: {menu: [10, 25, 50, 100], text: 'Show_MENU_entries'}}]},
                topEnd: {search: {placeholder: 'Search promo ads...'}},
                bottomStart: {rowClass: 'row mx-3 justify-content-between', features: ['info']},
                bottomEnd: 'paging'
            }
        });
    </script>
@endpush
