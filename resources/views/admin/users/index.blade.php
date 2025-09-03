@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
<div class="p-4">
  <h1 class="h3 mb-4">Users</h1>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Company</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->company }}</td>
          <td><a href="{{ route('admin.users.files', $user) }}" class="btn btn-sm btn-dark">Files</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{ $users->links() }}
</div>
@endsection
