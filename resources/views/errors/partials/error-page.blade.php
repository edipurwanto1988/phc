<section class="error-page">
    <div class="error-page__container">
        <div>
            <div class="error-page__badge">
                <span class="error-page__icon">{{ $iconSymbol ?? '!' }}</span>
                Error {{ $code }}
            </div>
            <h1 class="error-page__title">{{ $title }}</h1>
            <p class="error-page__message">{{ $message }}</p>
            <div class="error-page__actions">
                <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="error-page__button error-page__button--primary">
                    Kembali ke Beranda
                </a>
                <button type="button" onclick="history.back()" class="error-page__button error-page__button--secondary">
                    Kembali
                </button>
            </div>
        </div>

        <div class="error-page__panel">
            <div class="error-page__watermark">{{ $code }}</div>
            <div class="error-page__symbol">{{ $iconSymbol ?? '!' }}</div>
            <div>
                <div class="error-page__line"></div>
                <div class="error-page__line"></div>
                <div class="error-page__line"></div>
            </div>
            <div class="error-page__note">
                Jika masalah terus muncul, silakan hubungi pengelola website Fasilkom Unilak.
            </div>
        </div>
    </div>
</section>
