<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { PhEnvelopeSimple, PhLockKey, PhArrowRight } from '@phosphor-icons/vue';
import { toast } from 'vue-sonner';
import AuthLayout from '@/Layouts/AuthLayout.vue';

// Gunakan layout persisten agar sebelah kiri tidak re-render
defineOptions({ layout: AuthLayout });

defineProps({
    canResetPassword: {
        type: Boolean,
    },
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
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onError: () => toast.error('Email atau Password salah.'),
    });
};
</script>

<template>
    <Head title="Login - One Point" />

    <!-- Mobile Logo -->
    <div class="lg:hidden mb-12">
        <img src="/images/logo.png" alt="One Point Logo" class="h-16 w-auto object-contain drop-shadow-xl" @error="$event.target.style.display='none'" />
    </div>

    <div class="mb-10">
        <h2 class="text-3xl font-semibold tracking-tight mb-2 text-white">Selamat Datang</h2>
        <p class="text-gray-400 text-sm">Masuk ke akun One Point Anda.</p>
    </div>

    <div v-if="status" class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-sm font-medium text-green-400">
        {{ status }}
    </div>

    <form @submit.prevent="submit" class="space-y-5">
        <!-- Elegant Email Input -->
        <div class="space-y-2">
            <label for="email" class="text-sm font-medium text-gray-300">Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500 group-focus-within:text-gym-red transition-colors">
                    <PhEnvelopeSimple :size="18" />
                </div>
                <input 
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full pl-10 pr-4 py-3 bg-[#121212] border border-gray-800 rounded-xl text-white placeholder-gray-600 focus:bg-[#1A1A1A] focus:border-gym-red/50 focus:ring-1 focus:ring-gym-red/50 transition-all duration-200 outline-none sm:text-sm"
                    placeholder="nama@email.com"
                />
            </div>
            <p v-if="form.errors.email" class="text-xs text-red-500 mt-1">{{ form.errors.email }}</p>
        </div>

        <!-- Elegant Password Input -->
        <div class="space-y-2">
            <div class="flex justify-between items-center">
                <label for="password" class="text-sm font-medium text-gray-300">Kata Sandi</label>
                <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs text-gray-500 hover:text-gym-yellow transition-colors">
                    Lupa sandi?
                </Link>
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500 group-focus-within:text-gym-red transition-colors">
                    <PhLockKey :size="18" />
                </div>
                <input 
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    class="w-full pl-10 pr-4 py-3 bg-[#121212] border border-gray-800 rounded-xl text-white placeholder-gray-600 focus:bg-[#1A1A1A] focus:border-gym-red/50 focus:ring-1 focus:ring-gym-red/50 transition-all duration-200 outline-none sm:text-sm"
                    placeholder="••••••••"
                />
            </div>
            <p v-if="form.errors.password" class="text-xs text-red-500 mt-1">{{ form.errors.password }}</p>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-3 pt-2">
            <button
                type="button"
                @click="form.remember = !form.remember"
                :class="['relative w-10 h-6 rounded-full transition-colors duration-300 focus:outline-none', form.remember ? 'bg-gym-red' : 'bg-white/10']"
                role="switch"
                :aria-checked="form.remember"
            >
                <div :class="['absolute top-1 w-4 h-4 rounded-full bg-white shadow-md transition-all duration-300', form.remember ? 'left-5' : 'left-1']"></div>
            </button>
            <label @click="form.remember = !form.remember" class="text-sm text-gray-400 cursor-pointer select-none">
                Ingat saya
            </label>
        </div>

        <!-- Branded Submit Button -->
        <button 
            type="submit" 
            :disabled="form.processing"
            class="w-full py-3 bg-gym-red text-white hover:bg-red-700 font-semibold rounded-xl transition-all flex items-center justify-center gap-2 mt-8 disabled:opacity-50 disabled:cursor-not-allowed group shadow-[0_4px_14px_0_rgba(214,0,0,0.39)] hover:shadow-[0_6px_20px_rgba(214,0,0,0.23)] text-sm"
        >
            <span v-if="!form.processing">Masuk ke Sistem</span>
            <span v-else>Memverifikasi...</span>
            <PhArrowRight v-if="!form.processing" weight="bold" class="group-hover:translate-x-1 transition-transform" />
        </button>
        
        <div class="mt-8 text-center text-sm text-gray-500">
            Belum punya akun? 
            <Link :href="route('register')" class="text-gym-yellow font-medium hover:text-yellow-400 hover:underline underline-offset-4 transition-all">Daftar sekarang</Link>
        </div>
    </form>
</template>
