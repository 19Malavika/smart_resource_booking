<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const form = useForm({
    name: '',
    email: '',
    role: 'employee',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

onMounted(() => {
    // Basic reveal animation on mount
    const elements = document.querySelectorAll('.reveal-up-form');
    elements.forEach((el, index) => {
        setTimeout(() => {
            el.classList.add('opacity-100', 'translate-y-0');
            el.classList.remove('opacity-0', 'translate-y-4');
        }, index * 100);
    });
});
</script>

<template>
    <Head title="Create Account - ResourceHQ" />
    
    <!-- Import Inter Font for Auth Pages -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="relative flex min-h-screen flex-col justify-center bg-zinc-950 py-12 sm:px-6 lg:px-8 overflow-hidden antialiased text-zinc-50" style="font-family: 'Inter', sans-serif;">
        
        <!-- Premium Ambient Background Glows -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden z-0">
            <div class="absolute -top-40 left-1/2 -z-10 h-[800px] w-[800px] -translate-x-1/2 rounded-full bg-gradient-to-tr from-emerald-500/10 via-zinc-900/40 to-transparent blur-3xl"></div>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
            <!-- Brand Header -->
            <div class="text-center reveal-up-form opacity-0 translate-y-4 transition-all duration-700 ease-out">
                <Link href="/" class="inline-flex items-center justify-center gap-3 transition-transform hover:-translate-y-1">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-50 text-xl font-bold text-zinc-950 shadow-[0_0_15px_rgba(255,255,255,0.2)]">
                        R
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-zinc-50">
                        ResourceHQ
                    </span>
                </Link>

                <h2 class="mt-8 text-3xl font-bold tracking-tight text-zinc-50">
                    Create your account
                </h2>
                <p class="mt-3 text-sm text-zinc-400">
                    Join thousands of teams streamlining their workspace.
                </p>
            </div>

            <!-- Registration Form Card -->
            <div class="mt-10 sm:mx-auto sm:w-full reveal-up-form opacity-0 translate-y-4 transition-all duration-700 ease-out delay-100">
                <div class="overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900/60 backdrop-blur-md p-6 shadow-2xl sm:p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Full Name -->
                        <div>
                            <InputLabel for="name" value="Full name" class="text-sm font-medium text-zinc-300" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-2 block w-full rounded-xl border-zinc-700 bg-zinc-950/50 py-3 px-4 text-sm text-zinc-50 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 focus:bg-zinc-900 transition-colors"
                                v-model="form.name"
                                required
                                autofocus
                                placeholder="e.g. Alex Rivera"
                            />
                            <InputError class="mt-2 text-red-400" :message="form.errors.name" />
                        </div>

                        <!-- Corporate Email -->
                        <div>
                            <InputLabel for="email" value="Work email" class="text-sm font-medium text-zinc-300" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-2 block w-full rounded-xl border-zinc-700 bg-zinc-950/50 py-3 px-4 text-sm text-zinc-50 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 focus:bg-zinc-900 transition-colors"
                                v-model="form.email"
                                required
                                placeholder="alex@company.com"
                            />
                            <InputError class="mt-2 text-red-400" :message="form.errors.email" />
                        </div>

                        <!-- User Role Selection -->
                        <div>
                            <InputLabel value="I am a..." class="text-sm font-medium text-zinc-300 mb-2" />
                            <div class="grid grid-cols-3 gap-3">
                                <button
                                    type="button"
                                    @click="form.role = 'employee'"
                                    :class="form.role === 'employee' ? 'bg-emerald-500/10 border-emerald-500 text-emerald-400 ring-1 ring-emerald-500 shadow-[0_0_15px_rgba(52,211,153,0.15)]' : 'bg-zinc-950/50 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200'"
                                    class="rounded-xl border py-3 px-3 text-xs sm:text-sm font-semibold transition-all flex flex-col items-center gap-2"
                                >
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Employee
                                </button>
                                <button
                                    type="button"
                                    @click="form.role = 'manager'"
                                    :class="form.role === 'manager' ? 'bg-emerald-500/10 border-emerald-500 text-emerald-400 ring-1 ring-emerald-500 shadow-[0_0_15px_rgba(52,211,153,0.15)]' : 'bg-zinc-950/50 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200'"
                                    class="rounded-xl border py-3 px-3 text-xs sm:text-sm font-semibold transition-all flex flex-col items-center gap-2"
                                >
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Manager
                                </button>
                                <button
                                    type="button"
                                    @click="form.role = 'admin'"
                                    :class="form.role === 'admin' ? 'bg-emerald-500/10 border-emerald-500 text-emerald-400 ring-1 ring-emerald-500 shadow-[0_0_15px_rgba(52,211,153,0.15)]' : 'bg-zinc-950/50 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200'"
                                    class="rounded-xl border py-3 px-3 text-xs sm:text-sm font-semibold transition-all flex flex-col items-center gap-2"
                                >
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Admin
                                </button>
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <InputLabel for="password" value="Password" class="text-sm font-medium text-zinc-300" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-2 block w-full rounded-xl border-zinc-700 bg-zinc-950/50 py-3 px-4 text-sm text-zinc-50 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 focus:bg-zinc-900 transition-colors"
                                v-model="form.password"
                                required
                                placeholder="••••••••"
                            />
                            <InputError class="mt-2 text-red-400" :message="form.errors.password" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <InputLabel for="password_confirmation" value="Confirm password" class="text-sm font-medium text-zinc-300" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-2 block w-full rounded-xl border-zinc-700 bg-zinc-950/50 py-3 px-4 text-sm text-zinc-50 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 focus:bg-zinc-900 transition-colors"
                                v-model="form.password_confirmation"
                                required
                                placeholder="••••••••"
                            />
                            <InputError class="mt-2 text-red-400" :message="form.errors.password_confirmation" />
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="mt-4 w-full flex justify-center items-center gap-2 rounded-xl bg-emerald-400 px-4 py-3.5 text-sm font-bold text-zinc-950 shadow-[0_0_20px_rgba(52,211,153,0.2)] transition-all hover:bg-emerald-500 hover:-translate-y-0.5 hover:shadow-[0_10px_25px_-5px_rgba(52,211,153,0.4)] disabled:opacity-50"
                        >
                            {{ form.processing ? 'Creating account...' : 'Create Account' }}
                            <svg v-if="!form.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>

                        <div class="pt-6 mt-4 text-center text-sm text-zinc-400 border-t border-zinc-800 flex justify-center items-center gap-1.5">
                            <span>Already have an account?</span>
                            <Link :href="route('login')" class="font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">
                                Sign in instead
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
/* Remove guest layout default wrappers since we are rendering raw here */
</style>
