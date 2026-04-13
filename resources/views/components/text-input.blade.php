@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-gracia-primary focus:ring-gracia-primary rounded-xl shadow-sm']) }}>
