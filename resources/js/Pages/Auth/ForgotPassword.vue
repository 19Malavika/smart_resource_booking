<script setup>
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
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password - ResourceHQ" />

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
            <div class="text-center transition-all duration-700 ease-out">
                <Link href="/" class="inline-flex items-center justify-center gap-3 transition-transform hover:-translate-y-1">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-50 text-xl font-bold text-zinc-950 shadow-[0_0_15px_rgba(255,255,255,0.2)]">
                        R
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-zinc-50">
                        ResourceHQ
                    </span>
                </Link>

                <h2 class="mt-8 text-3xl font-bold tracking-tight text-zinc-50">
                    Reset Password
                </h2>
                <p class="mt-3 text-sm text-zinc-400 px-4">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
                </p>
            </div>

            <!-- Form Card -->
            <div class="mt-10 sm:mx-auto sm:w-full transition-all duration-700 ease-out delay-100">
                <div class="overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900/60 backdrop-blur-md p-6 shadow-2xl sm:p-8">
                    
                    <div
                        v-if="status"
                        class="mb-6 rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm font-medium text-emerald-400"
                    >
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Email -->
                        <div>
                            <InputLabel for="email" value="Email address" class="text-sm font-medium text-zinc-300" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-2 block w-full rounded-xl border-zinc-700 bg-zinc-950/50 py-3 px-4 text-sm text-zinc-50 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 focus:bg-zinc-900 transition-colors placeholder-zinc-600"
                                v-model="form.email"
                                required
                                autofocus
                                placeholder="name@company.com"
                            />
                            <InputError class="mt-2 text-red-400" :message="form.errors.email" />
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="mt-4 w-full flex justify-center items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-emerald-500 px-4 py-3.5 text-sm font-bold text-zinc-950 shadow-[0_0_20px_rgba(52,211,153,0.3)] transition-all hover:shadow-[0_0_40px_rgba(52,211,153,0.5)] hover:-translate-y-0.5 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Sending...' : 'Email Password Reset Link' }}
                            <svg v-if="!form.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>

                        <div class="pt-6 mt-4 text-center text-sm text-zinc-400 border-t border-zinc-800 flex justify-center items-center gap-1.5">
                            <span>Remember your password?</span>
                            <Link :href="route('login')" class="font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">
                                Back to login
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
/* Remove layout wrappers */
</style>
