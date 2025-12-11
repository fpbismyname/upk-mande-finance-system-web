<x-layouts.homepage title="Beranda">
    {{-- Hero section --}}
    <section class="hero min-h-screen bg-base-200 scroll-mt-12"
        style="background-image: url({{ route('storage.public.get', ['path' => config('site.company_bg')]) }}); ">
        <div class="hero-overlay bg-black/75"></div>
        <div class="hero-content text-center text-white">
            <div class="max-w-5xl">
                <div class="flex flex-row w-full justify-center py-12">
                    <x-ui.image :src="route('storage.public.get', ['path' => config('site.company_icon')])" class="max-w-64 w-full" />
                </div>
                <h1 class="text-5xl font-bold">Selamat datang di {{ config('site.website.title') }}</h1>
                <p class="py-4 text-lg">
                    {{ config('site.website.slogan') }}
                </p>
                <p class="mb-6 text-sm opacity-75">
                    {{ config('site.website.subtitle') }}
                </p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('client.register') }}" class="btn btn-primary">Gabung Sekarang</a>
                    <a href="{{ route('client.homepage.syarat-dan-ketentuan') }}" class="btn btn-outline">Syarat &
                        ketentuan</a>
                </div>
            </div>
        </div>
    </section>

    {{-- About section --}}
    <section id="about" class="py-12 bg-base-100 scroll-mt-12">
        <div class="container mx-auto px-4">
            <div class="card bg-white shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-3">Tentang UPK MANDE</h2>
                <p class="mb-4">
                    UPK MANDE berfokus pada pemberdayaan ekonomi desa melalui penyediaan modal usaha, pembinaan, dan
                    program peningkatan kapasitas. Kami bekerja sama dengan pelaku usaha mikro untuk meningkatkan
                    produktivitas dan kesejahteraan warga desa.
                </p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Misi:</strong> Mendukung kemandirian ekonomi masyarakat desa.</li>
                    <li><strong>Visi:</strong> Terwujudnya usaha mikro desa yang produktif dan berkelanjutan.</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- Services section --}}
    <section id="services" class="py-12 scroll-mt-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-semibold mb-6">Layanan Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card card-compact bg-white shadow p-4">
                    <div class="card-body">
                        <h3 class="font-semibold">Pinjaman Modal Usaha</h3>
                        <p class="text-sm">Bantuan modal untuk usaha mikro dan kecil dengan proses sederhana dan
                            pendampingan.</p>
                    </div>
                </div>

                <div class="card card-compact bg-white shadow p-4">
                    <div class="card-body">
                        <h3 class="font-semibold">Suku Bunga</h3>
                        <p class="text-sm">Bunga kompetitif: 18% (flat) untuk semua produk pinjaman.</p>
                    </div>
                </div>

                <div class="card card-compact bg-white shadow p-4">
                    <div class="card-body">
                        <h3 class="font-semibold">Limit Pinjaman</h3>
                        <p class="text-sm">Limit hingga Rp50.000.000 sesuai kebutuhan usaha dan kelayakan.</p>
                    </div>
                </div>

                <div class="card card-compact bg-white shadow p-4">
                    <div class="card-body">
                        <h3 class="font-semibold">Pembinaan & Pelatihan</h3>
                        <p class="text-sm">Pendampingan teknis, pelatihan manajemen usaha, dan pencatatan keuangan
                            sederhana.</p>
                    </div>
                </div>

                <div class="card card-compact bg-white shadow p-4">
                    <div class="card-body">
                        <h3 class="font-semibold">Proses & Persyaratan</h3>
                        <p class="text-sm">Proses cepat dengan persyaratan sederhana; verifikasi lapangan jika
                            diperlukan.</p>
                    </div>
                </div>

                <div class="card card-compact bg-white shadow p-4">
                    <div class="card-body">
                        <h3 class="font-semibold">Pendampingan Pasca-Pinjaman</h3>
                        <p class="text-sm">Monitoring berkala dan bantuan pengembangan usaha agar kredit berjalan
                            lancar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Benefit section --}}
    <section id="benefit" class="py-12 bg-base-100 scroll-mt-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-semibold mb-6">Keuntungan Bergabung</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="card bg-white shadow p-4">
                    <strong class="block mb-2">Akses Modal</strong>
                    <p class="text-sm">Memperoleh modal usaha untuk pengembangan dan perluasan usaha.</p>
                </div>
                <div class="card bg-white shadow p-4">
                    <strong class="block mb-2">Bunga Transparan</strong>
                    <p class="text-sm">Suku bunga tetap 18% (flat) sehingga mudah dihitung dan direncanakan.</p>
                </div>
                <div class="card bg-white shadow p-4">
                    <strong class="block mb-2">Limit Fleksibel</strong>
                    <p class="text-sm">Dana hingga Rp10.000.000 sesuai evaluasi kelayakan usaha.</p>
                </div>
                <div class="card bg-white shadow p-4">
                    <strong class="block mb-2">Pendampingan</strong>
                    <p class="text-sm">Pelatihan dan bimbingan manajemen usaha untuk meningkatkan kemampuan peminjam.
                    </p>
                </div>
                <div class="card bg-white shadow p-4">
                    <strong class="block mb-2">Proses Mudah</strong>
                    <p class="text-sm">Persyaratan sederhana dan alur pengajuan yang jelas.</p>
                </div>
                <div class="card bg-white shadow p-4">
                    <strong class="block mb-2">Kontribusi Sosial</strong>
                    <p class="text-sm">Mendukung pemberdayaan ekonomi desa dan kesejahteraan komunitas lokal.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA section --}}
    <section id="cta" class="py-12 bg-primary text-primary-content scroll-mt-12">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-2xl font-semibold mb-3">Siap Mengembangkan Usaha Anda?</h3>
            <p class="mb-4">Ajukan pinjaman hingga Rp10.000.000 dengan bunga 18% (flat) dan dapatkan pendampingan
                dari UPK MANDE.</p>
            <a href="{{ route('client.register') }}" class="btn btn-accent btn-lg">Gabung sekarang</a>
        </div>
    </section>

    {{-- Contact / Social links section --}}
    <section id="contact" class="py-12 scroll-mt-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-semibold mb-4">Ikuti Kami</h2>
            <p class="mb-6">Kunjungi akun resmi UPK MANDE di platform berikut:</p>

            <ul class="flex flex-col md:flex-row gap-3">
                <li>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                        class="btn btn-primary">
                        WhatsApp
                    </a>
                </li>
                <li>
                    <a href="https://instagram.com/upkmande" target="_blank" rel="noopener noreferrer"
                        class="btn btn-outline">
                        Instagram
                    </a>
                </li>
                <li>
                    <a href="https://facebook.com/upkmande" target="_blank" rel="noopener noreferrer"
                        class="btn btn-outline">
                        Facebook
                    </a>
                </li>
                <li>
                    <a href="https://youtube.com/@upkmande" target="_blank" rel="noopener noreferrer"
                        class="btn btn-outline">
                        YouTube
                    </a>
                </li>
            </ul>
        </div>
    </section>
</x-layouts.homepage>
