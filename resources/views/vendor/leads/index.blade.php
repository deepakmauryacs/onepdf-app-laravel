@extends('vendor.layouts.app')

@section('title', 'Leads')

@section('content')
<div class="container py-3">
  <h2 class="mb-3">Leads</h2>
  <div class="card">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Document</th>
            <th>Form</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($leads as $lead)
            <tr>
              <td>{{ $lead->name }}</td>
              <td>{{ $lead->email }}</td>
              <td>{{ optional($lead->document)->filename }}</td>
              <td>{{ optional($lead->leadForm)->name }}</td>
              <td>{{ $lead->created_at->format('Y-m-d H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center py-4">No leads found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">
    {{ $leads->links() }}
  </div>
</div>
@endsection
