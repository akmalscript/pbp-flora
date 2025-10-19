@extends('layouts.public')

@section('title', 'Profile | Flora')

@section('content')
    @php($user = $user ?? Auth::user())

    <main class="py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-semibold text-gray-900">Profil Saya</h1>
                <p class="mt-2 text-sm text-gray-500">Kelola informasi akun dan keamanan Anda.</p>
            </div>

            <div class="space-y-8">
                <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8">
                    <div class="max-w-2xl mx-auto space-y-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8">
                    <div class="max-w-2xl mx-auto space-y-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8">
                    <div class="max-w-2xl mx-auto space-y-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
