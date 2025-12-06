@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card-custom border-0 shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-hand-holding-heart fa-3x text-primary-green mb-3"></i>
                        <h4 class="fw-bold text-dark">{{ __('Selamat Datang') }}</h4>
                        <p class="text-muted small">Masuk untuk melanjutkan ke sistem donasi panti.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label-custom">{{ __('Alamat Email') }}</label>
                            <input id="email" 
                                   type="email" 
                                   class="form-control form-control-custom @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   placeholder="email@contoh.com"
                                   autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label-custom">{{ __('Kata Sandi') }}</label>
                            <input id="password" 
                                   type="password" 
                                   class="form-control form-control-custom @error('password') is-invalid @enderror" 
                                   name="password" 
                                   required 
                                   placeholder="Masukkan kata sandi"
                                   autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">
                                    {{ __('Ingat Saya') }}
                                </label>
                            </div>
                            
                            @if (Route::has('password.request'))
                                <a class="btn btn-link p-0 small" href="{{ route('password.request') }}">
                                    {{ __('Lupa Kata Sandi?') }}
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary-custom fw-bold">
                                <i class="fas fa-sign-in-alt me-2"></i> {{ __('Masuk') }}
                            </button>
                        </div>
                        
                        <!-- Register Link -->
                        <div class="text-center mt-3">
                            <p class="text-muted small mb-0">Belum punya akun? 
                                <a href="{{ route('register') }}" class="text-primary-green fw-bold">Daftar di sini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection