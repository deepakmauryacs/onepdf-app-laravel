@extends('vendor.layouts.app')

@section('title', 'File Details')

@section('content')
<div class="container py-3">
  <div class="mb-3"><a href="{{ route('vendor.files.manage') }}" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Back to files</a></div>
  <div class="card p-4">
    @if(session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <h2 class="mb-3">File Details</h2>
    <form method="POST" action="{{ route('vendor.files.update', $doc->id) }}" class="d-flex flex-column flex-sm-row gap-2 mb-4">
      @csrf
      @method('PUT')
      <input type="text" name="filename" value="{{ $doc->filename }}" class="form-control" />
      <button class="btn btn-primary">Save</button>
    </form>
    <p><strong>Size:</strong> {{ number_format($doc->size / 1024, 2) }} KB</p>
    <p><strong>Type:</strong> PDF</p>
    <div class="my-3">
      <label for="leadFormSelect" class="form-label">Lead form</label>
      <select id="leadFormSelect" class="form-select w-auto mb-2">
        <option value="">None</option>
        @foreach($leadForms as $form)
          <option value="{{ $form->id }}">{{ $form->name }}</option>
        @endforeach
      </select>
      <button id="btnGenerate" type="button" class="btn btn-secondary">Generate Link</button>
    </div>
    <div class="input-group mb-3">
      <input id="linkInput" type="text" class="form-control" value="{{ $url }}" readonly />
      <button id="btnCopy" class="btn btn-outline-secondary" type="button">Copy</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('btnGenerate').addEventListener('click', function(){
    const lead = document.getElementById('leadFormSelect').value;
    fetch(@json(route('vendor.files.generate')), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': @json(csrf_token())
      },
      body: JSON.stringify({id: {{ $doc->id }}, lead_form_id: lead})
    }).then(r => r.json()).then(data => {
      if(data.url){
        document.getElementById('linkInput').value = data.url;
      }
    });
  });

  document.getElementById('btnCopy').addEventListener('click', function(){
    const input = document.getElementById('linkInput');
    input.select();
    document.execCommand('copy');
  });
</script>
@endpush
