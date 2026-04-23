<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { PhClockCounterClockwise, PhCheckCircle, PhXCircle, PhClock, PhUser } from '@phosphor-icons/vue';
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
        'waiting_verification': { label: 'Menunggu Konfirmasi', color: 'text-yellow-400 bg-yellow-400/10 border-yellow-400/30', icon: PhClock },
        'approved': { label: 'Disetujui', color: 'text-green-400 bg-green-400/10 border-green-400/30', icon: PhCheckCircle },
        'rejected': { label: 'Ditolak', color: 'text-red-400 bg-red-400/10 border-red-400/30', icon: PhXCircle },
        'completed': { label: 'Selesai', color: 'text-blue-400 bg-blue-400/10 border-blue-400/30', icon: PhCheckCircle },
        'canceled': { label: 'Dibatalkan Member', color: 'text-gray-400 bg-gray-400/10 border-gray-400/30', icon: PhXCircle },
    };
    return map[status] || { label: status, color: 'text-gray-400 bg-gray-400/10 border-gray-400/30', icon: PhClock };
};
</script>

<template>
    <Head title="Riwayat Sesi - Trainer" />

    <div class="max-w-4xl mx-auto pb-20">
        <div class="mb-10">
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight mb-2 flex items-center gap-3">
                <PhClockCounterClockwise class="text-blue-400" />
                Riwayat <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">Sesi.</span>
            </h1>
            <p class="text-gray-400">Seluruh riwayat pemesanan yang pernah masuk dari member.</p>
        </div>

        <!-- Empty State -->
        <div v-if="bookings.length === 0" class="py-24 text-center border border-dashed border-white/10 rounded-[3rem] bg-[#121212]/30 flex flex-col items-center">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center border border-white/10 mb-6">
                <PhClockCounterClockwise :size="36" class="text-gray-500" />
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Belum Ada Riwayat</h3>
            <p class="text-gray-400 max-w-sm">Belum ada member yang melakukan booking sesi latihan dengan Anda.</p>
        </div>

        <!-- Session Cards -->
        <div v-else class="space-y-4">
            <div v-for="booking in bookings" :key="booking.id" class="bg-[#121212]/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 hover:border-white/20 transition-all duration-300">
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500/20 to-blue-900/20 flex items-center justify-center border border-blue-500/20">
                                <PhUser :size="20" class="text-blue-400" weight="fill" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">{{ booking.member?.name || 'Member' }}</h3>
                                <p class="text-xs text-gray-500">Request masuk {{ formatDate(booking.created_at) }}</p>
                            </div>
                        </div>

                        <!-- Member Profile Quick View -->
                        <div v-if="booking.member?.member_profile" class="bg-[#1a1a1a] p-3 rounded-xl border border-white/5 grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3">
                            <div>
                                <span class="block text-[10px] text-gray-500 uppercase">Postur</span>
                                <span class="text-xs font-bold text-white">{{ booking.member.member_profile.height }}cm / {{ booking.member.member_profile.weight }}kg</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-gray-500 uppercase">Cedera</span>
                                <span class="text-xs font-bold" :class="booking.member.member_profile.injury_history === 'None' || !booking.member.member_profile.injury_history ? 'text-green-400' : 'text-red-400'">{{ booking.member.member_profile.injury_history || 'Tidak ada' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-[#1a1a1a] rounded-xl px-3 py-2">
                                <span class="block text-[10px] text-gray-500 uppercase tracking-wider">Tanggal</span>
                                <span class="text-sm font-bold text-white">{{ formatDate(booking.booking_date) }}</span>
                            </div>
                            <div class="bg-[#1a1a1a] rounded-xl px-3 py-2">
                                <span class="block text-[10px] text-gray-500 uppercase tracking-wider">Shift</span>
                                <span class="text-sm font-bold text-orange-400">{{ booking.shift }}</span>
                            </div>
                            <div class="bg-[#1a1a1a] rounded-xl px-3 py-2">
                                <span class="block text-[10px] text-gray-500 uppercase tracking-wider">Tarif</span>
                                <span class="text-sm font-bold text-emerald-400">{{ formatRupiah(booking.amount) }}</span>
                            </div>
                        </div>

                        <!-- Pesan (Jika ada) -->
                        <div v-if="booking.message" class="mt-4 bg-[#1a1a1a] p-3 rounded-xl border border-white/5 border-l-4 border-l-blue-500">
                            <span class="block text-xs text-blue-400 font-bold mb-1 uppercase tracking-wider">Catatan dari Member</span>
                            <p class="text-sm text-gray-300 italic">"{{ booking.message }}"</p>
                        </div>
                    </div>

                    <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center">
                        <span :class="['inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border', getStatusConfig(booking.status).color]">
                            <component :is="getStatusConfig(booking.status).icon" weight="bold" :size="14" />
                            {{ getStatusConfig(booking.status).label }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
