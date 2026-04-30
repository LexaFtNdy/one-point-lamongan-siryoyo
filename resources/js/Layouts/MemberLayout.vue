<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { PhHouse, PhMagnifyingGlass, PhCalendarCheck, PhUser, PhSignOut, PhUsers, PhClipboardText, PhClockCounterClockwise, PhChartBar, PhMedal } from '@phosphor-icons/vue';
import { Toaster, toast } from 'vue-sonner';
import { computed, watch } from 'vue';

const page = usePage();
const currentComponent = computed(() => page.component);

const isActive = (componentName) => currentComponent.value === componentName;
const isActiveAny = (...names) => names.some(n => currentComponent.value === n);

// Global Toast Watcher
watch(() => page.props.flash, (flash) => {
    if (flash?.success) toast.success(flash.success);
    if (flash?.error) toast.error(flash.error);
}, { immediate: true, deep: true });
</script>

<template>
    <div class="min-h-screen relative bg-[#09090b] text-slate-50 font-sans pb-24 sm:pb-0 overflow-x-hidden selection:bg-gym-red selection:text-white">
        <!-- Vue Sonner Toaster -->
        <Toaster position="top-center" theme="dark" richColors />

        <!-- High-End Ambient Glowing Background -->
        <div class="fixed top-[-20%] left-[-10%] w-[600px] h-[600px] bg-gym-yellow/10 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>
        <div class="fixed bottom-[-10%] right-[-10%] w-[800px] h-[800px] bg-gym-red/10 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>

        <!-- Premium Subtle Noise Overlay -->
        <div class="fixed inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxMDAlJyBoZWlnaHQ9JzEwMCUnPjxmaWx0ZXIgaWQ9J24nPjxmZVR1cmJ1bGVuY2UgdHlwZT0nZnJhY3RhbE5vaXNlJyBiYXNlRnJlcXVlbmN5PScxLjUnIG51bU9jdGF2ZXM9JzMnIHN0aXRjaFRpbGVzPSdzdGl0Y2gnLz48L2ZpbHRlcj48cmVjdCB3aWR0aD0nMTAwJScgaGVpZ2h0PScxMDAlJyBmaWx0ZXI9J3VybCgjbiknIG9wYWNpdHk9JzAuMDM5Jy8+PC9zdmc+')] opacity-[0.15] pointer-events-none z-0"></div>

        <!-- Desktop Floating Nav -->
        <nav class="hidden sm:flex fixed top-6 inset-x-0 mx-auto w-max z-50 bg-[#121212]/70 backdrop-blur-2xl border border-white/5 rounded-full px-2 py-2 items-center gap-2 shadow-[0_8px_32px_rgba(0,0,0,0.5)]">
            <Link :href="route('dashboard')" class="flex items-center gap-3 px-6 py-2">
                <div class="p-1.5 bg-white rounded-full shadow-[0_0_15px_rgba(255,255,255,0.2)]">
                    <img src="/images/logo.png" alt="One Point" class="w-5 h-5 object-contain" />
                </div>
                <span class="font-heading font-bold tracking-widest text-white text-sm">ONE<span class="text-gym-red">POINT</span></span>
            </Link>

            <div class="h-8 w-px bg-white/10 mx-2"></div>

            <div class="flex items-center gap-1">
                <Link
                    :href="route('dashboard')"
                    :class="['flex items-center gap-2 px-5 py-2 rounded-full text-sm font-medium transition-all duration-300', isActiveAny('Dashboard', 'Trainer/Dashboard', 'Admin/Dashboard') ? 'bg-white/10 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5']"
                >
                    <PhHouse :size="18" :weight="isActiveAny('Dashboard', 'Trainer/Dashboard', 'Admin/Dashboard') ? 'fill' : 'regular'" /> Home
                </Link>

                <!-- MEMBER NAVIGATION -->
                <template v-if="$page.props.auth.user.role === 'member'">
                    <Link
                        :href="route('member.find-pt')"
                        :class="['flex items-center gap-2 px-5 py-2 rounded-full text-sm font-medium transition-all duration-300', isActiveAny('Member/FindTrainer', 'Member/Catalog', 'Member/TrainerProfile') ? 'bg-white/10 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5']"
                    >
                        <PhMagnifyingGlass :size="18" :weight="isActiveAny('Member/FindTrainer', 'Member/Catalog', 'Member/TrainerProfile') ? 'bold' : 'regular'" /> Rekomendasi PT
                    </Link>
                    <Link
                        :href="route('member.bookings')"
                        :class="['flex items-center gap-2 px-5 py-2 rounded-full text-sm font-medium transition-all duration-300', isActive('Member/MyBookings') ? 'bg-white/10 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5']"
                    >
                        <PhCalendarCheck :size="18" :weight="isActive('Member/MyBookings') ? 'fill' : 'regular'" /> Jadwal Saya
                    </Link>
                </template>

                <!-- TRAINER NAVIGATION -->
                <template v-if="$page.props.auth.user.role === 'trainer'">
                    <Link
                        :href="route('trainer.history')"
                        :class="['flex items-center gap-2 px-5 py-2 rounded-full text-sm font-medium transition-all duration-300', isActive('Trainer/History') ? 'bg-white/10 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5']"
                    >
                        <PhClockCounterClockwise :size="18" :weight="isActive('Trainer/History') ? 'fill' : 'regular'" /> Riwayat Sesi
                    </Link>
                </template>

                <!-- ADMIN NAVIGATION -->
                <template v-if="$page.props.auth.user.role === 'admin'">
                    <Link
                        :href="route('admin.users')"
                        :class="['flex items-center gap-2 px-5 py-2 rounded-full text-sm font-medium transition-all duration-300', isActive('Admin/Users') ? 'bg-white/10 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5']"
                    >
                        <PhUsers :size="18" :weight="isActive('Admin/Users') ? 'fill' : 'regular'" /> Kelola Pengguna
                    </Link>
                    <Link
                        :href="route('admin.achievements')"
                        :class="['flex items-center gap-2 px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 relative', isActive('Admin/Achievements') ? 'bg-white/10 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5']"
                    >
                        <PhMedal :size="18" :weight="isActive('Admin/Achievements') ? 'fill' : 'regular'" /> Verifikasi Kredensial
                        <span v-if="$page.props.pending_achievements_count > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border-2 border-[#121212]">
                            {{ $page.props.pending_achievements_count }}
                        </span>
                    </Link>
                    <Link
                        :href="route('admin.activity-log')"
                        :class="['flex items-center gap-2 px-5 py-2 rounded-full text-sm font-medium transition-all duration-300', isActive('Admin/ActivityLog') ? 'bg-white/10 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5']"
                    >
                        <PhClipboardText :size="18" :weight="isActive('Admin/ActivityLog') ? 'fill' : 'regular'" /> Log Aktivitas
                    </Link>
                </template>
            </div>

            <div class="h-8 w-px bg-white/10 mx-2"></div>

            <div class="flex items-center gap-1 pr-2">
                <Link :href="route('profile.edit')" class="flex items-center gap-2 px-5 py-2 rounded-full text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-300">
                    <PhUser :size="18" /> Profile
                </Link>
                <Link :href="route('logout')" method="post" as="button" class="flex items-center justify-center w-10 h-10 rounded-full text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all duration-300">
                    <PhSignOut :size="18" />
                </Link>
            </div>
        </nav>

        <!-- Mobile Top Bar -->
        <div class="sm:hidden fixed top-0 inset-x-0 z-40 bg-[#09090b]/80 backdrop-blur-xl border-b border-white/5 px-6 py-4 flex justify-between items-center">
            <Link :href="route('dashboard')" class="flex items-center gap-2">
                <div class="p-1 bg-white rounded-full">
                    <img src="/images/logo.png" alt="One Point" class="w-5 h-5 object-contain" />
                </div>
                <span class="font-heading font-bold text-sm tracking-widest text-white">ONE<span class="text-gym-red">POINT</span></span>
            </Link>
            <Link :href="route('logout')" method="post" as="button" class="text-red-400 hover:text-red-300 p-2 bg-red-500/10 rounded-full">
                <PhSignOut :size="20" />
            </Link>
        </div>

        <!-- Main Content -->
        <main class="relative z-10 pt-24 sm:pt-32 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto min-h-screen">
            <slot />
        </main>

        <!-- Mobile Bottom Navigation -->
        <nav class="sm:hidden fixed bottom-6 inset-x-6 z-50 bg-[#121212]/80 backdrop-blur-2xl border border-white/10 rounded-3xl px-4 py-3 flex justify-between items-center shadow-[0_10px_40px_rgba(0,0,0,0.8)] pb-safe-offset">
            <Link :href="route('dashboard')" class="relative flex flex-col items-center gap-1 px-4 py-1">
                <div v-if="isActiveAny('Dashboard', 'Trainer/Dashboard', 'Admin/Dashboard')" class="absolute -top-3 w-8 h-1 bg-gym-yellow rounded-full shadow-[0_0_10px_rgba(255,193,7,0.8)]"></div>
                <PhHouse :size="24" :weight="isActiveAny('Dashboard', 'Trainer/Dashboard', 'Admin/Dashboard') ? 'fill' : 'regular'" :class="isActiveAny('Dashboard', 'Trainer/Dashboard', 'Admin/Dashboard') ? 'text-gym-yellow' : 'text-gray-400'" />
                <span :class="['text-[10px] font-medium transition-colors', isActiveAny('Dashboard', 'Trainer/Dashboard', 'Admin/Dashboard') ? 'text-gym-yellow' : 'text-gray-500']">Home</span>
            </Link>

            <!-- MEMBER MOBILE NAV -->
            <template v-if="$page.props.auth.user.role === 'member'">
                <Link :href="route('member.find-pt')" class="relative flex flex-col items-center gap-1 px-4 py-1">
                    <div v-if="isActiveAny('Member/FindTrainer', 'Member/Catalog', 'Member/TrainerProfile')" class="absolute -top-3 w-8 h-1 bg-gym-yellow rounded-full shadow-[0_0_10px_rgba(255,193,7,0.8)]"></div>
                    <PhMagnifyingGlass :size="24" :weight="isActiveAny('Member/FindTrainer', 'Member/Catalog', 'Member/TrainerProfile') ? 'bold' : 'regular'" :class="isActiveAny('Member/FindTrainer', 'Member/Catalog', 'Member/TrainerProfile') ? 'text-gym-yellow' : 'text-gray-400'" />
                    <span :class="['text-[10px] font-medium transition-colors', isActiveAny('Member/FindTrainer', 'Member/Catalog', 'Member/TrainerProfile') ? 'text-gym-yellow' : 'text-gray-500']">PT</span>
                </Link>
                <Link :href="route('member.bookings')" class="relative flex flex-col items-center gap-1 px-4 py-1">
                    <div v-if="isActive('Member/MyBookings')" class="absolute -top-3 w-8 h-1 bg-gym-yellow rounded-full shadow-[0_0_10px_rgba(255,193,7,0.8)]"></div>
                    <PhCalendarCheck :size="24" :weight="isActive('Member/MyBookings') ? 'fill' : 'regular'" :class="isActive('Member/MyBookings') ? 'text-gym-yellow' : 'text-gray-400'" />
                    <span :class="['text-[10px] font-medium transition-colors', isActive('Member/MyBookings') ? 'text-gym-yellow' : 'text-gray-500']">Jadwal</span>
                </Link>
            </template>

            <!-- TRAINER MOBILE NAV -->
            <template v-if="$page.props.auth.user.role === 'trainer'">
                <Link :href="route('trainer.history')" class="relative flex flex-col items-center gap-1 px-4 py-1">
                    <div v-if="isActive('Trainer/History')" class="absolute -top-3 w-8 h-1 bg-gym-yellow rounded-full shadow-[0_0_10px_rgba(255,193,7,0.8)]"></div>
                    <PhClockCounterClockwise :size="24" :weight="isActive('Trainer/History') ? 'fill' : 'regular'" :class="isActive('Trainer/History') ? 'text-gym-yellow' : 'text-gray-400'" />
                    <span :class="['text-[10px] font-medium transition-colors', isActive('Trainer/History') ? 'text-gym-yellow' : 'text-gray-500']">Riwayat</span>
                </Link>
            </template>

            <!-- ADMIN MOBILE NAV -->
            <template v-if="$page.props.auth.user.role === 'admin'">
                <Link :href="route('admin.users')" class="relative flex flex-col items-center gap-1 px-4 py-1">
                    <div v-if="isActive('Admin/Users')" class="absolute -top-3 w-8 h-1 bg-gym-yellow rounded-full shadow-[0_0_10px_rgba(255,193,7,0.8)]"></div>
                    <PhUsers :size="24" :weight="isActive('Admin/Users') ? 'fill' : 'regular'" :class="isActive('Admin/Users') ? 'text-gym-yellow' : 'text-gray-400'" />
                    <span :class="['text-[10px] font-medium transition-colors', isActive('Admin/Users') ? 'text-gym-yellow' : 'text-gray-500']">Users</span>
                </Link>
                <Link :href="route('admin.achievements')" class="relative flex flex-col items-center gap-1 px-4 py-1">
                    <div v-if="isActive('Admin/Achievements')" class="absolute -top-3 w-8 h-1 bg-gym-yellow rounded-full shadow-[0_0_10px_rgba(255,193,7,0.8)]"></div>
                    <div class="relative">
                        <PhMedal :size="24" :weight="isActive('Admin/Achievements') ? 'fill' : 'regular'" :class="isActive('Admin/Achievements') ? 'text-gym-yellow' : 'text-gray-400'" />
                        <span v-if="$page.props.pending_achievements_count > 0" class="absolute -top-1 -right-2 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border-2 border-[#121212]">
                            {{ $page.props.pending_achievements_count }}
                        </span>
                    </div>
                    <span :class="['text-[10px] font-medium transition-colors', isActive('Admin/Achievements') ? 'text-gym-yellow' : 'text-gray-500']">Verify</span>
                </Link>
                <Link :href="route('admin.activity-log')" class="relative flex flex-col items-center gap-1 px-4 py-1">
                    <div v-if="isActive('Admin/ActivityLog')" class="absolute -top-3 w-8 h-1 bg-gym-yellow rounded-full shadow-[0_0_10px_rgba(255,193,7,0.8)]"></div>
                    <PhClipboardText :size="24" :weight="isActive('Admin/ActivityLog') ? 'fill' : 'regular'" :class="isActive('Admin/ActivityLog') ? 'text-gym-yellow' : 'text-gray-400'" />
                    <span :class="['text-[10px] font-medium transition-colors', isActive('Admin/ActivityLog') ? 'text-gym-yellow' : 'text-gray-500']">Log</span>
                </Link>
            </template>

            <Link :href="route('profile.edit')" class="relative flex flex-col items-center gap-1 px-4 py-1">
                <div v-if="isActive('Profile/Edit')" class="absolute -top-3 w-8 h-1 bg-gym-yellow rounded-full shadow-[0_0_10px_rgba(255,193,7,0.8)]"></div>
                <PhUser :size="24" :weight="isActive('Profile/Edit') ? 'fill' : 'regular'" :class="isActive('Profile/Edit') ? 'text-gym-yellow' : 'text-gray-400'" />
                <span :class="['text-[10px] font-medium transition-colors', isActive('Profile/Edit') ? 'text-gym-yellow' : 'text-gray-500']">Profile</span>
            </Link>
        </nav>
    </div>
</template>

<style>
@supports (padding-bottom: env(safe-area-inset-bottom)) {
  .pb-safe-offset {
    margin-bottom: env(safe-area-inset-bottom);
  }
}
</style>
