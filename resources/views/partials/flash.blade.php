@include('partials.flash-toast')

@if ($errors->any())
    <div class="mb-4 rounded-lg border px-4 py-3 text-sm"
         style="border-color:rgba(192,57,43,0.25); background:rgba(192,57,43,0.06); color:#c0392b;">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
