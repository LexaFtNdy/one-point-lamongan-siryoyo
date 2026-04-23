<template>
    <div class="relative overflow-hidden bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
        <!-- Shimmer Effect -->
        <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/5 to-transparent animate-[shimmer_1.5s_infinite]"></div>
        
        <div class="flex flex-col sm:flex-row gap-6">
            <!-- Avatar Skeleton -->
            <div class="w-24 h-24 rounded-2xl bg-white/10 flex-shrink-0"></div>
            
            <div class="flex-1 space-y-4">
                <!-- Title & Badge Skeleton -->
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <div class="w-40 h-6 bg-white/10 rounded-lg"></div>
                        <div class="w-24 h-4 bg-white/5 rounded-md"></div>
                    </div>
                    <div class="w-16 h-8 bg-white/10 rounded-full"></div>
                </div>

                <!-- Tags Skeleton -->
                <div class="flex gap-2">
                    <div class="w-20 h-6 bg-white/5 rounded-md"></div>
                    <div class="w-24 h-6 bg-white/5 rounded-md"></div>
                </div>

                <!-- Stats Skeleton -->
                <div class="flex gap-6 pt-4 border-t border-white/5">
                    <div class="w-16 h-4 bg-white/10 rounded-md"></div>
                    <div class="w-16 h-4 bg-white/10 rounded-md"></div>
                    <div class="w-20 h-4 bg-white/10 rounded-md"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@keyframes shimmer {
    100% {
        transform: translateX(100%);
    }
}
</style>
