@extends('dashboard')
@section('content')

<section class="section pt-5 pb-5 osahan-not-found-page">
    <div class="min-h-screen flex items-center justify-center mb-10">
        <div class="bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-2xl shadow-2xl text-center max-w-md w-full">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Terima Kasih!</h2>
            <p class="text-gray-600 mb-6">Pesananmu telah berhasil diproses.<br>Kami akan segera mengirimkannya ke alamatmu!</p>
            <a href="{{ route('atk_dashboard') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-full transition duration-300">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</section>

@endsection
