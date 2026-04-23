<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { PhBarbell, PhArrowRight, PhStar, PhShieldCheck, PhLightning } from '@phosphor-icons/vue';
import gsap from 'gsap';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

onMounted(() => {
    // GSAP Animations
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    // Header animation
    tl.from('header', { y: -50, opacity: 0, duration: 1, clearProps: 'all' })
      
      // Hero text animation
      .from('.hero-title', { y: 30, opacity: 0, duration: 0.8, stagger: 0.2, clearProps: 'all' }, '-=0.5')
      .from('.hero-subtitle', { y: 20, opacity: 0, duration: 0.8, clearProps: 'all' }, '-=0.4')
      .from('.hero-cta', { y: 20, opacity: 0, duration: 0.8, clearProps: 'all' }, '-=0.4')
      
      // Features stagger
      .from('.feature-card', { 
          y: 40, 
          opacity: 0, 
          duration: 0.8, 
          stagger: 0.15,
          clearProps: 'all'
      }, '-=0.2');
});
</script>

<template>
    <Head title="Welcome to Gym One Point" />
    
    <div class="min-h-screen bg-[#0a0a0a] text-white overflow-hidden selection:bg-gym-red selection:text-white">
        <!-- Abstract Background -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-gym-red/10 blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-gym-yellow/10 blur-[120px]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="flex items-center justify-between py-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-gym-red to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-gym-red/20">
                        <PhBarbell weight="fill" class="text-white text-xl" />
                    </div>
                    <span class="text-xl font-bold tracking-tight">One Point</span>
                </div>
                
                <nav v-if="canLogin" class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-sm font-medium hover:text-gym-yellow transition-colors"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-sm font-medium text-gray-300 hover:text-white transition-colors"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="text-sm font-bold bg-white text-black px-5 py-2.5 rounded-full hover:bg-gray-200 transition-colors"
                        >
                            Daftar Sekarang
                        </Link>
                    </template>
                </nav>
            </header>

            <!-- Hero Section -->
            <main class="flex-1 flex flex-col items-center justify-center text-center pt-10 pb-20">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-8 hero-title backdrop-blur-md">
                    <PhLightning weight="fill" class="text-gym-yellow" />
                    <span class="text-sm font-medium text-gray-300 uppercase tracking-wider">Sistem Rekomendasi Pintar</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 leading-tight max-w-4xl hero-title">
                    Temukan <span class="text-transparent bg-clip-text bg-gradient-to-r from-gym-red to-gym-yellow">Personal Trainer</span> Terbaikmu.
                </h1>
                
                <p class="text-lg md:text-xl text-gray-400 mb-10 max-w-2xl hero-subtitle font-light leading-relaxed">
                    Gym One Point Lamongan menggunakan algoritma multi-dimensi untuk mencocokkan tujuan kebugaran, kondisi fisik, dan jadwal Anda dengan Personal Trainer yang tepat.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 hero-cta">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="px-8 py-4 bg-gradient-to-r from-gym-red to-orange-600 rounded-full font-bold text-lg hover:shadow-lg hover:shadow-gym-red/30 transition-all flex items-center justify-center gap-2 group">
                        Masuk Dashboard
                        <PhArrowRight weight="bold" class="group-hover:translate-x-1 transition-transform" />
                    </Link>
                    <Link v-else :href="route('register')" class="px-8 py-4 bg-gradient-to-r from-gym-red to-orange-600 rounded-full font-bold text-lg hover:shadow-lg hover:shadow-gym-red/30 transition-all flex items-center justify-center gap-2 group">
                        Mulai Sekarang
                        <PhArrowRight weight="bold" class="group-hover:translate-x-1 transition-transform" />
                    </Link>
                    
                    <a href="#features" class="px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full font-bold text-lg transition-all flex items-center justify-center">
                        Pelajari Lebih Lanjut
                    </a>
                </div>

                <!-- Features Grid -->
                <div id="features" class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-32 w-full text-left">
                    <div class="feature-card bg-[#121212]/80 backdrop-blur-xl border border-white/5 p-8 rounded-[2rem] hover:border-white/10 transition-colors">
                        <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-6 border border-blue-500/20">
                            <PhShieldCheck weight="fill" class="text-2xl text-blue-400" />
                        </div>
                        <h3 class="text-xl font-bold mb-3">Keamanan & Medis</h3>
                        <p class="text-gray-400 font-light leading-relaxed">Algoritma mempertimbangkan riwayat cedera Anda untuk merekomendasikan PT dengan keahlian fisioterapi atau rehabilitasi.</p>
                    </div>

                    <div class="feature-card bg-[#121212]/80 backdrop-blur-xl border border-white/5 p-8 rounded-[2rem] hover:border-white/10 transition-colors">
                        <div class="w-12 h-12 bg-gym-yellow/10 rounded-2xl flex items-center justify-center mb-6 border border-gym-yellow/20">
                            <PhStar weight="fill" class="text-2xl text-gym-yellow" />
                        </div>
                        <h3 class="text-xl font-bold mb-3">Fuzzy Recommendation</h3>
                        <p class="text-gray-400 font-light leading-relaxed">Tidak ada match 100%? Sistem akan otomatis mencarikan alternatif trainer terbaik berdasarkan skor kecocokan tertinggi.</p>
                    </div>

                    <div class="feature-card bg-[#121212]/80 backdrop-blur-xl border border-white/5 p-8 rounded-[2rem] hover:border-white/10 transition-colors">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 border border-emerald-500/20">
                            <PhBarbell weight="fill" class="text-2xl text-emerald-400" />
                        </div>
                        <h3 class="text-xl font-bold mb-3">Manajemen Jadwal</h3>
                        <p class="text-gray-400 font-light leading-relaxed">Pilih shift latihan dengan mudah tanpa bentrok jadwal. Konfirmasi dari PT secara real-time melalui dashboard interaktif.</p>
                    </div>
                </div>
            </main>
            
            <footer class="py-8 text-center border-t border-white/5 text-gray-500 text-sm">
                &copy; {{ new Date().getFullYear() }} Gym One Point Lamongan. All rights reserved.
            </footer>
        </div>
    </div>
</template>
