@extends($activeTemplate.'layouts.app')

@push('meta')
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#000000">
    <meta name="mobile-web-app-capable" content="yes">
@endpush

@section('app')
    @parent
    <x-bottom-nav />
@endsection

@push('script')
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset('service-worker.js') }}', { scope: '/' })
                .catch(function(e) { console.error('SW registration failed', e); });
        }
    </script>
@endpush