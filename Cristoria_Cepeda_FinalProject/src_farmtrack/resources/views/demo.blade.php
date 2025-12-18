<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmTrack Demo - How It Works</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .step-card {
            transition: all 0.3s ease;
        }

        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .step-number {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }
        .delay-500 { animation-delay: 0.5s; opacity: 0; }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-green-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 0 1-1.161.886l-.143.048a1.107 1.107 0 0 0-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 0 1-1.652.928l-.679-.906a1.125 1.125 0 0 0-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 0 0-8.862 12.872M12.75 3.031a9 9 0 0 1 6.69 14.036m0 0-.177-.529A2.25 2.25 0 0 0 17.128 15H16.5l-.324-.324a1.453 1.453 0 0 0-2.328.377l-.036.073a1.586 1.586 0 0 1-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 0 1-5.276 3.67m0 0a9 9 0 0 1-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25" />
                    </svg>
                    <span class="text-2xl font-bold text-gray-900">FarmTrack</span>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('welcome') }}"
                        class="text-gray-700 hover:text-green-600 px-5 py-2 rounded-lg font-medium transition">
                        Back to Home
                    </a>
                    @guest
                        <a href="{{ route('welcome') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition shadow-md">
                            Get Started
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-green-600 to-green-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in-up">
                How FarmTrack Works
            </h1>
            <p class="text-xl md:text-2xl text-green-100 max-w-3xl mx-auto animate-fade-in-up delay-100">
                A simple step-by-step guide to managing your farm with FarmTrack
            </p>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Step 1 -->
            <div class="step-card bg-white rounded-2xl shadow-lg p-8 md:p-12 mb-12 animate-fade-in-up delay-100">
                <div class="flex flex-col md:flex-row items-start gap-8">
                    <div class="step-number flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        1
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Create Your Farmer Profile</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Set up your profile with personal info, farm location, and contact details.
                        </p>
                        <div class="mt-6">
                            <img src="{{ asset('images/demo-step1.png') }}" alt="Farmer Profile Creation Form" class="rounded-lg shadow-md w-full">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step-card bg-white rounded-2xl shadow-lg p-8 md:p-12 mb-12 animate-fade-in-up delay-200">
                <div class="flex flex-col md:flex-row items-start gap-8">
                    <div class="step-number flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        2
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Register Your Crops</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Add crops with type, dates, area, growth stage, and photos.
                        </p>
                        <div class="mt-6">
                            <img src="{{ asset('images/demo-step2.png') }}" alt="Crop Registration Form" class="rounded-lg shadow-md w-full">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step-card bg-white rounded-2xl shadow-lg p-8 md:p-12 mb-12 animate-fade-in-up delay-300">
                <div class="flex flex-col md:flex-row items-start gap-8">
                    <div class="step-number flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        3
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Track Progress Regularly</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Log observations, update growth stages, add photos, and record treatments.
                        </p>
                        <div class="mt-6">
                            <img src="{{ asset('images/demo-step3.png') }}" alt="Progress Log Entry Form" class="rounded-lg shadow-md w-full">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="step-card bg-white rounded-2xl shadow-lg p-8 md:p-12 mb-12 animate-fade-in-up delay-400">
                <div class="flex flex-col md:flex-row items-start gap-8">
                    <div class="step-number flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        4
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Monitor Your Dashboard</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            View all crops, upcoming harvests, progress logs, and farm statistics.
                        </p>
                        <div class="mt-6">
                            <img src="{{ asset('images/demo-step4.png') }}" alt="Farmer Dashboard Overview" class="rounded-lg shadow-md w-full">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="step-card bg-white rounded-2xl shadow-lg p-8 md:p-12 mb-12 animate-fade-in-up delay-500">
                <div class="flex flex-col md:flex-row items-start gap-8">
                    <div class="step-number flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        5
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Harvest & Report Results</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Update crop status to "Harvested" and record yield data with photos.
                        </p>
                        <div class="mt-6">
                            <img src="{{ asset('images/demo-step5.png') }}" alt="Harvest Recording Form" class="rounded-lg shadow-md w-full">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-green-600 py-20">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Start Managing Your Farm?</h2>
            <p class="text-xl text-green-100 mb-8">
                Join FarmTrack today and take control of your agricultural operations with our easy-to-use platform.
            </p>
            @guest
                <a href="{{ route('welcome') }}"
                    class="bg-white text-green-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition shadow-lg inline-flex items-center">
                    Get Started Now
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            @else
                <a href="{{ route('dashboard') }}"
                    class="bg-white text-green-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition shadow-lg inline-flex items-center">
                    Go to Dashboard
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-500">© 2025 FarmTrack. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>
