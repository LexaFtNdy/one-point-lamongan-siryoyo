<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import gsap from 'gsap';
import { PhUsers, PhBarbell, PhCalendarCheck, PhTrendUp, PhArrowRight, PhCheckCircle, PhXCircle, PhClock, PhChartBar } from '@phosphor-icons/vue';
import MemberLayout from '@/Layouts/MemberLayout.vue';

defineOptions({ layout: MemberLayout });

const props = defineProps({
    auth: Object,
    stats: Object,
    recentBookings: { type: Array, default: () => [] }
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
};

onMounted(() => {
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    tl.from('.header-anim', { y: 30, opacity: 0, duration: 0.8, clearProps: 'all' })
      .from('.dashboard-card', { y: 40, opacity: 0, duration: 0.6, stagger: 0.1, clearProps: 'all' }, '-=0.4');
});
</script>

<template>
    <Head title="Admin Dashboard - One Point" />

    <div class="space-y-8 pb-10">

        <!-- Welcome Banner -->
        <div class="header-anim relative overflow-hidden rounded-[2.5rem] bg-[#121212]/80 backdrop-blur-xl border border-white/10 p-8 sm:p-12 shadow-[0_20px_60px_rgba(0,0,0,0.5)] group">
            <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 bg-purple-600/20 rounded-full blur-[100px] pointer-events-none group-hover:bg-purple-500/30 transition-colors duration-700"></div>

            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-4 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    <span class="text-xs font-medium text-gray-300">Admin Control Panel</span>
                </div>
                <h1 class="text-4xl sm:text-5xl font-bold text-white tracking-tight mb-4 leading-tight">
                    Dashboard <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500">Admin.</span>
                </h1>
                <p class="text-gray-400 text-lg max-w-xl">Pantau statistik platform, kelola pengguna, dan monitor aktivitas secara real-time.</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="dashboard-card relative overflow-hidden bg-[#121212]/60 backdrop-blur-md border border-white/5 rounded-3xl p-6 hover:border-blue-500/30 transition-all duration-500 group transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full blur-[40px] group-hover:bg-blue-500/20 transition-colors duration-500"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center mb-4 border border-blue-500/20">
                        <PhUsers :size="24" class="text-blue-400" weight="fill" />
                    </div>
                    <p class="text-3xl font-bold text-white">{{ stats.totalMembers }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Total Member</p>
                </div>
            </div>

            <div class="dashboard-card relative overflow-hidden bg-[#121212]/60 backdrop-blur-md border border-white/5 rounded-3xl p-6 hover:border-orange-500/30 transition-all duration-500 group transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-orange-500/5 rounded-full blur-[40px] group-hover:bg-orange-500/20 transition-colors duration-500"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-orange-500/10 flex items-center justify-center mb-4 border border-orange-500/20">
                        <PhBarbell :size="24" class="text-orange-400" weight="fill" />
                    </div>
                    <p class="text-3xl font-bold text-white">{{ stats.totalTrainers }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Total PT</p>
                </div>
            </div>

            <div class="dashboard-card relative overflow-hidden bg-[#121212]/60 backdrop-blur-md border border-white/5 rounded-3xl p-6 hover:border-emerald-500/30 transition-all duration-500 group transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/5 rounded-full blur-[40px] group-hover:bg-emerald-500/20 transition-colors duration-500"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center mb-4 border border-emerald-500/20">
                        <PhCalendarCheck :size="24" class="text-emerald-400" weight="fill" />
                    </div>
                    <p class="text-3xl font-bold text-white">{{ stats.totalBookings }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Total Booking</p>
                </div>
            </div>

            <div class="dashboard-card relative overflow-hidden bg-[#121212]/60 backdrop-blur-md border border-white/5 rounded-3xl p-6 hover:border-purple-500/30 transition-all duration-500 group transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/5 rounded-full blur-[40px] group-hover:bg-purple-500/20 transition-colors duration-500"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center mb-4 border border-purple-500/20">
                        <PhCheckCircle :size="24" class="text-purple-400" weight="fill" />
                    </div>
                    <p class="text-3xl font-bold text-white">{{ stats.bookingsByStatus.approved }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Booking Aktif</p>
                </div>
            </div>
        </div>

        <!-- Booking Status Breakdown -->
        <div class="dashboard-card bg-[#121212]/80 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 md:p-8">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <PhChartBar :size="24" class="text-gym-yellow" weight="duotone" /> Breakdown Status Booking
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-yellow-500/5 border border-yellow-500/20 rounded-2xl p-4 text-center">
                    <PhClock :size="28" class="text-yellow-400 mx-auto mb-2" weight="fill" />
                    <p class="text-2xl font-bold text-yellow-400">{{ stats.bookingsByStatus.waiting }}</p>
                    <p class="text-xs text-gray-400 mt-1">Menunggu</p>
                </div>
                <div class="bg-green-500/5 border border-green-500/20 rounded-2xl p-4 text-center">
                    <PhCheckCircle :size="28" class="text-green-400 mx-auto mb-2" weight="fill" />
                    <p class="text-2xl font-bold text-green-400">{{ stats.bookingsByStatus.approved }}</p>
                    <p class="text-xs text-gray-400 mt-1">Disetujui</p>
                </div>
                <div class="bg-red-500/5 border border-red-500/20 rounded-2xl p-4 text-center">
                    <PhXCircle :size="28" class="text-red-400 mx-auto mb-2" weight="fill" />
                    <p class="text-2xl font-bold text-red-400">{{ stats.bookingsByStatus.rejected }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ditolak</p>
                </div>
                <div class="bg-blue-500/5 border border-blue-500/20 rounded-2xl p-4 text-center">
                    <PhCheckCircle :size="28" class="text-blue-400 mx-auto mb-2" weight="fill" />
                    <p class="text-2xl font-bold text-blue-400">{{ stats.bookingsByStatus.completed }}</p>
                    <p class="text-xs text-gray-400 mt-1">Selesai</p>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="dashboard-card bg-[#121212]/80 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 md:p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <PhTrendUp :size="24" class="text-gym-red" weight="duotone" /> Aktivitas Terbaru
                </h2>
                <Link :href="route('admin.activity-log')" class="text-sm text-gray-400 hover:text-white transition-colors flex items-center gap-1">
                    Lihat Semua <PhArrowRight :size="16" />
                </Link>
            </div>

            <div v-if="recentBookings.length === 0" class="py-8 text-center text-gray-500">
                Belum ada aktivitas booking.
            </div>

            <div v-else class="space-y-3">
                <div v-for="booking in recentBookings" :key="booking.id" class="flex items-center justify-between bg-[#1a1a1a] rounded-xl px-4 py-3 hover:bg-white/5 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                            <PhUsers :size="16" class="text-blue-400" />
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">{{ booking.member?.name }}</p>
                            <p class="text-xs text-gray-500">→ {{ booking.trainer_profile?.user?.name }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400">{{ formatDate(booking.created_at) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
