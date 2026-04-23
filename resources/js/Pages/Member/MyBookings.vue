<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import gsap from 'gsap';
import { PhCalendarCheck, PhCheckCircle, PhXCircle, PhClock, PhUser, PhBarbell } from '@phosphor-icons/vue';
import MemberLayout from '@/Layouts/MemberLayout.vue';

defineOptions({ layout: MemberLayout });

const props = defineProps({
    bookings: { type: Array, default: () => [] }
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
};

const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
};

const getStatusConfig = (status) => {
    const map = {
        'waiting_verification': { label: 'Menunggu Konfirmasi PT', color: 'text-yellow-400 bg-yellow-400/10 border-yellow-400/30', icon: PhClock },
        'approved': { label: 'Disetujui', color: 'text-green-400 bg-green-400/10 border-green-400/30', icon: PhCheckCircle },
        'rejected': { label: 'Ditolak', color: 'text-red-400 bg-red-400/10 border-red-400/30', icon: PhXCircle },
        'completed': { label: 'Selesai', color: 'text-blue-400 bg-blue-400/10 border-blue-400/30', icon: PhCheckCircle },
        'pending_payment': { label: 'Menunggu Pembayaran', color: 'text-orange-400 bg-orange-400/10 border-orange-400/30', icon: PhClock },
        'canceled': { label: 'Dibatalkan', color: 'text-gray-400 bg-gray-400/10 border-gray-400/30', icon: PhXCircle },
    };
    return map[status] || { label: status, color: 'text-gray-400 bg-gray-400/10 border-gray-400/30', icon: PhClock };
};

onMounted(() => {
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    tl.from('.header-anim', { y: -20, opacity: 0, duration: 0.6 })
      .from('.booking-card', { y: 40, opacity: 0, duration: 0.6, stagger: 0.15 }, '-=0.3');
});
</script>

<template>
    <Head title="Jadwal Saya - One Point" />

    <div class="max-w-4xl mx-auto pb-20">
        <div class="header-anim mb-10">
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight mb-2">
                Jadwal <span class="text-transparent bg-clip-text bg-gradient-to-r from-gym-yellow to-gym-red">Saya.</span>
            </h1>
            <p class="text-gray-400 text-lg">Pantau jadwal dan status booking Personal Trainer Anda.</p>
        </div>

        <!-- Empty State -->
        <div v-if="bookings.length === 0" class="py-24 text-center border border-dashed border-white/10 rounded-[3rem] bg-[#121212]/30 flex flex-col items-center">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center border border-white/10 mb-6">
                <PhCalendarCheck :size="36" class="text-gray-500" />
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Belum Ada Jadwal</h3>
            <p class="text-gray-400 max-w-sm">Anda belum pernah melakukan booking sesi latihan. Cari Personal Trainer yang cocok untuk Anda!</p>
        </div>

        <!-- Booking Cards -->
        <div v-else class="space-y-4">
            <div 
                v-for="(booking, index) in bookings" 
                :key="booking.id"
                class="booking-card bg-[#121212]/80 backdrop-blur-xl border border-white/5 rounded-[2rem] p-6 hover:bg-[#1a1a1a]/90 transition-colors"
            >
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500/20 to-blue-900/20 flex items-center justify-center border border-blue-500/20">
                                <PhBarbell :size="20" class="text-blue-400" weight="fill" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">{{ booking.trainer_profile?.user?.name || 'Personal Trainer' }}</h3>
                                <p class="text-xs text-gray-500">Dipesan {{ formatDate(booking.created_at) }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-4">
                            <div class="bg-[#1a1a1a] rounded-xl px-3 py-2">
                                <span class="block text-[10px] text-gray-500 uppercase tracking-wider">Tanggal Sesi</span>
                                <span class="text-sm font-bold text-white">{{ formatDate(booking.booking_date) }}</span>
                            </div>
                            <div class="bg-[#1a1a1a] rounded-xl px-3 py-2">
                                <span class="block text-[10px] text-gray-500 uppercase tracking-wider">Shift</span>
                                <span class="text-sm font-bold text-orange-400">{{ booking.shift }}</span>
                            </div>
                            <div class="bg-[#1a1a1a] rounded-xl px-3 py-2">
                                <span class="block text-[10px] text-gray-500 uppercase tracking-wider">Biaya</span>
                                <span class="text-sm font-bold text-emerald-400">{{ formatRupiah(booking.amount) }}</span>
                            </div>
                        </div>

                        <!-- Pesan (Jika ada) -->
                        <div v-if="booking.message" class="mt-4 bg-[#1a1a1a] p-4 rounded-xl border border-white/5">
                            <span class="block text-xs text-gray-500 mb-1">Pesan untuk PT:</span>
                            <p class="text-sm text-gray-300 italic">"{{ booking.message }}"</p>
                        </div>
                    </div>

                    <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-3">
                        <span :class="['inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border', getStatusConfig(booking.status).color]">
                            <component :is="getStatusConfig(booking.status).icon" weight="bold" :size="14" />
                            {{ getStatusConfig(booking.status).label }}
                        </span>
                        
                        <div v-if="booking.status === 'waiting_verification'" class="mt-2">
                            <Link :href="route('booking.cancel', booking.id)" method="patch" as="button" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs font-bold rounded-lg border border-red-500/20 transition-colors">
                                Batalkan
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
