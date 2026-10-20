
<div class="mt-20 flex justify-center">
    <div class="w-4xl rounded-lg bg-white p-4 shadow-md">
        <h1 class="mb-4 text-2xl font-bold">Health Status</h1>

        <div class="font-mono">
            @foreach ($checks as $label => $healthy)
                <p class="flex font-bold">{{ str_pad($label, 90, '.') }}
                    <span @class(['text-green-700' => $healthy, 'text-red-700' => ! $healthy])>{{ $healthy ? 'OK' : 'NOT OK' }}</span>
                </p>
            @endforeach
        </div>
    </div>
</div>
