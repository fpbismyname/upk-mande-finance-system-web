<x-layouts.app title="Daftar menjadi anggota {{ $app['name'] }}">
    <div class="container mx-auto min-h-screen flex items-center justify-center">
        <x-ui.card class="bg-base-200 text-base-content mx-auto p-6 max-w-lg w-full">
            <div class="card-body gap-6 text-center">
                <x-ui.image :src="asset('nav_icon.ico')" class="w-48 mx-auto" />
                <h3>Daftar menjadi anggota {{ $app['name'] }}</h3>
                <div class="flex flex-col gap-4">
                    <form action="{{ route('client.submit-register') }}" method="POST" class="flex flex-col gap-6">
                        @csrf
                        @method('post')
                        <div class="flex flex-col gap-4 text-start">
                            {{-- Name --}}
                            <div>
                                <label class="input validator w-full">
                                    <x-lucide-user class="w-4" />
                                    <input type="text" name="name" placeholder="Nama lengkap" max="250"
                                        value="{{ old('name') }}" required />
                                </label>
                                <p class="validator-hint hidden">
                                    Nama lengkap wajib diisi.
                                </p>
                            </div>
                            {{-- email --}}
                            <div>
                                <label class="input validator w-full">
                                    <x-lucide-mail class="w-4" />
                                    <input type="email" name="email" placeholder="Alamat Email" max="250"
                                        value="{{ old('email') }}" required />
                                </label>
                                <p class="validator-hint hidden">Masukan format email yang tepat</p>
                            </div>
                            {{-- Password --}}
                            <div>
                                <x-ui.input-password type="password" name="password" placeholder="Password"
                                    max="250" required pattern="\w{6,250}" minlength="6"
                                    prefix_icon="key-round" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <button type="submit" class="btn btn-secondary">Daftar</button>
                            <a href="{{ route('client.homepage.index') }}" type="submit"
                                class="btn btn-neutral">Kembali</a>
                        </div>
                    </form>
                    <div class="flex flex-col text-center">
                        <p>Sudah punya akun ? <a class="link-primary link-hover"
                                href="{{ route('client.login') }}">Login sekarang</a></p>
                    </div>
                </div>
            </div>
        </x-ui.card>
    </div>
</x-layouts.app>
