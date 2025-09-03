@extends('vendor.layouts.app')

@section('title', 'Notifications')

@section('content')
  <div class="bw-band">
    <ol class="bw-crumb">
      <li><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
      <li>Notifications</li>
    </ol>
  </div>

  <div class="card">
    <div class="card-header"><i class="bi bi-bell me-1"></i> Notifications</div>
    <div class="card-body">
      @if($notifications->isEmpty())
        <p class="text-muted mb-0">No notifications found.</p>
      @else
        <ul class="list-group mb-3">
          @foreach($notifications as $note)
            <li class="list-group-item">
              <div class="d-flex justify-content-between">
                <div>
                  <strong>{{ $note->title }}</strong>
                  <div class="small text-muted">{{ $note->created_at?->format('Y-m-d H:i') }}</div>
                  <p class="mb-0">{{ $note->message }}</p>
                </div>
                @if($note->action_url)
                  <a href="{{ $note->action_url }}" class="btn btn-sm btn-primary">View</a>
                @endif
              </div>
            </li>
          @endforeach
        </ul>
        {{ $notifications->links() }}
      @endif
    </div>
  </div>
@endsection

