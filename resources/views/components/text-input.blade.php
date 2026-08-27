@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-gray-800 border-gray-700 text-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm']) !!}>
