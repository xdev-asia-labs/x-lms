@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verify your email address
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                We've sent a verification link to your email address. Please check your inbox and click the link to verify your account.
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="rounded-md bg-blue-50 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">
                            {{ session('info') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-center mb-4">
                <svg class="h-16 w-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            
            <p class="text-center text-gray-700 mb-6">
                Didn't receive the email?
            </p>

            <form action="{{ route('member.verification.resend') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Resend Verification Email
                </button>
            </form>
        </div>

        <div class="text-center space-y-2">
            <a href="{{ route('member.dashboard') }}" class="block text-sm font-medium text-indigo-600 hover:text-indigo-500">
                Go to Dashboard
            </a>
            <form action="{{ route('member.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-500">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
