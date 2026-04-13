@extends('layouts.public')

@section('title', 'Mi Perfil - Gracia Creativa')

@section('content')

<!-- ═══════════════════════════════════════════════════════════════
     PROFILE HEADER
     ═══════════════════════════════════════════════════════════════ -->
<section class="section-padding bg-gray-800">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-gracia-primary/10 text-gracia-primary rounded-full text-sm font-semibold mb-4">Mi Cuenta</span>
            <h1 class="section-title">Mi Perfil</h1>
            <p class="section-subtitle">Administra tu información personal y configuración de cuenta</p>
        </div>

        <!-- Profile Information -->
        <div class="card-gracia p-8 mb-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Update Password -->
        <div class="card-gracia p-8 mb-8">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Delete Account -->
        <div class="card-gracia p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</section>

@endsection
