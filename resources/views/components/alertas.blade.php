@props(['errors'])
@if ($errors->any())
<div class="alert alert-danger mt-2">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show mt-2">
    <strong>{{ $message }}</strong>
</div>
@elseif ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show mt-2">
    <strong>{{ $message }}</strong>
</div>
@endif