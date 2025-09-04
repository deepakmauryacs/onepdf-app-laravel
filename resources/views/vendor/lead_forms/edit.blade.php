@extends('vendor.layouts.app')

@section('title', 'Edit Lead Form')

@section('content')
<div class="container py-3">
  <h2 class="mb-3">Edit Lead Form: {{ $form->name }}</h2>

  <div class="row">
    <div class="col-md-4">
      <h5>Fields</h5>
      <div id="field-palette">
        <div class="draggable-field btn btn-light w-100 mb-2" draggable="true" data-type="text">Text</div>
        <div class="draggable-field btn btn-light w-100 mb-2" draggable="true" data-type="email">Email</div>
        <div class="draggable-field btn btn-light w-100 mb-2" draggable="true" data-type="textarea">Textarea</div>
        <div class="draggable-field btn btn-light w-100 mb-2" draggable="true" data-type="number">Number</div>
        <div class="draggable-field btn btn-light w-100 mb-2" draggable="true" data-type="date">Date</div>
        <div class="draggable-field btn btn-light w-100 mb-2" draggable="true" data-type="select">Dropdown</div>
        <div class="draggable-field btn btn-light w-100 mb-2" draggable="true" data-type="radio">Radio</div>
        <div class="draggable-field btn btn-light w-100 mb-2" draggable="true" data-type="checkbox">Checkbox</div>
      </div>
    </div>
    <div class="col-md-8">
      <h5>Form Preview</h5>
      <form id="builder-canvas" class="border p-3" style="min-height:200px;"></form>
    </div>
  </div>

  <form method="post" action="{{ route('vendor.lead_forms.update', $form) }}" class="mt-3">
    @csrf
    @method('put')
    <input type="hidden" name="fields" id="fields-input">
    <button class="btn btn-primary" type="submit">Save</button>
    <a href="{{ route('vendor.lead_forms.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script>
  window.existingFields = @json($form->fields ?? []);
</script>
@endsection

@push('scripts')
  @vite('resources/js/lead-form-builder.js')
@endpush
