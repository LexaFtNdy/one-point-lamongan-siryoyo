<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import gsap from 'gsap';
import { PhUser, PhStar, PhCurrencyCircleDollar, PhArrowRight, PhFaders, PhCalendarCheck, PhX, PhMedal } from '@phosphor-icons/vue';
import MemberLayout from '@/Layouts/MemberLayout.vue';

defineOptions({ layout: MemberLayout });

const props = defineProps({
    exact_matches: Array,
    alternatives: Array,
    filters: Object,
});

// Modal State
const selectedTrainer = ref(null);
const isModalOpen = ref(false);

const form = useForm({
    trainer_profile_id: '',
    booking_date: '',
    shift: 'Pagi',
    message: ''
});

const openModal = (trainer) => {
    selectedTrainer.value = trainer;
    form.trainer_profile_id = trainer.id;
    // Set minimal date ke besok
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    form.booking_date = tomorrow.toISOString().split('T')[0];
    
    // Default shift
    if(trainer.available_shifts && trainer.available_shifts.length > 0) {
        form.shift = trainer.available_shifts[0];
    }
    isModalOpen.value = true;
};

const goToProfile = (trainer) => {
    router.visit(route('trainer.show', trainer.id));
};

const submitBooking = () => {
    form.post(route('booking.store'), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset('message');
        }
    });
};



const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
};

const dateOptions = computed(() => {
    const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const options = [];
    for (let i = 1; i <= 14; i++) {
        const d = new Date();
        d.setDate(d.getDate() + i);
        options.push({
            value: d.toISOString().split('T')[0],
            day: days[d.getDay()],
            date: d.getDate(),
            month: months[d.getMonth()],
        });
    }
    return options;
});

onMounted(() => {
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    tl.from('.header-anim', { y: -20, opacity: 0, duration: 0.6, clearProps: 'all' })
      .from('.trainer-card', { y: 40, opacity: 0, duration: 0.6, stagger: 0.1, clearProps: 'all' }, '-=0.3');
});
</script>

<template>
    <Head title="Katalog Trainer - One Point" />

    <div class="space-y-8 pb-10">
        
        <!-- Premium Header Section -->
        <div class="header-anim flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-white/5">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gym-yellow/10 border border-gym-yellow/20 mb-3 backdrop-blur-md">
                    <PhStar weight="fill" class="text-gym-yellow" />
                    <span class="text-xs font-medium text-gym-yellow uppercase tracking-wider">Hasil Rekomendasi</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight leading-tight">
                    Rekomendasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-gym-yellow to-gym-red">Trainer</span>.
                </h1>
                <p class="text-gray-400 text-lg mt-3 max-w-xl">Berikut adalah daftar personal trainer yang paling cocok dengan tujuan dan kebutuhan latihanmu.</p>
            </div>
            
            <div class="flex-shrink-0">
                <Link :href="route('member.find-pt')" class="px-5 py-2.5 rounded-full bg-white/5 hover:bg-white/10 text-white text-sm font-medium transition-all border border-white/10">
                    Ubah Kriteria
                </Link>
            </div>
        </div>

        <!-- Exact Matches Grid -->
        <div v-if="exact_matches.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div 
                v-for="(trainer, index) in exact_matches" 
                :key="trainer.id"
                class="trainer-card relative overflow-hidden bg-[#121212]/60 backdrop-blur-xl border border-white/5 rounded-[2rem] p-8 transition-all duration-500 group transform hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.6)] hover:border-white/10 flex flex-col h-full cursor-pointer"
                role="button"
                tabindex="0"
                @click="goToProfile(trainer)"
                @keydown.enter="goToProfile(trainer)"
            >
                <div class="absolute top-0 right-0 w-48 h-48 bg-gym-red/5 rounded-full blur-[60px] group-hover:bg-gym-red/15 transition-colors duration-700 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col h-full">
                    
                    <!-- Top section: Avatar & Basic Info -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-5">
                            <div class="relative">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-gray-800 to-black flex items-center justify-center border-2 border-white/10 shadow-xl z-10 relative">
                                    <PhUser :size="28" class="text-gray-400 group-hover:text-white transition-colors duration-300" />
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white group-hover:text-gym-yellow transition-all duration-300">{{ trainer.user.name }}</h3>
                                <p class="text-sm text-gray-400 font-medium">{{ trainer.user.gender === 'male' ? 'Male Trainer' : 'Female Trainer' }}</p>
                            </div>
                        </div>
                        
                        <!-- Match Score Badge -->
                        <div v-if="trainer.match_score !== undefined" class="flex flex-col items-center justify-center px-3 py-2 bg-gym-red/10 border border-gym-red/20 rounded-xl">
                            <span class="text-2xl font-bold text-gym-red leading-none">{{ trainer.match_score }}%</span>
                            <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mt-1">Match</span>
                        </div>
                    </div>

                    <!-- Specialties Chips -->
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span 
                            v-for="(spec, idx) in trainer.specialties" 
                            :key="idx"
                            class="px-3 py-1 bg-white/5 backdrop-blur-md text-gray-300 rounded-lg text-xs font-semibold tracking-wide border border-white/10 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]"
                        >
                            {{ spec }}
                        </span>
                    </div>

                    <!-- Description -->
                    <p class="text-base text-gray-400 leading-relaxed mb-4 line-clamp-3 flex-grow font-light">
                        "{{ trainer.description || 'Belum ada deskripsi profil.' }}"
                    </p>

                    <!-- Credential Badge -->
                    <div v-if="trainer.verified_credentials_count > 0" class="flex items-center gap-1.5 mb-4">
                        <PhMedal :size="16" weight="fill" class="text-emerald-400" />
                        <span class="text-xs font-bold text-emerald-400">{{ trainer.verified_credentials_count }} Kredensial Terverifikasi</span>
                    </div>

                    <!-- Bottom Section: Price & Action -->
                    <div class="pt-6 border-t border-white/5 mt-auto flex items-end justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Investment</p>
                            <p class="text-white font-bold text-xl tracking-tight">{{ formatRupiah(trainer.price_per_session) }} <span class="text-sm text-gray-500 font-normal">/ session</span></p>
                        </div>
                        
                        <button @click.stop="openModal(trainer)" class="w-14 h-14 rounded-full bg-white text-black flex items-center justify-center hover:bg-gray-200 transition-colors duration-300 group/btn shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_30px_rgba(255,255,255,0.2)]">
                            <PhArrowRight :size="24" weight="bold" class="group-hover/btn:translate-x-1 transition-transform" />
                        </button>
                    </div>

                </div>
            </div>

        </div>

        <!-- Premium Empty State / Fuzzy Match -->
        <div v-else class="space-y-12">
            <!-- Alert Tidak Ada Match Persis -->
            <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-[2rem] p-8 flex flex-col md:flex-row items-center gap-6">
                <div class="w-16 h-16 rounded-full bg-yellow-500/20 flex items-center justify-center shrink-0">
                    <PhFaders :size="32" class="text-yellow-500" />
                </div>
                <div>
                    <h3 class="text-xl font-bold text-yellow-400 mb-2">Trainer dengan kriteria persis belum tersedia</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Sayangnya kami belum memiliki trainer yang 100% memenuhi filter ketat (seperti gender) yang Anda pilih. 
                        Namun, kami telah mencarikan <strong class="text-white">Alternatif Rekomendasi</strong> di bawah ini yang sangat cocok dengan tujuan, kondisi fisik, dan budget latihan Anda.
                    </p>
                </div>
            </div>

            <div v-if="alternatives.length > 0">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                    <PhStar class="text-blue-500" weight="fill" /> Alternatif Terbaik
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div 
                        v-for="(trainer, index) in alternatives.slice(0, 3)" 
                        :key="trainer.id"
                        class="relative overflow-hidden bg-[#121212]/60 backdrop-blur-xl border border-white/5 rounded-[2rem] p-8 transition-all duration-500 group hover:border-blue-500/30 flex flex-col h-full cursor-pointer"
                        role="button"
                        tabindex="0"
                        @click="goToProfile(trainer)"
                        @keydown.enter="goToProfile(trainer)"
                    >
                        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-500/5 rounded-full blur-[60px] group-hover:bg-blue-500/15 transition-colors duration-700 pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col h-full">
                            
                            <!-- Top section: Avatar & Basic Info -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-900 to-black flex items-center justify-center border-2 border-white/10 shadow-xl">
                                        <PhUser :size="24" class="text-blue-400" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">{{ trainer.user.name }}</h3>
                                        <p class="text-xs text-gray-400">{{ trainer.user.gender === 'male' ? 'Male Trainer' : 'Female Trainer' }}</p>
                                    </div>
                                </div>
                                
                                <div v-if="trainer.match_score !== undefined" class="flex flex-col items-center justify-center px-3 py-1 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                                    <span class="text-xl font-bold text-blue-400">{{ trainer.match_score }}%</span>
                                    <span class="text-[9px] text-gray-400 font-semibold uppercase tracking-wider">Match</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-6">
                                <span v-for="(spec, idx) in trainer.specialties" :key="idx" class="px-2 py-1 bg-white/5 text-gray-300 rounded-md text-xs border border-white/10">
                                    {{ spec }}
                                </span>
                            </div>

                            <div v-if="trainer.verified_credentials_count > 0" class="flex items-center gap-1.5 mb-4">
                                <PhMedal :size="14" weight="fill" class="text-emerald-400" />
                                <span class="text-xs font-bold text-emerald-400">{{ trainer.verified_credentials_count }} Kredensial</span>
                            </div>

                            <div class="pt-6 border-t border-white/5 mt-auto flex items-end justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Tarif</p>
                                    <p class="text-white font-bold text-lg tracking-tight">{{ formatRupiah(trainer.price_per_session) }}</p>
                                </div>
                                
                                <button @click.stop="openModal(trainer)" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition-colors text-sm">
                                    Pilih PT
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jika tidak ada alternatif sama sekali -->
            <div v-else class="py-24 text-center border border-dashed border-white/10 rounded-[3rem] bg-[#121212]/30 flex flex-col items-center justify-center">
                <h3 class="text-xl font-bold text-white mb-2">Maaf, Data Kosong</h3>
                <p class="text-gray-400 text-sm max-w-md mx-auto">Kami belum memiliki data Personal Trainer saat ini. Silakan kembali lagi nanti.</p>
            </div>
        </div>

    </div>

        <!-- Booking Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="isModalOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-[#121212] border border-white/10 w-full max-w-md rounded-3xl shadow-2xl p-6 sm:p-8 overflow-hidden transform transition-all">
                <!-- Close Btn -->
                <button @click="isModalOpen = false" class="absolute top-6 right-6 text-gray-400 hover:text-white transition-colors bg-white/5 p-2 rounded-full">
                    <PhX :size="20" weight="bold" />
                </button>

                <h2 class="text-2xl font-bold text-white mb-2">Booking Sesi</h2>
                <p class="text-sm text-gray-400 mb-6">Bersama <strong class="text-gym-yellow">{{ selectedTrainer?.user?.name }}</strong></p>

                <div v-if="form.errors.schedule" class="mb-4 p-3 bg-red-500/20 border border-red-500/30 rounded-xl text-red-400 text-sm font-medium">
                    {{ form.errors.schedule }}
                </div>

                <form @submit.prevent="submitBooking" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-3">Pilih Tanggal Latihan</label>
                        <div class="grid grid-cols-3 gap-2 max-h-40 overflow-y-auto pr-1 custom-scroll">
                            <button
                                v-for="d in dateOptions"
                                :key="d.value"
                                type="button"
                                @click="form.booking_date = d.value"
                                :class="['flex flex-col items-center py-3 px-2 rounded-xl border-2 text-center transition-all', form.booking_date === d.value ? 'border-blue-500 bg-blue-500/10' : 'border-white/5 bg-[#1a1a1a] hover:bg-white/5']"
                            >
                                <span class="text-[10px] uppercase tracking-wider" :class="form.booking_date === d.value ? 'text-blue-400' : 'text-gray-500'">{{ d.day }}</span>
                                <span class="text-lg font-bold" :class="form.booking_date === d.value ? 'text-white' : 'text-gray-300'">{{ d.date }}</span>
                                <span class="text-[10px]" :class="form.booking_date === d.value ? 'text-blue-400' : 'text-gray-500'">{{ d.month }}</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Pilih Shift</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label v-for="shift in selectedTrainer?.available_shifts" :key="shift" class="cursor-pointer">
                                <input type="radio" v-model="form.shift" :value="shift" class="sr-only">
                                <div :class="['text-center px-2 py-3 rounded-xl border-2 text-sm transition-colors', form.shift === shift ? 'border-orange-500 bg-orange-500/10 text-orange-400 font-bold' : 'border-white/5 bg-[#1a1a1a] text-gray-400 hover:bg-white/5']">
                                    {{ shift }}
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Pesan untuk PT (Opsional)</label>
                        <textarea
                            v-model="form.message"
                            rows="2"
                            placeholder="Tulis kondisi khusus atau catatan untuk PT..."
                            class="w-full bg-[#1a1a1a] border-white/10 border-2 rounded-xl text-white px-4 py-3 outline-none focus:border-blue-500 transition-colors resize-none placeholder-gray-600 text-sm"
                        ></textarea>
                    </div>

                    <div class="pt-4 mt-4 border-t border-white/10 flex justify-between items-center mb-6">
                        <span class="text-gray-400">Harga Per Sesi</span>
                        <span class="text-2xl font-bold text-emerald-400">
                            Rp {{ new Intl.NumberFormat('id-ID').format(selectedTrainer?.price_per_session) }}
                        </span>
                    </div>

                    <button type="submit" :disabled="form.processing" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                        <PhCalendarCheck :size="24" weight="fill" />
                        {{ form.processing ? 'Memproses...' : 'Konfirmasi Booking' }}
                    </button>
                </form>
            </div>
        </div>

</template>
