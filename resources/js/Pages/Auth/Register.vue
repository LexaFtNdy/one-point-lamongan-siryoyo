<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { PhUser, PhEnvelopeSimple, PhLockKey, PhArrowRight } from '@phosphor-icons/vue';
import { toast } from 'vue-sonner';
import AuthLayout from '@/Layouts/AuthLayout.vue';

// Gunakan layout persisten agar sebelah kiri tidak re-render
defineOptions({ layout: AuthLayout });

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    gender: 'no_preference',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onError: () => toast.error('Terjadi kesalahan pada data registrasi.'),
    });
};
</script>

<template>
    <Head title="Register - One Point" />

    <!-- Mobile Logo -->
    <div class="lg:hidden mb-10">
        <img src="/images/logo.png" alt="One Point Logo" class="h-16 w-auto object-contain drop-shadow-xl" @error="$event.target.style.display='none'" />
    </div>

    <div class="mb-10">
        <h2 class="text-3xl font-semibold tracking-tight mb-2 text-white">Buat Akun</h2>
        <p class="text-gray-400 text-sm">Lengkapi detail di bawah untuk mendaftar.</p>
    </div>

    <form @submit.prevent="submit" class="space-y-5">
        <!-- Nama Lengkap -->
        <div class="space-y-2">
            <label for="name" class="text-sm font-medium text-gray-300">Nama Lengkap</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500 group-focus-within:text-gym-red transition-colors">
                    <PhUser :size="18" />
                </div>
                <input 
                    id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                    class="w-full pl-10 pr-4 py-3 bg-[#121212] border border-gray-800 rounded-xl text-white placeholder-gray-600 focus:bg-[#1A1A1A] focus:border-gym-red/50 focus:ring-1 focus:ring-gym-red/50 transition-all duration-200 outline-none sm:text-sm"
                    placeholder="John Doe"
                />
            </div>
            <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
        </div>

        <!-- Email -->
        <div class="space-y-2">
            <label for="email" class="text-sm font-medium text-gray-300">Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500 group-focus-within:text-gym-red transition-colors">
                    <PhEnvelopeSimple :size="18" />
                </div>
                <input 
                    id="email" type="email" v-model="form.email" required autocomplete="username"
                    class="w-full pl-10 pr-4 py-3 bg-[#121212] border border-gray-800 rounded-xl text-white placeholder-gray-600 focus:bg-[#1A1A1A] focus:border-gym-red/50 focus:ring-1 focus:ring-gym-red/50 transition-all duration-200 outline-none sm:text-sm"
                    placeholder="nama@email.com"
                />
            </div>
            <p v-if="form.errors.email" class="text-xs text-red-500 mt-1">{{ form.errors.email }}</p>
        </div>

        <!-- Gender Selection -->
        <div class="space-y-2">
            <label class="text-sm font-medium text-gray-300">Jenis Kelamin</label>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" @click="form.gender = 'male'" :class="['px-2 py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-all text-center border', form.gender === 'male' ? 'bg-gym-red text-white border-gym-red shadow-lg shadow-gym-red/30' : 'bg-[#121212] border-gray-800 text-gray-400 hover:bg-[#1A1A1A]']">Laki-laki</button>
                <button type="button" @click="form.gender = 'female'" :class="['px-2 py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-all text-center border', form.gender === 'female' ? 'bg-gym-red text-white border-gym-red shadow-lg shadow-gym-red/30' : 'bg-[#121212] border-gray-800 text-gray-400 hover:bg-[#1A1A1A]']">Perempuan</button>
                <button type="button" @click="form.gender = 'no_preference'" :class="['px-2 py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-all text-center border', form.gender === 'no_preference' ? 'bg-gym-red text-white border-gym-red shadow-lg shadow-gym-red/30' : 'bg-[#121212] border-gray-800 text-gray-400 hover:bg-[#1A1A1A]']">Rahasiakan</button>
            </div>
            <p v-if="form.errors.gender" class="text-xs text-red-500 mt-1">{{ form.errors.gender }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="text-sm font-medium text-gray-300">Kata Sandi</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500 group-focus-within:text-gym-red transition-colors">
                        <PhLockKey :size="18" />
                    </div>
                    <input 
                        id="password" type="password" v-model="form.password" required autocomplete="new-password"
                        class="w-full pl-10 pr-4 py-3 bg-[#121212] border border-gray-800 rounded-xl text-white placeholder-gray-600 focus:bg-[#1A1A1A] focus:border-gym-red/50 focus:ring-1 focus:ring-gym-red/50 transition-all duration-200 outline-none sm:text-sm"
                        placeholder="••••••••"
                    />
                </div>
                <p v-if="form.errors.password" class="text-xs text-red-500 mt-1">{{ form.errors.password }}</p>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="text-sm font-medium text-gray-300">Konfirmasi</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500 group-focus-within:text-gym-red transition-colors">
                        <PhLockKey :size="18" />
                    </div>
                    <input 
                        id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password"
                        class="w-full pl-10 pr-4 py-3 bg-[#121212] border border-gray-800 rounded-xl text-white placeholder-gray-600 focus:bg-[#1A1A1A] focus:border-gym-red/50 focus:ring-1 focus:ring-gym-red/50 transition-all duration-200 outline-none sm:text-sm"
                        placeholder="••••••••"
                    />
                </div>
                <p v-if="form.errors.password_confirmation" class="text-xs text-red-500 mt-1">{{ form.errors.password_confirmation }}</p>
            </div>
        </div>

        <!-- Branded Submit Button -->
        <button 
            type="submit" 
            :disabled="form.processing"
            class="w-full py-3 bg-gym-yellow text-black hover:bg-yellow-500 font-semibold rounded-xl transition-all flex items-center justify-center gap-2 mt-8 disabled:opacity-50 disabled:cursor-not-allowed group shadow-[0_4px_14px_0_rgba(255,193,7,0.39)] hover:shadow-[0_6px_20px_rgba(255,193,7,0.23)] text-sm"
        >
            <span v-if="!form.processing">Daftar Akun</span>
            <span v-else>Memproses...</span>
            <PhArrowRight v-if="!form.processing" weight="bold" class="group-hover:translate-x-1 transition-transform" />
        </button>
        
        <div class="mt-8 text-center text-sm text-gray-500">
            Sudah punya akun? 
            <Link :href="route('login')" class="text-gym-red font-medium hover:text-red-400 hover:underline underline-offset-4 transition-all">Masuk di sini</Link>
        </div>
    </form>
</template>
