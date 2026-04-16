@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-600 bg-gray-800 text-white placeholder-gray-300/70 focus:border-gracia-primary focus:ring-gracia-primary rounded-xl shadow-sm']) }}
