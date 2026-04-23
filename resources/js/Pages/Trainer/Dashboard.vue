<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { PhCalendarCheck } from '@phosphor-icons/vue';
import MemberLayout from '@/Layouts/MemberLayout.vue';

defineOptions({ layout: MemberLayout });

const props = defineProps({
    auth: Object,
    bookings: Array,
    hasProfile: Boolean
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
};
</script>

<template>
    <Head title="Trainer Dashboard" />

    <div class="space-y-8 pb-10">
        <!-- Alert jika belum isi profil -->
        <div v-if="!hasProfile" class="bg-blue-500/10 border border-blue-500/50 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-[0_0_20px_rgba(59,130,246,0.15)]">
            <div>
                <h3 class="text-blue-400 font-bold text-lg">Parameter Rekomendasi Belum Diatur!</h3>
                <p class="text-gray-300 text-sm">Sistem rekomendasi belum bisa mencocokkan Anda dengan member. Segera atur parameter Anda.</p>
            </div>
            <Link :href="route('trainer.setup')" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-full whitespace-nowrap transition-colors">
                Atur Parameter Sekarang
            </Link>
        </div>

        <!-- Premium Welcome Banner untuk Trainer -->
        <div class="relative overflow-hidden rounded-[2.5rem] bg-[#121212]/80 backdrop-blur-xl border border-white/10 p-8 sm:p-12 shadow-[0_20px_60px_rgba(0,0,0,0.5)] group mb-8">
            <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 bg-blue-600/20 rounded-full blur-[100px] pointer-events-none group-hover:bg-blue-500/30 transition-colors duration-700"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-4 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-xs font-medium text-gray-300">Trainer Dashboard Aktif</span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-bold text-white tracking-tight mb-4 leading-tight">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">{{ auth.user.name }}</span>
                    </h1>
                    <p class="text-gray-400 text-lg max-w-xl">Selamat datang di pusat komando Anda. Pantau jadwal, atur parameter rekomendasi, dan pandu member mencapai tujuannya.</p>
                </div>
                
                <div class="flex-shrink-0" v-if="hasProfile">
                    <Link :href="route('trainer.setup')" class="px-8 py-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold rounded-full transition-all flex items-center gap-2">
                        Edit Parameter
                    </Link>
                </div>
            </div>
        </div>

        <!-- Section Title -->
        <div class="pt-2 pb-4 flex items-center justify-between border-b border-white/10 mb-6">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <PhCalendarCheck :size="28" class="text-blue-400" weight="duotone" /> Jadwal & Pemesanan Latihan
            </h2>
        </div>
        
        <div v-if="!bookings || bookings.length === 0" class="text-center py-12 bg-[#121212]/50 border border-white/5 rounded-3xl">
            <p class="text-gray-500">Belum ada pemesanan jadwal yang masuk.</p>
        </div>

        <div v-else class="space-y-4">
            <div v-for="booking in bookings" :key="booking.id" class="bg-[#121212]/80 border border-white/10 rounded-3xl p-6 hover:border-white/20 transition-colors">
                <div class="flex flex-col md:flex-row justify-between gap-6">
                    <!-- Info Member -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="text-2xl font-bold text-white">{{ booking.member.name }}</h4>
                            <span v-if="booking.status === 'waiting_verification'" class="px-3 py-1 bg-yellow-500/20 text-yellow-500 text-xs font-bold rounded-full border border-yellow-500/30">MENUNGGU KONFIRMASI</span>
                            <span v-if="booking.status === 'approved'" class="px-3 py-1 bg-green-500/20 text-green-500 text-xs font-bold rounded-full border border-green-500/30">DISETUJUI</span>
                        </div>
                        <p class="text-sm text-gray-400 mb-4">Jadwal Latihan: <strong class="text-white">{{ formatDate(booking.booking_date) }}</strong> (Shift: <strong class="text-orange-400">{{ booking.shift }}</strong>)</p>
                        
                        <!-- Rekam Medis (Jika ada) -->
                        <div v-if="booking.member.member_profile" class="bg-[#1a1a1a] p-4 rounded-xl border border-white/5 grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div>
                                <span class="block text-xs text-gray-500">Tujuan</span>
                                <span class="font-bold text-sm text-gym-red">{{ booking.member.member_profile.goal }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500">Riwayat Cedera</span>
                                <span class="font-bold text-sm" :class="booking.member.member_profile.injury_history === 'None' ? 'text-green-400' : 'text-red-500'">{{ booking.member.member_profile.injury_history }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500">Postur Tubuh</span>
                                <span class="font-bold text-sm text-white">{{ booking.member.member_profile.height }}cm / {{ booking.member.member_profile.weight }}kg</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500">Level Pengalaman</span>
                                <span class="font-bold text-sm text-blue-400">{{ booking.member.member_profile.experience_level }}</span>
                            </div>
                        </div>
                        <div v-else class="text-xs text-gray-500 italic">Data profil latihan tidak tersedia.</div>

                        <!-- Pesan dari Member -->
                        <div v-if="booking.message" class="mt-4 bg-[#1a1a1a] p-4 rounded-xl border border-white/5 border-l-4 border-l-blue-500">
                            <span class="block text-xs text-blue-400 font-bold mb-1 uppercase tracking-wider">Catatan dari Member</span>
                            <p class="text-sm text-gray-300 italic">"{{ booking.message }}"</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div v-if="booking.status === 'waiting_verification'" class="flex flex-col sm:flex-row md:flex-col gap-3 min-w-[140px] justify-center">
                        <Link :href="route('booking.update-status', booking.id)" method="patch" :data="{status: 'approved'}" as="button" class="px-4 py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl text-center w-full transition-colors">
                            Terima Jadwal
                        </Link>
                        <Link :href="route('booking.update-status', booking.id)" method="patch" :data="{status: 'rejected'}" as="button" class="px-4 py-3 bg-[#1a1a1a] hover:bg-red-500/20 hover:text-red-400 border border-white/10 text-gray-400 font-bold rounded-xl text-center w-full transition-colors">
                            Tolak
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
