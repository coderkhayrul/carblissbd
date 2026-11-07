<div class="card mt-4"><h5 class="card-header">Recent SMS Messages</h5><div class="table-responsive"><table class="table"><thead><tr><th>Recipient / Date</th><th>Message</th><th>Status</th></tr></thead><tbody>
    @forelse ($smsMessages as $sms)
        <tr><td>{{ $sms->phone }}<small class="d-block text-muted text-nowrap">{{ \Illuminate\Support\Carbon::parse($sms->created_at)->timezone('Asia/Dhaka')->format('d M Y h:i A') }}</small></td><td style="min-width:180px;white-space:pre-line;overflow-wrap:anywhere">{{ $sms->message }}</td><td>{{ ucfirst($sms->status) }}@if ($sms->error)<small class="d-block text-danger">{{ $sms->error }}</small>@endif</td></tr>
    @empty<tr><td colspan="3" class="text-center py-4">No SMS messages sent yet.</td></tr>@endforelse
</tbody></table></div></div>
