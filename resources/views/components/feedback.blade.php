@if (session('status'))
    <p role="status" class="rounded-xl bg-emerald-50 px-4 py-3 font-semibold text-emerald-900">{{ session('status') }}</p>
@endif
@if ($errors->any())
    <ul role="alert" class="list-inside list-disc rounded-xl bg-red-50 px-4 py-3 text-red-900">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
