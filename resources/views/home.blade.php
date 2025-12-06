@extends('layouts.app')

@section('content')
<!-- MENGHAPUS KONTEN DEFAULT: Jika pengguna sampai di sini, mereka harus diarahkan -->

<script>
    // Redirect langsung ke rute pemilah peran jika pengguna sudah login
    window.location.href = "{{ route('redirect') }}";
</script>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-custom">
                <div class="card-body text-center">
                    <p class="text-muted">Mengalihkan ke Dashboard yang sesuai...</p>
                    <i class="fas fa-spinner fa-spin fa-2x text-primary-green"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection