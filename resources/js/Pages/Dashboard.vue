<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { PhArrowRight, PhCalendarCheck, PhFire, PhBarbell, PhTrendUp } from '@phosphor-icons/vue';
import MemberLayout from '@/Layouts/MemberLayout.vue';
import { computed, onMounted } from 'vue';
import gsap from 'gsap';

defineOptions({ layout: MemberLayout });

const props = defineProps({
    auth: Object,
    bookings: { type: Array, default: () => [] }
});

const activeBookings = computed(() => props.bookings.filter(b => b.status === 'approved').length);
const totalBookings = computed(() => props.bookings.length);
const waitingBookings = computed(() => props.bookings.filter(b => b.status === 'waiting_verification').length);

onMounted(() => {
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    // Animate banner
    tl.from('.dashboard-banner', { y: 30, opacity: 0, duration: 0.8, clearProps: 'all' })
      .from('.banner-text', { y: 20, opacity: 0, duration: 0.6, stagger: 0.1, clearProps: 'all' }, '-=0.4')
      .from('.banner-btn', { scale: 0.9, opacity: 0, duration: 0.5, clearProps: 'all' }, '-=0.2');

    // Animate stat cards
    tl.from('.stat-card', { 
        y: 30, 
        opacity: 0, 
        duration: 0.6, 
        stagger: 0.1,
        clearProps: 'all'
    }, '-=0.2');
});
</script>

<template>
    <Head title="Home Dashboard" />

    <div class="space-y-8 pb-10">

        <!-- Premium Welcome Banner -->
        <div class="dashboard-banner relative overflow-hidden rounded-[2.5rem] bg-[#121212]/80 backdrop-blur-xl border border-white/10 p-8 sm:p-12 shadow-[0_20px_60px_rgba(0,0,0,0.5)] group">
            <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 bg-gym-red/20 rounded-full blur-[100px] pointer-events-none group-hover:bg-gym-red/30 transition-colors duration-700"></div>
            <div class="absolute bottom-0 left-0 -ml-32 -mb-32 w-96 h-96 bg-gym-yellow/15 rounded-full blur-[100px] pointer-events-none group-hover:bg-gym-yellow/25 transition-colors duration-700 delay-100"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-10">
                <div class="text-center lg:text-left flex-1">
                    <div class="banner-text inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-gym-yellow animate-pulse"></span>
                        <span class="text-xs font-medium text-gray-300">Sistem Pemesanan PT Aktif</span>
                    </div>

                    <h1 class="banner-text text-4xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight mb-4 leading-tight">
                        Waktunya <br class="hidden lg:block"/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-gym-yellow via-white to-gym-red">Hancurkan Batasmu.</span>
                    </h1>
                    <p class="banner-text text-gray-400 text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Selamat datang kembali, <strong class="text-white">{{ auth.user.name }}</strong>. Temukan personal trainer terbaik, susun program latihanmu, dan capai body goals impianmu hari ini.
                    </p>
                </div>

                <div class="flex-shrink-0">
                    <Link
                        :href="route('member.find-pt')"
                        class="banner-btn relative inline-flex items-center justify-center gap-3 px-10 py-5 bg-white text-black font-bold rounded-full overflow-hidden transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_40px_rgba(255,255,255,0.3)] group/btn"
                    >
                        <span class="relative z-10 flex items-center gap-3 text-lg">
                            Cari Trainer Sekarang <PhArrowRight :size="24" weight="bold" class="group-hover/btn:translate-x-2 transition-transform duration-300" />
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-gray-200 to-white opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Section Title -->
        <div class="pt-6 pb-2 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <PhTrendUp :size="28" class="text-gym-red" weight="duotone" /> Overview Kamu
            </h2>
        </div>

        <!-- Dynamic Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Card 1: Jadwal Aktif -->
            <Link :href="route('member.bookings')" class="stat-card relative overflow-hidden bg-[#121212]/60 backdrop-blur-md border border-white/5 rounded-3xl p-6 hover:border-gym-red/30 hover:bg-[#1a1a1a]/80 transition-all duration-500 group transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gym-red/5 rounded-full blur-[50px] group-hover:bg-gym-red/20 transition-colors duration-500"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gym-red/20 to-red-900/20 flex items-center justify-center mb-5 border border-gym-red/20 shadow-[inset_0_1px_0_rgba(255,255,255,0.1)] group-hover:scale-110 transition-transform duration-500">
                        <PhCalendarCheck :size="28" class="text-gym-red" weight="fill" />
                    </div>
                    <p class="text-4xl font-bold text-white mb-1">{{ activeBookings }}</p>
                    <h3 class="text-white font-bold text-lg">Jadwal Aktif</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Sesi latihan yang sudah disetujui PT.</p>
                </div>
            </Link>

            <!-- Card 2: Menunggu Konfirmasi -->
            <Link :href="route('member.bookings')" class="stat-card relative overflow-hidden bg-[#121212]/60 backdrop-blur-md border border-white/5 rounded-3xl p-6 hover:border-gym-yellow/30 hover:bg-[#1a1a1a]/80 transition-all duration-500 group transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gym-yellow/5 rounded-full blur-[50px] group-hover:bg-gym-yellow/20 transition-colors duration-500"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gym-yellow/20 to-yellow-900/20 flex items-center justify-center mb-5 border border-gym-yellow/20 shadow-[inset_0_1px_0_rgba(255,255,255,0.1)] group-hover:scale-110 transition-transform duration-500">
                        <PhFire :size="28" class="text-gym-yellow" weight="fill" />
                    </div>
                    <p class="text-4xl font-bold text-white mb-1">{{ waitingBookings }}</p>
                    <h3 class="text-white font-bold text-lg">Menunggu Konfirmasi</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Booking yang sedang menunggu respon PT.</p>
                </div>
            </Link>

            <!-- Card 3: Total Booking -->
            <Link :href="route('member.bookings')" class="stat-card relative overflow-hidden bg-[#121212]/60 backdrop-blur-md border border-white/5 rounded-3xl p-6 hover:border-blue-500/30 hover:bg-[#1a1a1a]/80 transition-all duration-500 group transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full blur-[50px] group-hover:bg-blue-500/20 transition-colors duration-500"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500/20 to-blue-900/20 flex items-center justify-center mb-5 border border-blue-500/20 shadow-[inset_0_1px_0_rgba(255,255,255,0.1)] group-hover:scale-110 transition-transform duration-500">
                        <PhBarbell :size="28" class="text-blue-400" weight="fill" />
                    </div>
                    <p class="text-4xl font-bold text-white mb-1">{{ totalBookings }}</p>
                    <h3 class="text-white font-bold text-lg">Total Riwayat</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Seluruh sesi yang pernah kamu pesan.</p>
                </div>
            </Link>
        </div>
    </div>
</template>
