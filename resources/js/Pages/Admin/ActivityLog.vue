<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import { Head } from '@inertiajs/vue3';
import { PhClipboardText, PhCheckCircle, PhXCircle, PhClock, PhUser, PhBarbell } from '@phosphor-icons/vue';

defineProps({
    activities: {
        type: Array,
        default: () => []
    }
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const getStatusColor = (status) => {
    switch(status) {
        case 'waiting_verification': return 'text-gym-yellow bg-gym-yellow/10 border-gym-yellow/30';
        case 'approved': return 'text-green-400 bg-green-400/10 border-green-400/30';
        case 'rejected': return 'text-red-400 bg-red-400/10 border-red-400/30';
        case 'pending_payment': return 'text-orange-400 bg-orange-400/10 border-orange-400/30';
        case 'completed': return 'text-blue-400 bg-blue-400/10 border-blue-400/30';
        case 'canceled': return 'text-gray-400 bg-gray-400/10 border-gray-400/30';
        default: return 'text-gray-400 bg-gray-400/10 border-gray-400/30';
    }
};

const getStatusLabel = (status) => {
    switch(status) {
        case 'waiting_verification': return 'Menunggu Konfirmasi';
        case 'approved': return 'Disetujui PT';
        case 'rejected': return 'Ditolak PT';
        case 'pending_payment': return 'Menunggu Pembayaran';
        case 'completed': return 'Selesai';
        case 'canceled': return 'Dibatalkan';
        default: return status;
    }
};

const getStatusIcon = (status) => {
    switch(status) {
        case 'waiting_verification': return PhClock;
        case 'approved': return PhCheckCircle;
        case 'rejected': return PhXCircle;
        case 'completed': return PhCheckCircle;
        case 'canceled': return PhXCircle;
        default: return PhClock;
    }
};
</script>

<template>
    <Head title="Log Aktivitas - Admin" />

    <MemberLayout>
        <div class="max-w-6xl mx-auto pb-20">
            <!-- Header -->
            <div class="mb-10">
                <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight mb-2 flex items-center gap-3">
                    <PhClipboardText class="text-gym-red" />
                    Log <span class="text-transparent bg-clip-text bg-gradient-to-r from-gray-400 to-gray-100">Aktivitas.</span>
                </h1>
                <p class="text-gray-400">Track record interaksi dan pemesanan antara Member dan Personal Trainer.</p>
            </div>

            <!-- Activity Table -->
            <div class="bg-[#121212]/80 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal Request</th>
                                <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Member</th>
                                <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Personal Trainer</th>
                                <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Jadwal Sesi</th>
                                <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr v-if="activities.length === 0">
                                <td colspan="5" class="py-12 text-center text-gray-500">
                                    Belum ada log aktivitas.
                                </td>
                            </tr>
                            <tr v-for="activity in activities" :key="activity.id" class="hover:bg-white/5 transition-colors">
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-300">{{ formatDate(activity.created_at) }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400">
                                            <PhUser weight="fill" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-white">{{ activity.member?.name || 'Unknown' }}</p>
                                            <p v-if="activity.message" class="text-xs text-gray-500 italic mt-0.5 truncate max-w-[150px]">"{{ activity.message }}"</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-orange-500/20 flex items-center justify-center text-orange-400">
                                            <PhBarbell weight="fill" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-white">{{ activity.trainer_profile?.user?.name || 'Unknown PT' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-200">{{ new Date(activity.booking_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) }}</p>
                                    <p class="text-xs text-gray-500">Shift: {{ activity.shift }}</p>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span :class="['inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border', getStatusColor(activity.status)]">
                                        <component :is="getStatusIcon(activity.status)" weight="bold" />
                                        {{ getStatusLabel(activity.status) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </MemberLayout>
</template>
