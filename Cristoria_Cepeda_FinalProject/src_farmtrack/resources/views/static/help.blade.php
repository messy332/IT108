@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Help Center</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Everything you need to know about using FarmTrack</p>
        </div>

        <!-- Quick Start Guide -->
        <div class="bg-gray-50 rounded-lg p-8 mb-8 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Quick Start Guide</h2>
            <p class="text-gray-700">Welcome to FarmTrack! Here's how to get started with managing your agricultural operations.</p>
        </div>

        <!-- Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Dashboard Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Dashboard</h3>
                <p class="text-gray-600 mb-4">Your central hub for monitoring all farm activities and statistics.</p>
                <ul class="space-y-2 text-gray-700 text-sm">
                    <li>• View real-time statistics</li>
                    <li>• Track recent activities</li>
                    <li>• Monitor upcoming harvests</li>
                </ul>
            </div>

            <!-- Farmers Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Farmers</h3>
                <p class="text-gray-600 mb-4">Manage your farmer network and their information.</p>
                <ul class="space-y-2 text-gray-700 text-sm">
                    <li>• Add new farmers with detailed profiles</li>
                    <li>• View and edit farmer information</li>
                    <li>• Track farmer activities and crops</li>
                </ul>
            </div>

            <!-- Crops Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Crops</h3>
                <p class="text-gray-600 mb-4">Track and manage all crop information and schedules.</p>
                <ul class="space-y-2 text-gray-700 text-sm">
                    <li>• Add crops with planting dates</li>
                    <li>• Set expected harvest dates</li>
                    <li>• Monitor crop growth stages</li>
                </ul>
            </div>

            <!-- Progress Logs Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Progress Logs</h3>
                <p class="text-gray-600 mb-4">Document and track all field activities and observations.</p>
                <ul class="space-y-2 text-gray-700 text-sm">
                    <li>• Record daily field activities</li>
                    <li>• Track weather conditions</li>
                    <li>• Document growth stages</li>
                </ul>
            </div>

            <!-- Analytics Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Analytics</h3>
                <p class="text-gray-600 mb-4">Generate insights and reports from your farm data.</p>
                <ul class="space-y-2 text-gray-700 text-sm">
                    <li>• View performance metrics</li>
                    <li>• Export data for analysis</li>
                    <li>• Track trends over time</li>
                </ul>
            </div>

            <!-- Settings Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Profile & Settings</h3>
                <p class="text-gray-600 mb-4">Manage your account and system preferences.</p>
                <ul class="space-y-2 text-gray-700 text-sm">
                    <li>• Update profile information</li>
                    <li>• Change password</li>
                    <li>• Configure notifications</li>
                </ul>
            </div>
        </div>

        <!-- Need More Help Section -->
        <div class="bg-gray-100 rounded-lg p-8 text-center border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Need More Help?</h2>
            <p class="text-gray-600 mb-6">Our support team is here to assist you with any questions or issues.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="mailto:support@farmtrack.com" class="inline-block bg-gray-800 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-700 transition">
                    Email Support
                </a>
                <a href="tel:+639123456789" class="inline-block bg-gray-800 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-700 transition">
                    Call Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
