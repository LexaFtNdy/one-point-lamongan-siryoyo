<script setup>
import { Link } from '@inertiajs/vue3';
import { PhSquaresFour, PhUsers, PhCalendarCheck, PhSignOut, PhBarbell } from '@phosphor-icons/vue';
import { Toaster } from 'vue-sonner';
</script>

<template>
    <div class="min-h-screen relative bg-deep-onyx text-slate-50 font-sans">
        <Toaster position="top-right" theme="dark" richColors />

        <!-- Subtler Ambient Glow for Admin (More serious tone) -->
        <div class="fixed top-[-20%] right-[-10%] w-[800px] h-[800px] bg-gym-red/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div class="fixed bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-gym-yellow/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div class="fixed inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxMDAlJyBoZWlnaHQ9JzEwMCUnPjxmaWx0ZXIgaWQ9J24nPjxmZVR1cmJ1bGVuY2UgdHlwZT0nZnJhY3RhbE5vaXNlJyBiYXNlRnJlcXVlbmN5PScxLjUnIG51bU9jdGF2ZXM9JzMnIHN0aXRjaFRpbGVzPSdzdGl0Y2gnLz48L2ZpbHRlcj48cmVjdCB3aWR0aD0nMTAwJScgaGVpZ2h0PScxMDAlJyBmaWx0ZXI9J3VybCgjbiknIG9wYWNpdHk9JzAuMDUnLz48L3N2Zz4=')] opacity-20 pointer-events-none mix-blend-overlay z-0"></div>

        <!-- Floating Island Navigation (Admin Command Center) -->
        <nav class="fixed top-6 inset-x-0 mx-auto w-[95%] sm:w-[90%] max-w-6xl z-50 bg-soft-obsidian/70 backdrop-blur-2xl border border-white/5 rounded-3xl px-4 sm:px-8 py-3 sm:py-4 flex justify-between items-center shadow-2xl">
            <div class="flex items-center gap-3">
                <div class="p-1.5 bg-white rounded-xl">
                    <img src="/images/logo.png" alt="One Point Logo" class="w-8 h-8 object-contain" />
                </div>
                <div class="hidden sm:block">
                    <h1 class="font-heading font-bold text-lg leading-none text-white">ONE<span class="text-gym-red">POINT</span></h1>
                    <p class="text-[10px] text-slate-400 font-medium tracking-widest uppercase mt-1">Command Center</p>
                </div>
            </div>

            <!-- Nav Links -->
            <div class="flex items-center gap-4 sm:gap-8 overflow-x-auto no-scrollbar">
                <Link :href="route('dashboard')" class="flex items-center gap-2 text-sm font-medium text-white transition-colors">
                    <PhSquaresFour :size="20" class="text-gym-yellow" /> <span class="hidden sm:inline">Dashboard</span>
                </Link>
                <Link href="#" class="flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                    <PhUsers :size="20" /> <span class="hidden sm:inline">Manage Users</span>
                </Link>
                <Link href="#" class="flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                    <PhCalendarCheck :size="20" /> <span class="hidden sm:inline">Transactions</span>
                </Link>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium text-white">{{ $page.props.auth.user.name }}</p>
                    <p class="text-xs text-gym-yellow">Administrator</p>
                </div>
                <div class="hidden sm:block h-8 w-px bg-white/10 mx-2"></div>
                <Link :href="route('logout')" method="post" as="button" class="p-2 hover:bg-gym-red/20 text-slate-400 hover:text-gym-red rounded-full transition-colors" title="Logout">
                    <PhSignOut :size="20" />
                </Link>
            </div>
        </nav>

        <!-- Main Content (Bento Grid Container Ready) -->
        <main class="relative z-10 pt-32 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto min-h-screen pb-12">
            <slot />
        </main>
    </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
