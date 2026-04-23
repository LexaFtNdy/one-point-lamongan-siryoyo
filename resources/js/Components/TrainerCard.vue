<script setup>
import { PhStar, PhMedal, PhGenderIntersex, PhWallet } from '@phosphor-icons/vue';

defineProps({
    trainer: {
        type: Object,
        required: true
    }
});

const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(number);
};
</script>

<template>
    <div class="group relative overflow-hidden bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 hover:bg-white/10 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_40px_-10px_rgba(255,193,7,0.15)]">
        
        <!-- Score Badge (Absolute Top Right) -->
        <div v-if="trainer.recommendation_score" class="absolute top-0 right-0 bg-gym-yellow text-slate-900 font-bold font-heading px-4 py-2 rounded-bl-2xl text-sm flex items-center gap-1">
            <PhStar weight="fill" />
            {{ trainer.recommendation_score }} Pts
        </div>

        <div class="flex flex-col sm:flex-row gap-6">
            <!-- Avatar Placeholder (using initial) -->
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-gym-red to-gym-yellow flex-shrink-0 flex items-center justify-center shadow-lg">
                <span class="text-4xl font-heading font-bold text-white shadow-sm">{{ trainer.user.name.charAt(0) }}</span>
            </div>
            
            <div class="flex-1">
                <!-- Title -->
                <div class="pr-20 sm:pr-24">
                    <h3 class="text-xl font-heading font-bold text-white group-hover:text-gym-yellow transition-colors">{{ trainer.user.name }}</h3>
                    <p class="text-sm text-slate-400 mt-1 line-clamp-2">{{ trainer.description || 'Professional Personal Trainer at One Point Gym Lamongan.' }}</p>
                </div>

                <!-- Tags (Specialties) -->
                <div class="flex flex-wrap gap-2 mt-4" v-if="trainer.specialties && trainer.specialties.length > 0">
                    <span v-for="specialty in trainer.specialties" :key="specialty" class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">
                        {{ specialty }}
                    </span>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-y-3 sm:gap-6 mt-5 pt-5 border-t border-white/5">
                    <div class="flex items-center gap-2 text-sm text-slate-300">
                        <PhGenderIntersex class="text-gym-yellow" />
                        <span class="capitalize">{{ trainer.user.gender === 'no_preference' ? 'Umum' : trainer.user.gender }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-300">
                        <PhWallet class="text-gym-yellow" />
                        <span>{{ formatRupiah(trainer.price_per_session) }} / Sesi</span>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="mt-6 flex justify-end">
                    <button class="px-6 py-2.5 bg-gym-red hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors shadow-lg shadow-gym-red/20 w-full sm:w-auto">
                        Booking Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
