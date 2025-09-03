@extends('admin.layouts.app')

@section('title', 'User Files')

@section('content')
<div class="p-4">
  <h1 class="h3 mb-4">Files for {{ $user->name }}</h1>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Filename</th>
          <th>Size</th>
          <th>Updated</th>
        </tr>
      </thead>
      <tbody>
        @forelse($documents as $doc)
        <tr>
          <td>{{ $doc->filename }}</td>
          <td>{{ number_format($doc->size) }} bytes</td>
          <td>{{ $doc->updated_at }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="text-muted text-center">No files found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $documents->links() }}
</div>
@endsection
