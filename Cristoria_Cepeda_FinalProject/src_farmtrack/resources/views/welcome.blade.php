<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>FarmTrack - Monitor Your Farm, Grow Your Success</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/farmer-demo.js'])
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.6) 0%, rgba(22, 101, 52, 0.6) 100%),
                url('https://i.pinimg.com/1200x/40/73/d4/4073d4378fad895860ee2285fedf3a74.jpg') center/cover no-repeat;
        }

        .hero-pattern {
            background-image:
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        }

        /* When both classes are applied, keep the photo visible beneath the pattern */
        .hero-gradient.hero-pattern {
            background:
                linear-gradient(135deg, rgba(104, 110, 106, 0.504) 0%, rgba(216, 227, 220, 0.084) 100%),
                url('https://i.pinimg.com/736x/5f/13/61/5f136137410ed81df130e97c45156068.jpg') center/cover no-repeat;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .hero-text {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-buttons {
            position: relative;
            z-index: 40;
        }

        .hero-buttons button,
        .hero-buttons a {
            cursor: pointer;
            position: relative;
            z-index: 31;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            animation: slideUp 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .stat-card {
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* Ensure overlay container never blocks clicks if Tailwind utilities are missing */
        .pointer-events-none {
            pointer-events: none;
        }

        /* Demo Tutorial Styles */
        .demo-highlight {
            position: relative;
            z-index: 9997;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.5), 0 0 0 9999px rgba(0, 0, 0, 0.7);
            border-radius: 1rem;
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-slideUp {
            animation: slideUp 0.4s ease-out;
        }
    </style>
</head>

<body class="bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-green-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 0 1-1.161.886l-.143.048a1.107 1.107 0 0 0-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 0 1-1.652.928l-.679-.906a1.125 1.125 0 0 0-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 0 0-8.862 12.872M12.75 3.031a9 9 0 0 1 6.69 14.036m0 0-.177-.529A2.25 2.25 0 0 0 17.128 15H16.5l-.324-.324a1.453 1.453 0 0 0-2.328.377l-.036.073a1.586 1.586 0 0 1-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 0 1-5.276 3.67m0 0a9 9 0 0 1-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25" />
                    </svg>
                    <span class="text-2xl font-bold text-gray-900">FarmTrack</span>
                </div>

                <!-- Center Navigation Links -->
                <div class="hidden md:flex items-center space-x-10">
                    <a href="#features" class="text-gray-600 hover:text-green-600 font-medium transition">Features</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-green-600 font-medium transition">How It Works</a>
                    <a href="#contact" class="text-gray-600 hover:text-green-600 font-medium transition">Contact</a>
                </div>

                <!-- Right Side Buttons -->
                <div class="flex items-center space-x-3 hero-buttons">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-700 hover:text-green-600 px-5 py-2 rounded-lg font-medium transition">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium transition shadow-md">
                                Logout
                            </button>
                        </form>
                    @else
                        <button onclick="openModal('loginModal')"
                            class="text-gray-700 hover:text-green-600 px-5 py-2 rounded-lg font-medium transition">
                            Sign In
                        </button>
                        <button onclick="openModal('registerModal')"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition shadow-md">
                            Get Started
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section
        class="hero-gradient hero-pattern min-h-[600px] flex items-center justify-center text-center px-4 relative overflow-hidden">
        <!-- Floating Farm Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 opacity-20 float-animation" style="animation-delay: 0s;">
                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2M12 8C10.9 8 10 8.9 10 10S10.9 12 12 12 14 11.1 14 10 13.1 8 12 8M12 14C10.34 14 9 15.34 9 17S10.34 20 12 20 15 18.66 15 17 13.66 14 12 14M7 18C6.45 18 6 18.45 6 19S6.45 20 7 20 8 19.55 8 19 7.55 18 7 18M17 18C16.45 18 16 18.45 16 19S16.45 20 17 20 18 19.55 18 19 17.55 18 17 18Z" />
                </svg>
            </div>
            <div class="absolute top-32 right-20 opacity-20 float-animation" style="animation-delay: 1s;">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2M12 8C10.9 8 10 8.9 10 10S10.9 12 12 12 14 11.1 14 10 13.1 8 12 8M12 14C10.34 14 9 15.34 9 17S10.34 20 12 20 15 18.66 15 17 13.66 14 12 14M7 18C6.45 18 6 18.45 6 19S6.45 20 7 20 8 19.55 8 19 7.55 18 7 18M17 18C16.45 18 16 18.45 16 19S16.45 20 17 20 18 19.55 18 19 17.55 18 17 18Z" />
                </svg>
            </div>
            <div class="absolute bottom-32 left-20 opacity-20 float-animation" style="animation-delay: 2s;">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2M12 8C10.9 8 10 8.9 10 10S10.9 12 12 12 14 11.1 14 10 13.1 8 12 8M12 14C10.34 14 9 15.34 9 17S10.34 20 12 20 15 18.66 15 17 13.66 14 12 14M7 18C6.45 18 6 18.45 6 19S6.45 20 7 20 8 19.55 8 19 7.55 18 7 18M17 18C16.45 18 16 18.45 16 19S16.45 20 17 20 18 19.55 18 19 17.55 18 17 18Z" />
                </svg>
            </div>
            <div class="absolute bottom-20 right-10 opacity-20 float-animation" style="animation-delay: 0.5s;">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2M12 8C10.9 8 10 8.9 10 10S10.9 12 12 12 14 11.1 14 10 13.1 8 12 8M12 14C10.34 14 9 15.34 9 17S10.34 20 12 20 15 18.66 15 17 13.66 14 12 14M7 18C6.45 18 6 18.45 6 19S6.45 20 7 20 8 19.55 8 19 7.55 18 7 18M17 18C16.45 18 16 18.45 16 19S16.45 20 17 20 18 19.55 18 19 17.55 18 17 18Z" />
                </svg>
            </div>
        </div>

        <div class="max-w-4xl relative z-20">
            <h1 class="font-bold text-white mb-6 hero-text" style="font-size: 10rem; line-height: 1; text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.5), 2px 2px 4px rgba(0, 0, 0, 0.3);">
                FarmTrack
            </h1>
            <p class="text-2xl md:text-4xl text-white mb-8 max-w-2xl mx-auto font-semibold" style="text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.6), 1px 1px 3px rgba(0, 0, 0, 0.4);">
                Monitor Your Farm, Grow Your Success
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center hero-buttons">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-lg text-lg font-semibold transition shadow-lg flex items-center justify-center">
                        Go to Dashboard
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('demo') }}"
                        class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-lg text-lg font-semibold transition shadow-lg flex items-center justify-center">
                        Browse
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-green-500 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                <div class="stat-card">
                    <div id="stat-total-farmers" class="text-5xl font-bold mb-2">
                        {{ number_format($stats['total_farmers'] ?? 0) }}+</div>
                    <div class="text-lg">Active Farmers</div>
                </div>
                <div class="stat-card">
                    <div id="stat-total-farm-area" class="text-5xl font-bold mb-2">
                        {{ number_format($stats['total_farm_area'] ?? 0, 1) }}+</div>
                    <div class="text-lg">Hectares Monitored</div>
                </div>
                <div class="stat-card">
                    <div id="stat-active-crops" class="text-5xl font-bold mb-2">
                        {{ number_format($stats['active_crops'] ?? 0) }}+</div>
                    <div class="text-lg">Active Crops</div>
                </div>
                <div class="stat-card">
                    <div id="stat-total-logs" class="text-5xl font-bold mb-2">
                        {{ number_format($stats['total_logs'] ?? 0) }}+</div>
                    <div class="text-lg">Progress Logs</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Everything You Need to Succeed</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Powerful features designed to help you monitor, manage, and grow your agricultural operations.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Farmer Management -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Farmer Management</h3>
                    <p class="text-gray-600">Track and manage all farmers in your network with detailed profiles and
                        activity logs.</p>
                </div>

                <!-- Crop Monitoring -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Crop Monitoring</h3>
                    <p class="text-gray-600">Real-time monitoring of crop growth, health, and harvest schedules across
                        all fields.</p>
                </div>

                <!-- Analytics Dashboard -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Analytics Dashboard</h3>
                    <p class="text-gray-600">Comprehensive insights and reports to make data-driven agricultural
                        decisions.</p>
                </div>

                <!-- Location Tracking -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Location Tracking</h3>
                    <p class="text-gray-600">GPS-enabled field mapping and location-based monitoring for precise
                        management.</p>
                </div>

                <!-- Smart Alerts -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Smart Alerts</h3>
                    <p class="text-gray-600">Get notified about critical events, weather changes, and important
                        milestones.</p>
                </div>

                <!-- Yield Optimization -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Yield Optimization</h3>
                    <p class="text-gray-600">Maximize productivity with AI-powered recommendations and best practices.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="how-it-works" class="bg-blue-600 py-20">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Transform Your Farm Management?</h2>
            <p class="text-xl text-blue-100 mb-8">
                Join thousands of farmers who are already using FarmTrack to monitor their crops and increase
                productivity.
            </p>
            @auth
                <a href="{{ route('dashboard') }}"
                    class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition shadow-lg inline-flex items-center">
                    Go to Dashboard
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            @else
                <button onclick="openModal('registerModal')"
                    class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition shadow-lg inline-flex items-center">
                    Get Started Now
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-white border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-8">
                <div class="md:col-span-1">
                    <div class="flex items-center space-x-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 0 1-1.161.886l-.143.048a1.107 1.107 0 0 0-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 0 1-1.652.928l-.679-.906a1.125 1.125 0 0 0-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 0 0-8.862 12.872M12.75 3.031a9 9 0 0 1 6.69 14.036m0 0-.177-.529A2.25 2.25 0 0 0 17.128 15H16.5l-.324-.324a1.453 1.453 0 0 0-2.328.377l-.036.073a1.586 1.586 0 0 1-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 0 1-5.276 3.67m0 0a9 9 0 0 1-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25" />
                        </svg>
                        <span class="text-xl font-bold text-gray-900">FarmTrack</span>
                    </div>
                    <p class="text-gray-600 text-sm">The complete solution for monitoring farmers and managing crops with precision and ease.</p>
                </div>

                <div class="ml-[100px]">
                    <h3 class="font-bold text-gray-900 mb-4">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-gray-600 hover:text-green-600 transition">Features</a></li>
                        <li><a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-green-600 transition">Dashboard</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-600 transition">FAQ</a></li>
                    </ul>
                </div>

                <div class="ml-[50px]">
                    <h3 class="font-bold text-gray-900 mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-600 hover:text-green-600 transition">About</a></li>
                        <li><a href="#contact" class="text-gray-600 hover:text-green-600 transition">Contact</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-600 transition">Privacy</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 mb-4">Contact</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="tel:+639123456789" class="text-gray-600 hover:text-green-600 transition flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                                +63 912 345 6789
                            </a>
                        </li>
                        <li>
                            <a href="mailto:farmtrack.noreply@gmail.com" class="text-gray-600 hover:text-green-600 transition flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                farmtrack.noreply@gmail.com
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-8 mb-[-20px] text-center">
                <p class="text-gray-500">© 2025 FarmTrack. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Sign In</h2>
                <button onclick="closeModal('loginModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                @if ($errors->has('email') && !old('name'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-600 text-sm">{{ $errors->first('email') }}</p>
                    </div>
                @endif
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @if($errors->has('email') && !old('name')) border-red-500 @endif">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition mb-4">
                    Sign In
                </button>

                <div class="text-center">
                    <p class="text-gray-600 text-sm">
                        Don't have an account?
                        <button type="button" onclick="switchModal('loginModal', 'registerModal')"
                            class="text-green-600 hover:text-green-700 font-semibold">
                            Register here
                        </button>
                    </p>
                    <p class="text-gray-600 text-sm mt-2">
                        <button type="button" onclick="switchModal('loginModal', 'forgotModal')"
                            class="text-green-600 hover:text-green-700 font-semibold">
                            Forgot Password?
                        </button>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="registerModal" class="modal">
        <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
                <button onclick="closeModal('registerModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="registerForm" action="{{ route('register') }}" method="POST" onsubmit="validateRegisterForm(event)">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Full Name</label>
                    <input type="text" name="name" id="fullName" value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p id="nameError" class="text-red-500 text-xs mt-1" style="display:none;"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                    <input type="email" name="email" id="registerEmail" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p id="emailError" class="text-red-500 text-xs mt-1" style="display:none;"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Register as</label>
                    <select name="role_id" required disabled
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed">
                        @foreach(\App\Models\Role::whereIn('slug', ['farmer'])->get() as $role)
                            <option value="{{ $role->id }}" selected>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @foreach(\App\Models\Role::whereIn('slug', ['farmer'])->get() as $role)
                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                    @endforeach
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                    <input type="password" name="password" id="registerPassword"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" oninput="checkPasswordStrength()">
                    <p id="passwordError" class="text-red-500 text-xs mt-1" style="display:none;"></p>
                    <div id="passwordStrength" class="mt-2 text-xs font-semibold" style="display:none;">
                        <div class="flex gap-1">
                            <div id="strengthBar1" class="h-1 flex-1 bg-gray-300 rounded"></div>
                            <div id="strengthBar2" class="h-1 flex-1 bg-gray-300 rounded"></div>
                            <div id="strengthBar3" class="h-1 flex-1 bg-gray-300 rounded"></div>
                        </div>
                        <p id="strengthText" class="mt-1"></p>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="confirmPassword"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p id="confirmPasswordError" class="text-red-500 text-xs mt-1" style="display:none;"></p>
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition mb-4">
                    Create Account
                </button>

                <div class="text-center">
                    <p class="text-gray-600 text-sm">
                        Already have an account?
                        <button type="button" onclick="switchModal('registerModal', 'loginModal')"
                            class="text-green-600 hover:text-green-700 font-semibold">
                            Sign in here
                        </button>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Forgot Password Modal - Step 1: Request OTP -->
    <div id="forgotModal" class="modal">
        <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
                <button onclick="closeModal('forgotModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-4 text-center">
                <p class="text-gray-600">Enter your email address and we'll send you a 6-digit OTP code.</p>
            </div>

            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form id="requestOtpForm" onsubmit="submitRequestOtp(event); return false;">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                    <input type="email" name="email" id="forgotEmail" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p id="forgotEmailError" class="text-red-500 text-xs mt-1" style="display:none;"></p>
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition mb-4">
                    Send OTP
                </button>

                <div class="text-center">
                    <p class="text-gray-600 text-sm">
                        Remember your password?
                        <button type="button" onclick="switchModal('forgotModal', 'loginModal')"
                            class="text-green-600 hover:text-green-700 font-semibold">
                            Back to Sign In
                        </button>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Verify OTP Modal - Step 2: Enter OTP -->
    <div id="verifyOtpModal" class="modal">
        <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Verify OTP</h2>
                <button onclick="closeModal('verifyOtpModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-4 text-center">
                <p class="text-gray-600">Enter the 6-digit code sent to your email.</p>
            </div>

            <form id="verifyOtpForm" onsubmit="submitVerifyOtp(event); return false;">
                @csrf
                <input type="hidden" name="email" id="otpEmail">

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">OTP Code</label>
                    <input type="text" name="otp" id="otpCode" maxlength="6" placeholder="000000" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p id="otpError" class="text-red-500 text-xs mt-1" style="display:none;"></p>
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition mb-4">
                    Verify OTP
                </button>

                <div class="text-center">
                    <p class="text-gray-600 text-sm">
                        Didn't receive the code?
                        <button type="button" id="resendOtpBtn" onclick="resendOtpModal()"
                            class="text-green-600 hover:text-green-700 font-semibold">
                            Resend OTP
                        </button>
                        <span id="resendTimer" class="text-gray-500 text-xs ml-2"></span>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Reset Password Modal - Step 3: Set New Password -->
    <div id="resetPasswordModal" class="modal">
        <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Set New Password</h2>
                <button onclick="closeModal('resetPasswordModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-4 text-center">
                <p class="text-gray-600">Enter your new password to complete the reset.</p>
            </div>

            <form id="resetPasswordForm" onsubmit="submitResetPassword(event); return false;">
                @csrf
                <input type="hidden" name="email" id="resetEmail">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">New Password</label>
                    <input type="password" name="password" id="resetNewPassword" required oninput="checkResetPasswordStrength(); checkResetPasswordMatch();"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    
                    <!-- Password Strength Indicator -->
                    <div id="resetPasswordStrength" style="display:none; margin-top: 8px;">
                        <div class="flex gap-1 mb-2">
                            <div id="resetStrengthBar1" class="h-1 flex-1 bg-gray-300 rounded"></div>
                            <div id="resetStrengthBar2" class="h-1 flex-1 bg-gray-300 rounded"></div>
                            <div id="resetStrengthBar3" class="h-1 flex-1 bg-gray-300 rounded"></div>
                        </div>
                        <p id="resetStrengthText" class="text-xs font-semibold"></p>
                    </div>
                    
                    <p id="resetPasswordError" class="text-red-500 text-xs mt-1" style="display:none;"></p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="resetConfirmPassword" required oninput="checkResetPasswordMatch()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p id="resetConfirmPasswordError" class="text-red-500 text-xs mt-1" style="display:none;"></p>
                    <p id="resetConfirmPasswordSuccess" class="text-green-600 text-xs mt-1" style="display:none;"></p>
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition mb-4"
                    style="pointer-events: auto; cursor: pointer;">
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <script>
        // Check if external image loads, if not use fallback
        function checkImageLoad() {
            const heroSection = document.querySelector('.hero-gradient');
            if (!heroSection) return;
            const img = new Image();
            const src = 'https://i.pinimg.com/1200x/40/73/d4/4073d4378fad895860ee2285fedf3a74.jpg';
            img.onload = function() {
                // Image loaded successfully, keep current background
            };
            img.onerror = function() {
                // Image failed to load, use gradient-only fallback
                heroSection.style.background =
                    'linear-gradient(135deg, rgba(34, 197, 94, 0.85) 0%, rgba(22, 101, 52, 0.85) 100%)';
            };
            img.src = src;
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function switchModal(closeId, openId) {
            closeModal(closeId);
            setTimeout(() => openModal(openId), 300);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // AJAX function to submit request OTP form
        function submitRequestOtp(event) {
            event.preventDefault();
            console.log('submitRequestOtp called');
            
            const form = document.getElementById('requestOtpForm');
            const email = document.getElementById('forgotEmail').value;
            const errorEl = document.getElementById('forgotEmailError');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            console.log('Email:', email);
            
            // Clear previous errors
            errorEl.style.display = 'none';
            
            // Disable button during request
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            
            // Get CSRF token from the specific form
            const csrfToken = form.querySelector('input[name="_token"]').value;
            
            fetch('{{ route("password.request-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                if (data.success) {
                    document.getElementById('otpEmail').value = email;
                    closeModal('forgotModal');
                    setTimeout(() => openModal('verifyOtpModal'), 300);
                } else {
                    errorEl.textContent = data.message || 'Error sending OTP';
                    errorEl.style.display = 'block';
                }
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send OTP';
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            })
            .catch(error => {
                console.error('Fetch error:', error);
                errorEl.textContent = 'Error: ' + error.message;
                errorEl.style.display = 'block';
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send OTP';
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        }

        // AJAX function to submit verify OTP form
        function submitVerifyOtp(event) {
            event.preventDefault();
            console.log('submitVerifyOtp called');
            
            const form = document.getElementById('verifyOtpForm');
            const email = document.getElementById('otpEmail').value;
            const otp = document.getElementById('otpCode').value;
            const errorEl = document.getElementById('otpError');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            console.log('Email:', email);
            console.log('OTP:', otp);
            
            // Clear previous errors
            errorEl.style.display = 'none';
            
            // Validate OTP format
            if (!/^\d{6}$/.test(otp)) {
                errorEl.textContent = 'Invalid OTP format. Please enter exactly 6 digits.';
                errorEl.style.display = 'block';
                return;
            }
            
            // Disable button during request
            submitBtn.disabled = true;
            submitBtn.textContent = 'Verifying...';
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            
            // Get CSRF token from the specific form
            const csrfToken = form.querySelector('input[name="_token"]').value;
            console.log('CSRF Token found:', !!csrfToken);
            
            fetch('{{ route("password.verify-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email, otp: otp })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Store email in reset password form
                    document.getElementById('resetEmail').value = email;
                    closeModal('verifyOtpModal');
                    setTimeout(() => openModal('resetPasswordModal'), 300);
                } else {
                    errorEl.textContent = data.message || 'Invalid OTP';
                    errorEl.style.display = 'block';
                }
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Verify OTP';
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            })
            .catch(error => {
                console.error('Fetch error:', error);
                errorEl.textContent = 'Error: ' + error.message;
                errorEl.style.display = 'block';
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Verify OTP';
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        }

        // Resend OTP function
        function resendOtpModal() {
            const email = document.getElementById('otpEmail').value;
            const resendBtn = document.getElementById('resendOtpBtn');
            const errorEl = document.getElementById('otpError');
            
            if (!email) {
                errorEl.textContent = 'Email not found. Please request OTP again.';
                errorEl.style.display = 'block';
                return;
            }

            resendBtn.disabled = true;
            resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
            errorEl.style.display = 'none';

            fetch('{{ route("password.request-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear OTP input
                    document.getElementById('otpCode').value = '';
                    // Show success message
                    errorEl.textContent = 'New OTP sent to your email!';
                    errorEl.style.color = '#10b981';
                    errorEl.style.display = 'block';
                    // Start cooldown timer
                    startResendCooldown();
                } else {
                    errorEl.textContent = data.message || 'Error resending OTP';
                    errorEl.style.color = '#ef4444';
                    errorEl.style.display = 'block';
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            })
            .catch(error => {
                errorEl.textContent = 'Error: ' + error.message;
                errorEl.style.color = '#ef4444';
                errorEl.style.display = 'block';
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        }

        // Cooldown timer for resend button
        function startResendCooldown() {
            const resendBtn = document.getElementById('resendOtpBtn');
            const timerDisplay = document.getElementById('resendTimer');
            let cooldownSeconds = 30;

            const interval = setInterval(() => {
                cooldownSeconds--;
                timerDisplay.textContent = `(${cooldownSeconds}s)`;

                if (cooldownSeconds <= 0) {
                    clearInterval(interval);
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    timerDisplay.textContent = '';
                }
            }, 1000);
        }

        // OTP Input Validation
        document.addEventListener('DOMContentLoaded', function() {
            const otpInput = document.getElementById('otpCode');
            if (otpInput) {
                otpInput.addEventListener('input', function(e) {
                    // Remove any non-digit characters
                    this.value = this.value.replace(/[^0-9]/g, '');
                    // Clear error on input
                    document.getElementById('otpError').style.display = 'none';
                });

                otpInput.addEventListener('keypress', function(e) {
                    // Only allow digits
                    if (!/[0-9]/.test(e.key)) {
                        e.preventDefault();
                        const errorEl = document.getElementById('otpError');
                        errorEl.textContent = 'Invalid OTP format. Only numbers are allowed.';
                        errorEl.style.color = '#ef4444';
                        errorEl.style.display = 'block';
                    }
                });
            }
            
            // Debug: Log when forms are found
            const verifyOtpForm = document.getElementById('verifyOtpForm');
            const requestOtpForm = document.getElementById('requestOtpForm');
            console.log('verifyOtpForm found:', !!verifyOtpForm);
            console.log('requestOtpForm found:', !!requestOtpForm);
            
            if (verifyOtpForm) {
                console.log('verifyOtpForm CSRF token:', !!verifyOtpForm.querySelector('input[name="_token"]'));
                console.log('otpEmail field:', !!document.getElementById('otpEmail'));
                console.log('otpCode field:', !!document.getElementById('otpCode'));
            }
        });

        // Validation functions for registration form
        function validateFullName(name) {
            if (!name) return 'This input field is required';
            if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(name)) return 'Special characters not allowed';
            if (/\d/.test(name)) return 'Numbers not allowed';
            if (/  /.test(name)) return 'Double spaces not allowed';
            if (!/^[A-Z]/.test(name)) return 'Must start with capital letter';
            return '';
        }

        function validateEmail(email) {
            if (!email) return 'This input field is required';
            const validDomains = ['@gmail.com', '@csucc.edu.ph'];
            const isValid = validDomains.some(domain => email.endsWith(domain));
            if (!isValid) return 'Invalid email. Accepted: @gmail.com or @csucc.edu.ph';
            return '';
        }

        function checkPasswordStrength() {
            const password = document.getElementById('registerPassword').value;
            const strengthDiv = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            const bar1 = document.getElementById('strengthBar1');
            const bar2 = document.getElementById('strengthBar2');
            const bar3 = document.getElementById('strengthBar3');

            if (!password) {
                strengthDiv.style.display = 'none';
                return;
            }

            strengthDiv.style.display = 'block';
            bar1.style.backgroundColor = '#d1d5db';
            bar2.style.backgroundColor = '#d1d5db';
            bar3.style.backgroundColor = '#d1d5db';

            const hasLetter = /[a-zA-Z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
            const isLongEnough = password.length >= 8;

            let strength = 0;
            if (hasLetter && isLongEnough) strength++;
            if (hasNumber && isLongEnough) strength++;
            if (hasSpecial && isLongEnough) strength++;

            if (strength === 1) {
                bar1.style.backgroundColor = '#ef4444';
                strengthText.textContent = 'Weak - Letter only';
                strengthText.style.color = '#ef4444';
            } else if (strength === 2) {
                bar1.style.backgroundColor = '#f59e0b';
                bar2.style.backgroundColor = '#f59e0b';
                strengthText.textContent = 'Medium - Letter & Number';
                strengthText.style.color = '#f59e0b';
            } else if (strength === 3) {
                bar1.style.backgroundColor = '#10b981';
                bar2.style.backgroundColor = '#10b981';
                bar3.style.backgroundColor = '#10b981';
                strengthText.textContent = 'Strong - Letter, Number & Special Character';
                strengthText.style.color = '#10b981';
            } else {
                strengthText.textContent = 'Password must be 8+ characters';
                strengthText.style.color = '#ef4444';
            }
        }

        function validateRegisterForm(event) {
            event.preventDefault();

            const name = document.getElementById('fullName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            const nameError = document.getElementById('nameError');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const confirmError = document.getElementById('confirmPasswordError');

            // Clear previous errors
            nameError.style.display = 'none';
            emailError.style.display = 'none';
            passwordError.style.display = 'none';
            confirmError.style.display = 'none';

            let hasError = false;

            // Validate name
            const nameMsg = validateFullName(name);
            if (nameMsg) {
                nameError.textContent = nameMsg;
                nameError.style.display = 'block';
                hasError = true;
            }

            // Validate email
            const emailMsg = validateEmail(email);
            if (emailMsg) {
                emailError.textContent = emailMsg;
                emailError.style.display = 'block';
                hasError = true;
            }

            // Validate password
            if (!password) {
                passwordError.textContent = 'This input field is required';
                passwordError.style.display = 'block';
                hasError = true;
            } else if (password.length < 8) {
                passwordError.textContent = 'Password must be 8 characters or above';
                passwordError.style.display = 'block';
                hasError = true;
            } else {
                const hasLetter = /[a-zA-Z]/.test(password);
                const hasNumber = /\d/.test(password);
                const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

                if (!hasLetter || !hasNumber || !hasSpecial) {
                    passwordError.textContent = 'Password must contain letter, number, and special character';
                    passwordError.style.display = 'block';
                    hasError = true;
                }
            }

            // Validate confirm password
            if (!confirmPassword) {
                confirmError.textContent = 'This input field is required';
                confirmError.style.display = 'block';
                hasError = true;
            } else if (password !== confirmPassword) {
                confirmError.textContent = 'Passwords do not match';
                confirmError.style.display = 'block';
                hasError = true;
            }

            if (!hasError) {
                document.getElementById('registerForm').submit();
            }
        }

        // Password strength checker for reset password
        function checkResetPasswordStrength() {
            const password = document.getElementById('resetNewPassword').value;
            const strengthDiv = document.getElementById('resetPasswordStrength');
            const strengthText = document.getElementById('resetStrengthText');
            const bar1 = document.getElementById('resetStrengthBar1');
            const bar2 = document.getElementById('resetStrengthBar2');
            const bar3 = document.getElementById('resetStrengthBar3');

            if (!password) {
                strengthDiv.style.display = 'none';
                return;
            }

            strengthDiv.style.display = 'block';
            bar1.style.backgroundColor = '#d1d5db';
            bar2.style.backgroundColor = '#d1d5db';
            bar3.style.backgroundColor = '#d1d5db';

            const hasLetter = /[a-zA-Z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
            const isLongEnough = password.length >= 8;

            let strength = 0;
            if (hasLetter && isLongEnough) strength++;
            if (hasNumber && isLongEnough) strength++;
            if (hasSpecial && isLongEnough) strength++;

            if (strength === 1) {
                bar1.style.backgroundColor = '#ef4444';
                strengthText.textContent = 'Weak - Letter only';
                strengthText.style.color = '#ef4444';
            } else if (strength === 2) {
                bar1.style.backgroundColor = '#f59e0b';
                bar2.style.backgroundColor = '#f59e0b';
                strengthText.textContent = 'Medium - Letter & Number';
                strengthText.style.color = '#f59e0b';
            } else if (strength === 3) {
                bar1.style.backgroundColor = '#10b981';
                bar2.style.backgroundColor = '#10b981';
                bar3.style.backgroundColor = '#10b981';
                strengthText.textContent = 'Strong - Letter, Number & Special Character';
                strengthText.style.color = '#10b981';
            } else {
                strengthText.textContent = 'Password must be 8+ characters';
                strengthText.style.color = '#ef4444';
            }
        }

        // Real-time password matching checker for reset password
        function checkResetPasswordMatch() {
            try {
                const passwordInput = document.getElementById('resetNewPassword');
                const confirmPasswordInput = document.getElementById('resetConfirmPassword');
                const confirmError = document.getElementById('resetConfirmPasswordError');
                const confirmSuccess = document.getElementById('resetConfirmPasswordSuccess');

                if (!passwordInput || !confirmPasswordInput || !confirmError || !confirmSuccess) {
                    return;
                }

                const password = passwordInput.value || '';
                const confirmPassword = confirmPasswordInput.value || '';

                // Clear previous messages
                confirmError.style.display = 'none';
                confirmSuccess.style.display = 'none';

                // Only show message if confirm password has value
                if (confirmPassword.length === 0) {
                    return;
                }

                // Compare passwords
                if (password !== confirmPassword) {
                    confirmError.textContent = 'Passwords do not match';
                    confirmError.style.display = 'block';
                } else if (password.length > 0) {
                    confirmSuccess.textContent = 'Passwords match';
                    confirmSuccess.style.display = 'block';
                }
            } catch (error) {
                console.error('Error in checkResetPasswordMatch:', error);
            }
        }

        // AJAX function to submit reset password form
        function submitResetPassword(event) {
            event.preventDefault();
            console.log('submitResetPassword called');
            
            const submitButton = event.target.querySelector('button[type="submit"]');
            const email = document.getElementById('resetEmail').value;
            const password = document.getElementById('resetNewPassword').value;
            const passwordConfirmation = document.getElementById('resetConfirmPassword').value;
            const passwordError = document.getElementById('resetPasswordError');
            const confirmError = document.getElementById('resetConfirmPasswordError');
            
            console.log('Email:', email);
            console.log('Password length:', password.length);
            console.log('Confirm password length:', passwordConfirmation.length);
            
            // Clear previous errors
            passwordError.style.display = 'none';
            confirmError.style.display = 'none';
            
            // Validate email exists
            if (!email) {
                console.error('No email found in resetEmail field');
                passwordError.textContent = 'Session expired. Please start over.';
                passwordError.style.display = 'block';
                return;
            }
            
            if (!password || !passwordConfirmation) {
                console.error('Password fields empty');
                passwordError.textContent = 'Please fill in all fields';
                passwordError.style.display = 'block';
                return;
            }
            
            if (password !== passwordConfirmation) {
                console.error('Passwords do not match');
                confirmError.textContent = 'Passwords do not match';
                confirmError.style.display = 'block';
                return;
            }
            
            if (password.length < 8) {
                console.error('Password too short');
                passwordError.textContent = 'Password must be at least 8 characters';
                passwordError.style.display = 'block';
                return;
            }

            // Validate password strength
            const hasLetter = /[a-zA-Z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

            if (!hasLetter || !hasNumber || !hasSpecial) {
                console.error('Password does not meet strength requirements');
                passwordError.textContent = 'Password must contain letter, number, and special character';
                passwordError.style.display = 'block';
                return;
            }
            
            console.log('All validations passed, sending request...');
            
            // Disable button and show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Resetting...';
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            
            fetch('{{ route("password.reset-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ 
                    email: email,
                    password: password, 
                    password_confirmation: passwordConfirmation 
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    console.log('Password reset successful!');
                    closeModal('resetPasswordModal');
                    alert('Password reset successfully! Please sign in with your new password.');
                    setTimeout(() => {
                        window.location.href = '{{ route("welcome") }}';
                    }, 500);
                } else {
                    console.error('Password reset failed:', data.message);
                    // Re-enable button
                    submitButton.disabled = false;
                    submitButton.textContent = 'Reset Password';
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    
                    passwordError.textContent = data.message || 'Error resetting password';
                    passwordError.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = 'Reset Password';
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                
                passwordError.textContent = 'Error: ' + error.message;
                passwordError.style.display = 'block';
            });
        }

        // Handle form errors - show appropriate modal if there are validation errors
        document.addEventListener('DOMContentLoaded', function() {
            checkImageLoad();

            // Handle OTP verified - switch to reset password modal (check this FIRST)
            @if (session('otp_verified'))
                console.log('OTP verified, switching to reset password modal');
                setTimeout(() => {
                    closeModal('verifyOtpModal');
                    setTimeout(() => {
                        openModal('resetPasswordModal');
                    }, 100);
                }, 300);
            @elseif ($errors->any())
                @php
                    $hasLoginError = $errors->has('email') && !old('name');
                    $hasRegisterError = $errors->has('name') || old('name');
                @endphp
                @if ($hasRegisterError)
                    {{-- Register error (has name error or has name field) --}}
                    console.log('Register error detected, opening register modal');
                    openModal('registerModal');
                @elseif ($hasLoginError)
                    {{-- Login error (has email error but no name field = login form) --}}
                    console.log('Login error detected, opening login modal');
                    console.log('Error message: {{ $errors->first("email") }}');
                    openModal('loginModal');
                @elseif (request()->routeIs('password.request-otp'))
                    openModal('forgotModal');
                @elseif (request()->routeIs('password.verify-otp'))
                    openModal('verifyOtpModal');
                @else
                    {{-- Default to login modal for any other errors --}}
                    console.log('Other error detected, opening login modal');
                    openModal('loginModal');
                @endif
            @endif

            // Handle OTP sent - switch to verification modal
            @if (session('status'))
                setTimeout(() => {
                    closeModal('forgotModal');
                    openModal('verifyOtpModal');
                }, 300);
            @endif

            // Handle password reset success
            @if (session('password_reset_success'))
                setTimeout(() => {
                    closeModal('resetPasswordModal');
                    window.location.href = '{{ route("welcome") }}';
                }, 300);
            @endif

            // Realtime-like polling for stats
            function formatNumber(n) {
                if (n === null || n === undefined) return '0';
                return n.toLocaleString(undefined);
            }

            function updateStatsUI(data) {
                const elFarmers = document.getElementById('stat-total-farmers');
                const elArea = document.getElementById('stat-total-farm-area');
                const elCrops = document.getElementById('stat-active-crops');
                const elLogs = document.getElementById('stat-total-logs');
                if (elFarmers) elFarmers.textContent = `${formatNumber(data.total_farmers)}+`;
                if (elArea) elArea.textContent =
                    `${Number(data.total_farm_area).toLocaleString(undefined, { minimumFractionDigits: 1, maximumFractionDigits: 1 })}+`;
                if (elCrops) elCrops.textContent = `${formatNumber(data.active_crops)}+`;
                if (elLogs) elLogs.textContent = `${formatNumber(data.total_logs)}+`;
            }
            async function fetchStats() {
                try {
                    const res = await fetch('{{ route('stats.summary') }}', {
                        headers: {
                            'Accept': 'application/json'
                        },
                        cache: 'no-store'
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    updateStatsUI(data);
                } catch (e) {
                    /* ignore network errors */ }
            }
            // Initial fetch and interval
            fetchStats();
            setInterval(fetchStats, 15000);
        });

        // Counting animation for stats
        function animateCounter(element, target, duration = 2000, decimals = 0) {
            let start = 0;
            const increment = target / (duration / 16);
            const timer = setInterval(() => {
                start += increment;
                if (start >= target) {
                    element.textContent = decimals > 0 ? target.toFixed(decimals) + '+' : Math.ceil(target) + '+';
                    clearInterval(timer);
                } else {
                    element.textContent = decimals > 0 ? start.toFixed(decimals) + '+' : Math.ceil(start) + '+';
                }
            }, 16);
        }

        // Intersection Observer for stats animation
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statElements = [
                        { id: 'stat-total-farmers', value: {{ $stats['total_farmers'] ?? 0 }}, decimals: 0 },
                        { id: 'stat-total-farm-area', value: {{ $stats['total_farm_area'] ?? 0 }}, decimals: 1 },
                        { id: 'stat-active-crops', value: {{ $stats['active_crops'] ?? 0 }}, decimals: 0 },
                        { id: 'stat-total-logs', value: {{ $stats['total_logs'] ?? 0 }}, decimals: 0 }
                    ];

                    statElements.forEach(stat => {
                        const element = document.getElementById(stat.id);
                        if (element) {
                            animateCounter(element, stat.value, 2000, stat.decimals);
                        }
                    });

                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        // Observe the stats section
        const statsSection = document.querySelector('.bg-green-500');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }
    </script>

    <!-- Security: Check if user is authenticated and redirect -->
    <script>
        (function() {
            var authCheckUrl = '{{ route("auth.check") }}';
            
            function checkAuthAndRedirect() {
                fetch(authCheckUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-cache'
                    },
                    credentials: 'same-origin',
                    cache: 'no-store'
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.authenticated && data.redirect) {
                        // Use replace to prevent back navigation
                        window.location.replace(data.redirect);
                    }
                })
                .catch(function(error) { 
                    console.log('Auth check:', error); 
                });
            }

            // Check immediately on page load
            checkAuthAndRedirect();

            // Check on pageshow (handles bfcache)
            window.addEventListener('pageshow', function(event) {
                checkAuthAndRedirect();
            });

            // Check on visibility change (tab becomes visible)
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    checkAuthAndRedirect();
                }
            });

            // Check on focus
            window.addEventListener('focus', function() {
                checkAuthAndRedirect();
            });

            // Periodic check every 2 seconds while on this page
            setInterval(checkAuthAndRedirect, 2000);
        })();
    </script>
</body>

</html>
