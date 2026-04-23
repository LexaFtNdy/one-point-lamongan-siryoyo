<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen relative flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-deep-onyx">
        <!-- Ambient Glow for Guest (Red & Yellow) -->
        <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] bg-gym-red/20 rounded-full blur-[120px] pointer-events-none z-0"></div>
        <div class="fixed bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-gym-yellow/15 rounded-full blur-[150px] pointer-events-none z-0"></div>
        
        <!-- Noise Overlay -->
        <div class="fixed inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxMDAlJyBoZWlnaHQ9JzEwMCUnPjxmaWx0ZXIgaWQ9J24nPjxmZVR1cmJ1bGVuY2UgdHlwZT0nZnJhY3RhbE5vaXNlJyBiYXNlRnJlcXVlbmN5PScxLjUnIG51bU9jdGF2ZXM9JzMnIHN0aXRjaFRpbGVzPSdzdGl0Y2gnLz48L2ZpbHRlcj48cmVjdCB3aWR0aD0nMTAwJScgaGVpZ2h0PScxMDAlJyBmaWx0ZXI9J3VybCgjbiknIG9wYWNpdHk9JzAuMDUnLz48L3N2Zz4=')] opacity-20 pointer-events-none mix-blend-overlay z-0"></div>

        <div class="relative z-10">
            <Link href="/" class="flex flex-col items-center gap-3">
                <div class="p-2 bg-white rounded-2xl shadow-[0_0_30px_rgba(255,193,7,0.3)]">
                    <img src="/images/logo.png" alt="One Point Logo" class="w-16 h-16 object-contain" />
                </div>
                <h1 class="font-heading font-bold text-3xl text-white tracking-widest mt-2">ONE<span class="text-gym-red">POINT</span></h1>
            </Link>
        </div>

        <div class="relative z-10 w-full sm:max-w-md mt-8 px-6 py-8 bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl overflow-hidden sm:rounded-3xl">
            <slot />
        </div>
    </div>
</template>
