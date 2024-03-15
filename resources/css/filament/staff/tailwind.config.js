import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Staff/**/*.php',
        './resources/views/filament/staff/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/awcodes/overlook/resources//views/**/*.blade.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php'
    ],
}
