@props(['user' => null])

@php $resolvedUser = $user ?? auth()->user(); @endphp

@if($resolvedUser?->hasRole('admin'))
    <x-badge color="red">admin</x-badge>
@else
    <x-badge color="blue">user</x-badge>
@endif
