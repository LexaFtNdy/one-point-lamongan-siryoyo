<script setup>
import { onMounted } from 'vue';
import gsap from 'gsap';
import { Toaster } from 'vue-sonner';

onMounted(() => {
    // Only animate the left side ONCE during the initial app load
    const tl = gsap.timeline();
    tl.from('.gsap-left-logo', { opacity: 0, y: -20, duration: 0.8, ease: 'power3.out' })
      .from('.gsap-left-text', { opacity: 0, y: 20, duration: 0.8, stagger: 0.1, ease: 'power3.out' }, '-=0.4');
});
</script>

<template>
    <Toaster position="top-right" theme="dark" richColors />

    <div class="min-h-screen bg-[#0A0A0A] text-white flex overflow-hidden font-sans">
        
        <!-- Left Side (Visual Image) - Persistent across auth pages -->
        <div class="hidden lg:flex lg:w-5/12 relative flex-col justify-between p-12 lg:p-16 border-r border-white/5">
            <!-- Sleek Image Cover -->
            <div class="absolute inset-0 bg-[url('/images/gym-bg.jpg')] bg-cover bg-center z-0"></div>
            <!-- Smooth Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0A] via-[#0A0A0A]/80 to-[#0A0A0A]/30 z-0"></div>
            <div class="absolute inset-0 bg-black/20 z-0"></div>

            <div class="relative z-10 gsap-left-logo">
                <img src="/images/logo.png" alt="One Point Logo" class="h-16 w-auto object-contain drop-shadow-2xl" @error="$event.target.style.display='none'" />
            </div>

            <div class="relative z-10 mt-auto">
                <h1 class="text-4xl xl:text-5xl font-bold tracking-tight leading-[1.1] mb-6 gsap-left-text">
                    One Point<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-gym-red to-gym-yellow">Gym Lamongan.</span>
                </h1>
                <p class="text-gray-400 text-lg max-w-md leading-relaxed gsap-left-text">
                    Sistem Informasi Rekomendasi Personal Trainer menggunakan metode <span class="text-gym-yellow font-medium">Content-Based Filtering</span>.
                </p>
            </div>
        </div>

        <!-- Right Side (Dynamic Content) -->
        <div class="w-full lg:w-7/12 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-[#0A0A0A] relative overflow-y-auto">
            <!-- Vue Transition for buttery smooth page swaps -->
            <transition name="auth-slide" mode="out-in">
                <div :key="$page.url" class="w-full max-w-md relative z-10 py-10">
                    <slot />
                </div>
            </transition>
        </div>
    </div>
</template>

<style>
/* 
 * Premium route transition: 
 * Fades out and slides slightly to the left, 
 * then the new page fades in and slides from the right.
 */
.auth-slide-enter-active,
.auth-slide-leave-active {
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.auth-slide-enter-from {
    opacity: 0;
    transform: translateX(20px);
}
.auth-slide-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}
</style>
