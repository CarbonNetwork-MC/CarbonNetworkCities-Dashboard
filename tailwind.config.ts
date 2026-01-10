import type { Config } from 'tailwindcss'

export default {
  darkMode: 'class',
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './vendor/masmerise/livewire-toaster/resources/views/*.blade.php',
  ],
} satisfies Config