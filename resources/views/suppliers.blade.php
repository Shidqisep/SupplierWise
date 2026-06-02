@extends('layouts.app')

@section('content')
    <livewire:supplier-manager />
@endsection
@if(request('open') === 'create')
<script>
document.addEventListener('DOMContentLoaded', () => {
    openModal('modal-supplier');
});
</script>
@endif