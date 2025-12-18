<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Farm Track System - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-green-50 to-blue-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 0 1-1.161.886l-.143.048a1.107 1.107 0 0 0-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 0 1-1.652.928l-.679-.906a1.125 1.125 0 0 0-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 0 0-8.862 12.872M12.75 3.031a9 9 0 0 1 6.69 14.036m0 0-.177-.529A2.25 2.25 0 0 0 17.128 15H16.5l-.324-.324a1.453 1.453 0 0 0-2.328.377l-.036.073a1.586 1.586 0 0 1-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 0 1-5.276 3.67m0 0a9 9 0 0 1-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Farm Track</h1>
                        <p class="text-sm text-gray-600">Monitoring System</p>
                    </div>
                </div>
            </div>

            <div class="w-full sm:max-w-md px-6 py-6 bg-white border border-gray-200 shadow-lg overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Default Login: admin@gmail.com / admin_1010</p>
            </div>
        </div>
        
        <!-- Security: Redirect to login if accessing cached authenticated pages -->
        <script>
            (function() {
                // Check if user was logged out and trying to go back
                @if(session('logged_out'))
                    // Clear any cached pages and prevent back navigation
                    if (window.history && window.history.pushState) {
                        window.history.pushState(null, null, window.location.href);
                        window.onpopstate = function() {
                            window.history.pushState(null, null, window.location.href);
                        };
                    }
                @endif

                // Prevent form resubmission on back button
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.href);
                }
            })();
        </script>
    </body>
</html>
