@extends('layouts.app')
@section('content')

<div class="sv-container" style="padding-top: 120px; padding-bottom: 60px; max-width: 800px; margin: 0 auto;">
    <h1 class="sv-section-title" style="font-size: 2.5rem; margin-bottom: 8px;">Syarat & Ketentuan</h1>
    <p style="color: var(--sv-text-muted); margin-bottom: 40px;">Pembaruan Terakhir: {{ date('d F Y') }}</p>

    <div style="color: var(--sv-text-secondary); line-height: 1.8; font-size: 1rem;">
        <h2 style="color: #fff; font-size: 1.5rem; margin-top: 32px; margin-bottom: 16px;">1. Penerimaan Syarat</h2>
        <p style="margin-bottom: 16px;">
            Dengan mengakses dan menggunakan platform StreamVid, Anda menyetujui untuk terikat oleh Syarat dan Ketentuan ini. Jika Anda tidak menyetujui bagian mana pun dari syarat ini, Anda tidak diperkenankan menggunakan layanan kami.
        </p>

        <h2 style="color: #fff; font-size: 1.5rem; margin-top: 32px; margin-bottom: 16px;">2. Layanan Berlangganan</h2>
        <p style="margin-bottom: 16px;">
            Beberapa konten di StreamVid mungkin memerlukan langganan aktif. Biaya langganan akan ditagih di awal sesuai dengan paket yang Anda pilih. StreamVid berhak mengubah harga paket dengan memberikan pemberitahuan sebelumnya.
        </p>

        <h2 style="color: #fff; font-size: 1.5rem; margin-top: 32px; margin-bottom: 16px;">3. Hak Kekayaan Intelektual</h2>
        <p style="margin-bottom: 16px;">
            Semua konten yang tersedia di StreamVid, termasuk namun tidak terbatas pada film, gambar, logo, dan perangkat lunak, adalah milik StreamVid atau pemberi lisensinya dan dilindungi oleh undang-undang hak cipta. Anda tidak diperkenankan untuk mengunduh, menyalin, atau mendistribusikan ulang konten tanpa izin tertulis.
        </p>

        <h1 class="sv-section-title" style="font-size: 2.5rem; margin-top: 64px; margin-bottom: 8px;">Kebijakan Privasi</h1>
        <p style="color: var(--sv-text-muted); margin-bottom: 40px;">Pembaruan Terakhir: {{ date('d F Y') }}</p>

        <h2 style="color: #fff; font-size: 1.5rem; margin-top: 32px; margin-bottom: 16px;">1. Pengumpulan Data</h2>
        <p style="margin-bottom: 16px;">
            Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami saat Anda mendaftar akun, seperti nama dan alamat email. Kami juga mengumpulkan data analitik mengenai perilaku penayangan dan penggunaan aplikasi Anda untuk meningkatkan pengalaman menonton Anda.
        </p>

        <h2 style="color: #fff; font-size: 1.5rem; margin-top: 32px; margin-bottom: 16px;">2. Penggunaan Informasi</h2>
        <p style="margin-bottom: 16px;">
            Informasi yang dikumpulkan digunakan untuk menyediakan, memelihara, dan meningkatkan layanan kami. Ini termasuk merekomendasikan film, memproses transaksi, dan mengirimkan pembaruan layanan kepada Anda.
        </p>

        <h2 style="color: #fff; font-size: 1.5rem; margin-top: 32px; margin-bottom: 16px;">3. Keamanan Data</h2>
        <p style="margin-bottom: 16px;">
            Kami menerapkan langkah-langkah keamanan yang dirancang untuk melindungi informasi pribadi Anda dari akses yang tidak sah dan penyalahgunaan. Namun, tidak ada sistem transmisi internet yang sepenuhnya aman, dan kami tidak dapat menjamin keamanan absolut dari data Anda.
        </p>
    </div>
</div>

@endsection
