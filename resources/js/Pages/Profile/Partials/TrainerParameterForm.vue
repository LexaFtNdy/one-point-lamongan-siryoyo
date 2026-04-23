<script setup>
import { useForm } from '@inertiajs/vue3';
import { PhCheck, PhMinus, PhPlus, PhFloppyDisk } from '@phosphor-icons/vue';
import { toast } from 'vue-sonner';

const props = defineProps({
    profile: Object
});

const form = useForm({
    specialties: props.profile?.specialties || [],
    handled_experience_levels: props.profile?.handled_experience_levels || [],
    coaching_style: props.profile?.coaching_style || 'Motivator',
    available_shifts: props.profile?.available_shifts || [],
    price_per_session: props.profile?.price_per_session ? parseInt(props.profile.price_per_session) : 100000,
    description: props.profile?.description || '',
});

const specialtyOptions = [
    'Fat Loss', 'Weight Loss', 'Obesity Management', 'Cutting', 'Bakar Lemak',
    'Muscle Gain', 'Bulking', 'Bodybuilding', 'Powerlifting', 'Hypertrophy',
    'Endurance', 'Cardio', 'HIIT', 'CrossFit', 'Marathon Prep',
    'Flexibility', 'Yoga', 'Pilates', 'Core Strength', 'Rehabilitation', 'Physiotherapy'
];

const experienceOptions = ['Beginner', 'Intermediate', 'Advanced'];
const shiftOptions = ['Pagi', 'Siang', 'Malam'];
const styleOptions = [
    { val: 'Drill Sergeant', label: 'Keras & Disiplin' },
    { val: 'Motivator', label: 'Ramah & Suportif' },
    { val: 'Educator', label: 'Edukatif & Ilmiah' }
];

const toggleArray = (field, value) => {
    const index = form[field].indexOf(value);
    if (index === -1) {
        if (field === 'specialties' && form.specialties.length >= 5) {
            toast.error('Maksimal 5 Spesialisasi');
            return;
        }
        form[field].push(value);
    } else {
        form[field].splice(index, 1);
    }
};

const updatePrice = (amount) => {
    const newVal = form.price_per_session + amount;
    if (newVal >= 50000 && newVal <= 2000000) {
        form.price_per_session = newVal;
    }
};

const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
};

const submit = () => {
    form.post(route('trainer.setup.update'), {
        preserveScroll: true,
        onSuccess: () => toast.success('Parameter berhasil disimpan!'),
    });
};
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-xl font-bold text-white mb-2">Parameter Rekomendasi PT</h2>
            <p class="text-sm text-gray-400">Pilih spesialisasi dan keahlian Anda secara presisi agar Algoritma Rekomendasi dapat mencocokkan Anda dengan Member yang tepat.</p>
        </header>

        <form @submit.prevent="submit" class="space-y-8">
            
            <!-- Specialties (Max 5) -->
            <div class="bg-[#121212]/50 p-6 rounded-2xl border border-white/5">
                <h3 class="text-lg font-bold text-white mb-2">Spesialisasi & Keahlian</h3>
                <p class="text-xs text-gray-400 mb-4">Pilih maksimal 5 *tag* yang paling merepresentasikan keahlian Anda.</p>
                <div class="flex flex-wrap gap-2">
                    <button 
                        v-for="opt in specialtyOptions" :key="opt" type="button"
                        @click="toggleArray('specialties', opt)"
                        :class="['px-3 py-1.5 rounded-full border text-xs font-medium transition-all', form.specialties.includes(opt) ? 'border-blue-500 bg-blue-500/20 text-white' : 'border-white/10 bg-[#1a1a1a] text-gray-400 hover:border-white/30']"
                    >
                        {{ opt }} <PhCheck v-if="form.specialties.includes(opt)" class="inline ml-1" />
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Coaching Style -->
                <div class="bg-[#121212]/50 p-6 rounded-2xl border border-white/5">
                    <h3 class="text-lg font-bold text-white mb-4">Gaya Melatih</h3>
                    <div class="space-y-2">
                        <label v-for="opt in styleOptions" :key="opt.val" class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all" :class="form.coaching_style === opt.val ? 'border-purple-500 bg-purple-500/10' : 'border-white/5 bg-[#1a1a1a]'">
                            <input type="radio" v-model="form.coaching_style" :value="opt.val" class="w-4 h-4 text-purple-500 bg-black border-white/20 focus:ring-purple-500 focus:ring-offset-black">
                            <span :class="form.coaching_style === opt.val ? 'text-white text-sm font-bold' : 'text-gray-400 text-sm'">{{ opt.label }}</span>
                        </label>
                    </div>
                </div>

                <!-- Shift -->
                <div class="bg-[#121212]/50 p-6 rounded-2xl border border-white/5">
                    <h3 class="text-lg font-bold text-white mb-4">Shift Tersedia</h3>
                    <div class="space-y-2">
                        <label v-for="opt in shiftOptions" :key="opt" @click="toggleArray('available_shifts', opt)" class="flex items-center justify-between gap-3 p-3 rounded-xl border cursor-pointer transition-all select-none" :class="form.available_shifts.includes(opt) ? 'border-orange-500 bg-orange-500/10' : 'border-white/5 bg-[#1a1a1a] hover:border-white/20'">
                            <span :class="form.available_shifts.includes(opt) ? 'text-white text-sm font-bold' : 'text-gray-400 text-sm'">{{ opt }}</span>
                            <!-- Custom Toggle -->
                            <div :class="['relative w-10 h-6 rounded-full transition-colors duration-300', form.available_shifts.includes(opt) ? 'bg-orange-500' : 'bg-white/10']">
                                <div :class="['absolute top-1 w-4 h-4 rounded-full bg-white shadow-md transition-all duration-300', form.available_shifts.includes(opt) ? 'left-5' : 'left-1']"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Target Klien & Harga -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-[#121212]/50 p-6 rounded-2xl border border-white/5">
                    <h3 class="text-lg font-bold text-white mb-4">Target Klien</h3>
                    <div class="flex flex-wrap gap-2">
                        <button 
                            v-for="opt in experienceOptions" :key="opt" type="button"
                            @click="toggleArray('handled_experience_levels', opt)"
                            :class="['px-3 py-1.5 rounded-lg border text-xs font-medium transition-all flex-1', form.handled_experience_levels.includes(opt) ? 'border-gym-yellow bg-gym-yellow/20 text-gym-yellow' : 'border-white/10 bg-[#1a1a1a] text-gray-400']"
                        >
                            {{ opt }}
                        </button>
                    </div>
                </div>

                <div class="bg-[#121212]/50 p-6 rounded-2xl border border-white/5">
                    <h3 class="text-lg font-bold text-white mb-4">Tarif Sesi Latihan (Rp)</h3>
                    <div class="flex items-center justify-between bg-[#1a1a1a] p-2 rounded-xl border border-white/10">
                        <button type="button" @click="updatePrice(-10000)" class="w-10 h-10 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-white"><PhMinus weight="bold" /></button>
                        <div class="relative flex items-center justify-center">
                            <span class="absolute left-0 text-gray-500 font-medium">Rp</span>
                            <input 
                                type="number" 
                                v-model.number="form.price_per_session"
                                class="w-32 bg-transparent border-none text-center text-xl font-bold text-emerald-400 outline-none focus:ring-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                            />
                        </div>
                        <button type="button" @click="updatePrice(10000)" class="w-10 h-10 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-white"><PhPlus weight="bold" /></button>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="bg-[#121212]/50 p-6 rounded-2xl border border-white/5">
                <h3 class="text-lg font-bold text-white mb-3">Bio Singkat</h3>
                <textarea v-model="form.description" rows="3" class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Ketik deskripsi diri agar member tertarik..."></textarea>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-4 pt-4 border-t border-white/10">
                <button type="submit" :disabled="form.processing" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all">
                    <PhFloppyDisk weight="bold" /> Simpan Parameter
                </button>
            </div>
        </form>
    </section>
</template>
