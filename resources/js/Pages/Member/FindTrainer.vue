<script setup>
import { ref, onMounted } from 'vue';
import gsap from 'gsap';
import { Head, useForm } from '@inertiajs/vue3';
import { 
    PhArrowRight, PhMinus, PhPlus, 
    PhFire, PhBarbell, PhHeartbeat, PhPersonSimpleTaiChi,
    PhGenderIntersex, PhFirstAid, PhBrain, PhClock, PhMoney
} from '@phosphor-icons/vue';
import MemberLayout from '@/Layouts/MemberLayout.vue';

defineOptions({ layout: MemberLayout });

const form = useForm({
    goal: 'Weight Loss',
    gender_preference: 'no_preference',
    experience_level: 'Beginner',
    coaching_style: 'Motivator',
    available_shift: 'Malam',
    max_budget: 150000,
});

// Helper for Steppers
const updateValue = (field, amount, min = 0, max = 9999999) => {
    const newValue = form[field] + amount;
    if (newValue >= min && newValue <= max) {
        form[field] = newValue;
    }
};

const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
};

const submit = () => {
    form.post(route('member.find-pt.results'));
};

onMounted(() => {
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    tl.from('.header-anim', { y: -30, opacity: 0, duration: 0.8 })
      .from('.question-section', { y: 40, opacity: 0, duration: 0.6, stagger: 0.15 }, '-=0.4')
      .from('.submit-btn', { scale: 0.9, opacity: 0, duration: 0.4 }, '-=0.2');
});
</script>

<template>
    <Head title="Kuesioner Rekomendasi - One Point" />

    <div class="max-w-3xl mx-auto pb-32">
        
        <!-- Header -->
        <div class="header-anim mb-16 text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight leading-tight mb-4">
                Input <span class="text-transparent bg-clip-text bg-gradient-to-r from-gym-yellow to-gym-red">Kebutuhan.</span>
            </h1>
            <p class="text-gray-400 text-lg">Pilih sesuai dengan kondisi dan target Anda.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-16">
            
            <!-- SECTION 1: GOAL -->
            <div class="question-section space-y-4">
                <h3 class="text-xl font-bold text-white text-center">Apa Tujuan Utama Anda?</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" v-model="form.goal" value="Weight Loss" class="sr-only">
                        <div :class="['flex items-center gap-4 p-5 rounded-2xl border-2 transition-all duration-300', form.goal === 'Weight Loss' ? 'border-gym-red bg-gym-red/10 shadow-[0_0_20px_rgba(214,0,0,0.2)]' : 'border-white/5 bg-[#121212] hover:bg-[#1a1a1a]']">
                            <div :class="['p-3 rounded-full', form.goal === 'Weight Loss' ? 'bg-gym-red text-white' : 'bg-white/5 text-gray-400']"><PhFire :size="28" weight="fill" /></div>
                            <div>
                                <h4 class="font-bold text-white text-lg">Fat Loss</h4>
                                <p class="text-sm text-gray-400">Turun berat badan & bakar lemak</p>
                            </div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" v-model="form.goal" value="Muscle Gain" class="sr-only">
                        <div :class="['flex items-center gap-4 p-5 rounded-2xl border-2 transition-all duration-300', form.goal === 'Muscle Gain' ? 'border-blue-500 bg-blue-500/10 shadow-[0_0_20px_rgba(59,130,246,0.2)]' : 'border-white/5 bg-[#121212] hover:bg-[#1a1a1a]']">
                            <div :class="['p-3 rounded-full', form.goal === 'Muscle Gain' ? 'bg-blue-500 text-white' : 'bg-white/5 text-gray-400']"><PhBarbell :size="28" weight="fill" /></div>
                            <div>
                                <h4 class="font-bold text-white text-lg">Muscle Gain</h4>
                                <p class="text-sm text-gray-400">Bentuk otot & tambah berat</p>
                            </div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" v-model="form.goal" value="Endurance" class="sr-only">
                        <div :class="['flex items-center gap-4 p-5 rounded-2xl border-2 transition-all duration-300', form.goal === 'Endurance' ? 'border-gym-yellow bg-gym-yellow/10 shadow-[0_0_20px_rgba(255,193,7,0.2)]' : 'border-white/5 bg-[#121212] hover:bg-[#1a1a1a]']">
                            <div :class="['p-3 rounded-full', form.goal === 'Endurance' ? 'bg-gym-yellow text-black' : 'bg-white/5 text-gray-400']"><PhHeartbeat :size="28" weight="fill" /></div>
                            <div>
                                <h4 class="font-bold text-white text-lg">Endurance</h4>
                                <p class="text-sm text-gray-400">Tingkatkan stamina & kardio</p>
                            </div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" v-model="form.goal" value="Flexibility" class="sr-only">
                        <div :class="['flex items-center gap-4 p-5 rounded-2xl border-2 transition-all duration-300', form.goal === 'Flexibility' ? 'border-emerald-500 bg-emerald-500/10 shadow-[0_0_20px_rgba(16,185,129,0.2)]' : 'border-white/5 bg-[#121212] hover:bg-[#1a1a1a]']">
                            <div :class="['p-3 rounded-full', form.goal === 'Flexibility' ? 'bg-emerald-500 text-white' : 'bg-white/5 text-gray-400']"><PhPersonSimpleTaiChi :size="28" weight="fill" /></div>
                            <div>
                                <h4 class="font-bold text-white text-lg">Flexibility</h4>
                                <p class="text-sm text-gray-400">Kelenturan, postur & Yoga</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- FISIK DAN CEDERA SUDAH DIAMBIL DARI PROFILE -->

            <!-- SECTION 2: PENGALAMAN & GAYA MELATIH -->
            <div class="space-y-10">
                <!-- Pengalaman -->
                <div>
                    <h3 class="text-lg font-bold text-white mb-4 text-center">Pengalaman Gym</h3>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label v-for="opt in [{val:'Beginner', label:'Pemula'}, {val:'Intermediate', label:'Menengah'}, {val:'Advanced', label:'Lanjutan'}]" :key="opt.val" class="flex-1 cursor-pointer">
                            <input type="radio" v-model="form.experience_level" :value="opt.val" class="sr-only">
                            <div :class="['text-center px-4 py-3 rounded-xl border-2 transition-colors duration-300', form.experience_level === opt.val ? 'border-gym-yellow bg-gym-yellow/10 text-gym-yellow font-bold' : 'border-white/5 bg-[#121212] text-gray-400 hover:bg-[#1a1a1a]']">
                                {{ opt.label }}
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Gaya Melatih -->
                <div>
                    <h3 class="text-lg font-bold text-white mb-4 text-center">Preferensi Sifat PT</h3>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label v-for="opt in [{val:'Drill Sergeant', label:'Disiplin & Keras'}, {val:'Motivator', label:'Ramah & Suportif'}, {val:'Educator', label:'Edukatif & Ilmiah'}]" :key="opt.val" class="flex-1 cursor-pointer">
                            <input type="radio" v-model="form.coaching_style" :value="opt.val" class="sr-only">
                            <div :class="['text-center px-4 py-3 rounded-xl border-2 transition-colors duration-300', form.coaching_style === opt.val ? 'border-purple-500 bg-purple-500/10 text-purple-400 font-bold' : 'border-white/5 bg-[#121212] text-gray-400 hover:bg-[#1a1a1a]']">
                                {{ opt.label }}
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: SHIFT & BUDGET -->
            <div class="space-y-10 bg-[#121212]/50 p-8 rounded-3xl border border-white/5">
                <!-- Shift -->
                <div>
                    <h3 class="text-lg font-bold text-white mb-4 text-center">Jadwal Latihan</h3>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label v-for="opt in [{val:'Pagi', label:'Pagi (06-12)'}, {val:'Siang', label:'Siang (12-18)'}, {val:'Malam', label:'Malam (18-22)'}]" :key="opt.val" class="flex-1 cursor-pointer">
                            <input type="radio" v-model="form.available_shift" :value="opt.val" class="sr-only">
                            <div :class="['text-center px-4 py-3 rounded-xl border-2 transition-colors duration-300', form.available_shift === opt.val ? 'border-orange-500 bg-orange-500/10 text-orange-400 font-bold' : 'border-white/5 bg-[#1a1a1a] text-gray-400 hover:bg-white/5']">
                                {{ opt.label }}
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Budget -->
                <div>
                    <h3 class="text-lg font-bold text-white mb-4 text-center">Budget Maksimal Per Sesi (Rp)</h3>
                    <div class="flex items-center justify-between bg-[#1a1a1a] p-3 rounded-2xl border border-white/10 max-w-md mx-auto">
                        <button type="button" @click="updateValue('max_budget', -10000, 50000, 1000000)" class="w-14 h-14 flex items-center justify-center rounded-xl bg-white/5 text-white hover:bg-white/10 active:scale-95 transition-all"><PhMinus :size="24" weight="bold" /></button>
                        <div class="relative flex items-center justify-center px-4 w-full">
                            <span class="absolute left-6 text-gray-500 font-medium text-xl">Rp</span>
                            <input 
                                type="number" 
                                v-model.number="form.max_budget"
                                class="w-full bg-transparent border-none text-center text-2xl sm:text-3xl font-bold text-emerald-400 outline-none focus:ring-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none tracking-tight"
                            />
                        </div>
                        <button type="button" @click="updateValue('max_budget', 10000, 50000, 1000000)" class="w-14 h-14 flex items-center justify-center rounded-xl bg-white/5 text-white hover:bg-white/10 active:scale-95 transition-all"><PhPlus :size="24" weight="bold" /></button>
                    </div>
                </div>
            </div>

            <!-- SECTION 6: GENDER (FILTER) -->
            <div>
                <h3 class="text-lg font-bold text-white mb-4 text-center">Preferensi Gender PT</h3>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label v-for="opt in [{val:'no_preference', label:'Bebas'}, {val:'male', label:'Pria'}, {val:'female', label:'Wanita'}]" :key="opt.val" class="flex-1 cursor-pointer">
                        <input type="radio" v-model="form.gender_preference" :value="opt.val" class="sr-only">
                        <div :class="['text-center px-4 py-4 rounded-xl border-2 transition-colors duration-300', form.gender_preference === opt.val ? 'border-blue-500 bg-blue-500/10 text-blue-400 font-bold' : 'border-white/5 bg-[#121212] text-gray-400 hover:bg-[#1a1a1a]']">
                            {{ opt.label }}
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center pt-8">
                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="group relative inline-flex items-center justify-center gap-3 px-12 py-5 bg-gradient-to-r from-gym-red to-red-800 text-white font-bold rounded-full overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-[0_0_40px_rgba(214,0,0,0.6)] w-full sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed border border-red-500/50 text-xl"
                >
                    <span class="relative z-10 flex items-center gap-2">
                        {{ form.processing ? 'Memproses Data...' : 'Cari Rekomendasi Sekarang' }} 
                        <PhArrowRight v-if="!form.processing" :size="24" weight="bold" class="group-hover:translate-x-2 transition-transform duration-300" />
                    </span>
                </button>
            </div>

        </form>
    </div>
</template>
