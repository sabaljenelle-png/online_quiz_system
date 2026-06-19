@extends('layouts.app')

@section('content')

<div class="container mx-auto p-5">
    <h2>Profile Settings</h2>

    @include('profile.partials.update-profile-information-form')

    <br>

    @include('profile.partials.update-password-form')

    <br>

    @include('profile.partials.delete-user-form')
</div>

@endsection