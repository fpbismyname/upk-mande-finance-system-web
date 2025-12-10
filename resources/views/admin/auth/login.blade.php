<x-layouts.app title="Portal login admin">
    <section id="login-card" class="flex container mx-auto min-h-screen px-4">
        <x-ui.card class="bg-base-200 text-base-content mx-auto p-6 my-auto max-w-lg w-full">
            <div class="card-body gap-6 text-center">
                <x-ui.image :src="route('storage.public.get', ['path' => config('site.company_icon')])" class="w-48 mx-auto" />
                <h3>Portal Admin Login</h3>
                <div class="flex flex-col">
                    <form action="{{ route('admin.submit-login') }}" method="POST" class="flex flex-col gap-6">
                        @csrf
                        @method('POST')
                        <div class="flex flex-col gap-4 text-start">
                            <div>
                                <label class="input validator w-full">
                                    <x-lucide-mail class="w-4" />
                                    <input type="email" name="email" placeholder="Alamat Email" max="250"
                                        value="{{ old('email') }}" required />
                                </label>
                                <p class="validator-hint hidden">Masukan format email yang tepat</p>
                            </div>
                            <div>
                                <x-ui.input-password type="password" name="password" placeholder="Password"
                                    max="250" required pattern="\w{6,250}" minlength="6"
                                    prefix_icon="key-round" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <button type="submit" class="btn btn-secondary">Login</button>
                            <a href="{{ route('client.homepage.index') }}" type="submit"
                                class="btn btn-neutral">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </x-ui.card>
    </section>
</x-layouts.app>
