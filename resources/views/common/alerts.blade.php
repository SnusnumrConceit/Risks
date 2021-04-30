<div class="card-actions">
    @if(count($errors) > 0)
        @include('common.errors', compact('errors'))
    @endif
    @if(session()->has('success'))
        @include('common.success')
    @endif
</div>
