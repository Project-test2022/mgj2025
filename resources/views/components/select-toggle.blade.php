@props([
    'name',
])

<div class="ran-sel">
    <input type="checkbox" name="{{ $name }}" id="{{ $name }}" class="toggle-checkbox hidden">
    <label for="{{ $name }}" class="checkbox-label">
        <img src="" alt="OFF">
    </label>
</div>
