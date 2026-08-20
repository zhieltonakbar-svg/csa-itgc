@props([
    'width' => '100%',
    'height' => '100%',
    'class' => ''
])

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" fill="none" class="{{ $class }}" style="width: {{ $width }}; height: {{ $height }};">
  <!-- Green rounded square background -->
  <rect width="36" height="36" rx="8" ry="8" fill="#2d9e55"/>

  <!-- Shield outline (white) -->
  <path d="M18 7 L27 10.5 V18 C27 23.5 22.5 28 18 29.5 C13.5 28 9 23.5 9 18 V10.5 L18 7 Z"
        fill="none"
        stroke="#ffffff"
        stroke-width="1.8"
        stroke-linejoin="round"/>

  <!-- Checkmark (white) -->
  <path d="M13.5 18 L16.5 21 L22.5 14"
        stroke="#ffffff"
        stroke-width="2.2"
        stroke-linecap="round"
        stroke-linejoin="round"
        fill="none"/>
</svg>
