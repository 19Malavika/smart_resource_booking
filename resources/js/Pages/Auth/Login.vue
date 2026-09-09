<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Sign In - ResourceHQ" />
    
    <!-- Import Inter Font for Auth Pages -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="flex min-h-screen bg-zinc-950 text-zinc-50 font-sans" style="font-family: 'Inter', sans-serif;">
        <!-- Left Side: Form -->
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:w-1/2 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                
                <!-- Branding Header -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="bg-zinc-50 text-zinc-950 w-8 h-8 flex items-center justify-center rounded-md text-sm font-bold">R</div>
                        <span class="text-xl font-bold tracking-tight">ResourceHQ</span>
                    </div>

                    <h2 class="text-3xl font-semibold tracking-tight text-zinc-50">
                        Welcome back
                    </h2>
                    <p class="mt-2 text-sm text-zinc-400">
                        Sign in to manage your resource bookings.
                    </p>
                </div>

                <!-- Login Form -->
                <div>
                    <div
                        v-if="status"
                        class="mb-6 rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm font-medium text-emerald-400"
                    >
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-zinc-300">Email address</label>
                            <div class="mt-2">
                                <input
                                    id="email"
                                    type="email"
                                    class="block w-full rounded-lg border-zinc-800 bg-zinc-900/50 py-2.5 px-3 text-sm text-zinc-50 placeholder-zinc-500 focus:border-emerald-500 focus:ring-emerald-500/20 transition-colors"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    placeholder="user@company.com"
                                />
                                <InputError class="mt-1.5" :message="form.errors.email" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-zinc-300">Password</label>
                            <div class="mt-2">
                                <input
                                    id="password"
                                    type="password"
                                    class="block w-full rounded-lg border-zinc-800 bg-zinc-900/50 py-2.5 px-3 text-sm text-zinc-50 placeholder-zinc-500 focus:border-emerald-500 focus:ring-emerald-500/20 transition-colors"
                                    v-model="form.password"
                                    required
                                    placeholder="••••••••"
                                />
                                <InputError class="mt-1.5" :message="form.errors.password" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <input id="remember" type="checkbox" v-model="form.remember" class="w-4 h-4 rounded border-zinc-700 bg-zinc-900 text-emerald-500 focus:ring-emerald-500/20 focus:ring-offset-zinc-950">
                                <label for="remember" class="text-sm text-zinc-400">Remember me</label>
                            </div>
                            
                            <Link :href="route('password.request')" class="text-sm font-medium text-emerald-400 hover:text-emerald-300">
                                Forgot password?
                            </Link>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="mt-4 w-full flex justify-center items-center gap-2 rounded-xl bg-emerald-400 px-4 py-3.5 text-sm font-bold text-zinc-950 shadow-[0_0_20px_rgba(52,211,153,0.2)] transition-all hover:bg-emerald-500 hover:-translate-y-0.5 hover:shadow-[0_10px_25px_-5px_rgba(52,211,153,0.4)] disabled:opacity-50"
                        >
                            <span v-if="form.processing">Signing in...</span>
                            <span v-else>Sign in to account</span>
                            <svg v-if="!form.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: Hero Image -->
        <div class="relative hidden w-0 flex-1 lg:block bg-zinc-900">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/80 to-transparent z-10"></div>
            <img class="absolute inset-0 h-full w-full object-cover opacity-80 mix-blend-luminosity" src="/images/hero_login_new.jpg" alt="Corporate office hero" />
            
            <div class="absolute bottom-12 left-12 right-12 z-20">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-zinc-900/50 backdrop-blur-md border border-zinc-700/50 text-xs font-semibold text-zinc-300 mb-4">
                    Enterprise Edition
                </div>
                <h1 class="text-4xl font-bold text-white mb-3 max-w-xl leading-tight">
                    Smart Resource Booking & Allocation
                </h1>
                <p class="text-lg text-zinc-300 max-w-lg">
                    Reserve the resources your organization needs. Seamlessly manage meeting rooms, vehicles, and equipment.
                </p>
            </div>
        </div>
    </div>
</template>
