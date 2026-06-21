@extends('frontend.layouts.master')

@push("css")

@endpush

@section('content')

    @include('frontend.sections.banner')

    @include('frontend.sections.feature')

    @include('frontend.sections.overview')

    @include('frontend.sections.testimonial')

    <!-- CTA Section -->
    <section class="enzo-cta">
        <div class="container">
            <div class="enzo-cta-content" data-aos="fade-up">
                <h2 class="enzo-cta-title">{{ __('Ready to Get Started?') }}</h2>
                <p class="enzo-cta-sub">{{ __('Join millions of customers who trust EnzoBank for their financial needs.') }}</p>
                <a href="{{ setRoute('user.register') }}" class="enzo-btn-white enzo-btn-lg">
                    {{ __('Open an Account Now') }} →
                </a>
            </div>
        </div>
    </section>

@endsection

@push("script")

@endpush
