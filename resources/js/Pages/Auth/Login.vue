<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Sign in — CRM by sell.ke" />

    <div class="min-h-screen flex">

        <!-- Left branding panel -->
        <div class="hidden lg:flex lg:w-1/2 bg-primary flex-col justify-between p-12 relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/5"></div>
                <div class="absolute top-1/3 -left-16 w-64 h-64 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-20 right-1/4 w-80 h-80 rounded-full bg-white/5"></div>
            </div>

            <!-- Logo -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 border border-white/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5.477-3.77M9 20H4v-2a4 4 0 015.477-3.77m0 0A4 4 0 1112 6a4 4 0 012.523 7.23"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-xl tracking-tight">CRM <span class="text-white/70">by sell.ke</span></span>
            </div>

            <!-- Hero text -->
            <div class="relative z-10">
                <h1 class="text-4xl font-bold text-white leading-tight mb-4">
                    Manage your pipeline.<br/>Close more deals.
                </h1>
                <p class="text-white/70 text-lg leading-relaxed max-w-sm">
                    Track leads, manage accounts, and collaborate with your team — all in one place.
                </p>

                <!-- Feature bullets -->
                <div class="mt-10 space-y-4">
                    <div v-for="feat in [
                        { icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', label: 'Real-time pipeline analytics' },
                        { icon: 'M17 20h5v-2a4 4 0 00-5.477-3.77M9 20H4v-2a4 4 0 015.477-3.77m0 0A4 4 0 1112 6a4 4 0 012.523 7.23', label: 'Team collaboration & leaderboards' },
                        { icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', label: 'Stage-based deal tracking' },
                    ]" :key="feat.label" class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feat.icon"/>
                            </svg>
                        </div>
                        <span class="text-white/80 text-sm font-medium">{{ feat.label }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10">
                <p class="text-white/40 text-xs">© 2026 sell.ke · All rights reserved</p>
            </div>
        </div>

        <!-- Right form panel -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-gray-50 px-6 py-12">

            <!-- Mobile logo -->
            <div class="lg:hidden flex items-center gap-2 mb-10">
                <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5.477-3.77M9 20H4v-2a4 4 0 015.477-3.77m0 0A4 4 0 1112 6a4 4 0 012.523 7.23"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-800 text-lg">CRM <span class="text-primary">by sell.ke</span></span>
            </div>

            <div class="w-full max-w-md">
                <!-- Heading -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
                    <p class="text-gray-500 text-sm mt-1">Sign in to your account to continue</p>
                </div>

                <!-- Status message -->
                <div v-if="status" class="mb-5 p-3 rounded-xl bg-green-50 border border-green-200 text-sm text-green-700 font-medium">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email -->
                    <div>
                        <InputLabel for="email" value="Email address" class="text-sm font-medium text-gray-700 mb-1.5" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:ring-primary focus:border-primary text-sm py-3"
                            placeholder="you@company.com"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-1.5" :message="form.errors.email" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <InputLabel for="password" value="Password" class="text-sm font-medium text-gray-700" />
                            <Link v-if="canResetPassword" :href="route('password.request')"
                                class="text-xs text-primary hover:text-primary-dark font-medium transition">
                                Forgot password?
                            </Link>
                        </div>
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:ring-primary focus:border-primary text-sm py-3"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        />
                        <InputError class="mt-1.5" :message="form.errors.password" />
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center">
                        <Checkbox v-model:checked="form.remember" name="remember" id="remember" class="rounded" />
                        <label for="remember" class="ml-2 text-sm text-gray-600 cursor-pointer select-none">Keep me signed in</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        :disabled="form.processing"
                        class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-primary hover:bg-primary-dark text-white font-semibold rounded-xl shadow-md shadow-primary/25 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm mt-2">
                        <svg v-if="form.processing" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        {{ form.processing ? 'Signing in…' : 'Sign in' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>


