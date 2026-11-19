@props(['name', 'label', 'type' => 'text', 'hint' => null, 'value' => null])
<div class="flex min-w-0 flex-col gap-1.5">
    <label for="{{ $name }}" class="text-title-sm font-semibold">{{ $label }}</label>
    <input
        {{ $attributes->class(['w-full rounded-lg bg-surface-container-low px-3.5 py-3 text-body-md', 'border-error' => $errors->has($name)]) }}
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
        @if ($errors->has($name) || $hint) aria-describedby="{{ $name }}-help" @endif
        @error($name) aria-invalid="true" @enderror
        required
    >
    @error($name)
        <p id="{{ $name }}-help" class="text-body-sm text-error">{{ $message }}</p>
    @elseif ($hint)
        <p id="{{ $name }}-help" class="text-body-sm text-on-surface-variant">{{ $hint }}</p>
    @enderror
</div>
