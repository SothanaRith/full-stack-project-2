<x-guest-layout :wide="true">
    <div class="grid min-h-[650px] lg:grid-cols-[0.95fr_1.05fr]">
        <section class="relative hidden overflow-hidden bg-[#2b1d17] p-10 text-white lg:flex lg:flex-col lg:justify-between xl:p-14">
            <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full border border-white/10"></div>
            <div class="absolute -right-10 -top-10 h-72 w-72 rounded-full border border-white/10"></div>
            <div class="absolute -bottom-32 -left-28 h-80 w-80 rounded-full bg-[#b7794b]/20 blur-3xl"></div>

            <a href="/" class="relative z-10 inline-flex w-fit items-center gap-3 rounded-full focus:outline-none focus:ring-2 focus:ring-[#e8b88c] focus:ring-offset-4 focus:ring-offset-[#2b1d17]" aria-label="Cafe Shop home">
                <span class="grid h-11 w-11 place-items-center rounded-full bg-[#f4dfca] text-[#3b271e] shadow-lg shadow-black/20">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 9h11v5.5A4.5 4.5 0 0 1 11.5 19h-2A4.5 4.5 0 0 1 5 14.5V9Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11h1.5a2.5 2.5 0 0 1 0 5H16M8 6c0-1 1-1.25 1-2.25M12 6c0-1 1-1.25 1-2.25" />
                    </svg>
                </span>
                <span class="text-lg font-semibold tracking-wide">Cafe Shop</span>
            </a>

            <div class="relative z-10 max-w-sm">
                <span class="mb-6 block h-1 w-12 rounded-full bg-[#d59664]"></span>
                <p class="text-4xl font-semibold leading-tight tracking-tight xl:text-5xl">
                    Good coffee.<br>Great days.
                </p>
                <p class="mt-5 max-w-xs text-base leading-7 text-stone-300">
                    Sign in to manage your cafe, products, and everything that keeps your day brewing smoothly.
                </p>
            </div>

            <p class="relative z-10 text-xs font-medium uppercase tracking-[0.24em] text-stone-400">
                Crafted for your daily ritual
            </p>
        </section>

        <section class="flex items-center px-6 py-10 sm:px-12 lg:px-16 xl:px-20">
            <div class="mx-auto w-full max-w-md">
                <a href="/" class="mb-10 inline-flex items-center gap-3 text-[#3b271e] lg:hidden" aria-label="Cafe Shop home">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-[#f4dfca]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 9h11v5.5A4.5 4.5 0 0 1 11.5 19h-2A4.5 4.5 0 0 1 5 14.5V9Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11h1.5a2.5 2.5 0 0 1 0 5H16M8 6c0-1 1-1.25 1-2.25M12 6c0-1 1-1.25 1-2.25" />
                        </svg>
                    </span>
                    <span class="font-semibold tracking-wide">Cafe Shop</span>
                </a>

                <div class="mb-8">
                    <p class="mb-2 text-sm font-semibold uppercase tracking-[0.18em] text-[#a7653f]">Welcome back</p>
                    <h1 class="text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">Sign in to your account</h1>
                    <p class="mt-3 text-sm leading-6 text-stone-500">Enter your details to continue to your dashboard.</p>
                </div>

                <x-auth-session-status class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-stone-700">{{ __('Email address') }}</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 10.2 12a3.4 3.4 0 0 0 3.6 0L21 7.5M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z" />
                                </svg>
                            </span>
                            <input id="email" class="block w-full rounded-xl border-stone-200 bg-stone-50 py-3.5 pl-12 pr-4 text-stone-900 placeholder:text-stone-400 focus:border-[#a7653f] focus:bg-white focus:ring-[#a7653f]" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus autocomplete="username">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div x-data="{ showPassword: false }">
                        <div class="mb-2 flex items-center justify-between gap-4">
                            <label for="password" class="block text-sm font-semibold text-stone-700">{{ __('Password') }}</label>
                            @if (Route::has('password.request'))
                                <a class="rounded text-sm font-semibold text-[#9a5a36] transition hover:text-[#6f3e25] focus:outline-none focus:ring-2 focus:ring-[#a7653f] focus:ring-offset-2" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>

                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <rect width="14" height="11" x="5" y="10" rx="2" />
                                    <path stroke-linecap="round" d="M8 10V7a4 4 0 0 1 8 0v3" />
                                </svg>
                            </span>
                            <input id="password" class="block w-full rounded-xl border-stone-200 bg-stone-50 py-3.5 pl-12 pr-12 text-stone-900 placeholder:text-stone-400 focus:border-[#a7653f] focus:bg-white focus:ring-[#a7653f]" :type="showPassword ? 'text' : 'password'" name="password" placeholder="Enter your password" required autocomplete="current-password">
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center rounded-r-xl px-4 text-stone-400 transition hover:text-stone-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#a7653f]" @click="showPassword = ! showPassword" :aria-label="showPassword ? 'Hide password' : 'Show password'">
                                <svg x-show="! showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" />
                                    <circle cx="12" cy="12" r="2.5" />
                                </svg>
                                <svg x-cloak x-show="showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" d="m4 4 16 16" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.6 6.1A9.6 9.6 0 0 1 12 6c6 0 9.5 6 9.5 6a15 15 0 0 1-2.2 2.8M14.5 14.5A3.5 3.5 0 0 1 9.6 9.6M6.5 7.3C4 9.2 2.5 12 2.5 12s3.5 6 9.5 6c1 0 2-.2 2.9-.5" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <label for="remember_me" class="flex w-fit cursor-pointer items-center gap-3 text-sm text-stone-600">
                        <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-stone-300 text-[#8b5132] focus:ring-[#a7653f]" name="remember">
                        <span>{{ __('Keep me signed in') }}</span>
                    </label>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border border-transparent bg-[#3b271e] px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#3b271e]/20 transition hover:-translate-y-0.5 hover:bg-[#4b3125] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#a7653f] focus:ring-offset-2 active:translate-y-0">
                        {{ __('Sign in') }}
                        <svg class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5" />
                        </svg>
                    </button>
                </form>

                @if (Route::has('register'))
                    <p class="mt-8 text-center text-sm text-stone-500">
                        {{ __('New to Cafe Shop?') }}
                        <a href="{{ route('register') }}" class="ml-1 rounded font-semibold text-[#9a5a36] transition hover:text-[#6f3e25] focus:outline-none focus:ring-2 focus:ring-[#a7653f] focus:ring-offset-2">
                            {{ __('Create an account') }}
                        </a>
                    </p>
                @endif
            </div>
        </section>
    </div>
</x-guest-layout>
