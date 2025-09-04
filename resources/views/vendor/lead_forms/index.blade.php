@extends('vendor.layouts.app')

@section('title', 'Lead Forms')

@section('content')
<div class="container py-3">
  <h2 class="mb-3">Lead Forms</h2>

  <form method="post" action="{{ route('vendor.lead_forms.store') }}" class="mb-3">
    @csrf
    <div class="input-group">
      <input type="text" name="name" class="form-control" placeholder="Form name" required>
      <button class="btn btn-primary" type="submit">Add</button>
    </div>
  </form>

  <div class="card">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Created</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($forms as $form)
            <tr>
              <td>{{ $form->name }}</td>
              <td>{{ $form->created_at->format('Y-m-d H:i') }}</td>
              <td><a href="{{ route('vendor.lead_forms.edit', $form) }}" class="btn btn-sm btn-secondary">Edit</a></td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center py-4">No forms yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
