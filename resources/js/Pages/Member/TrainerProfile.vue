<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import MemberLayout from '@/Layouts/MemberLayout.vue';
import { PhUser, PhStar, PhCalendarCheck, PhX } from '@phosphor-icons/vue';

defineOptions({ layout: MemberLayout });

const props = defineProps({
    trainer: Object,
});

const achievements = computed(() => props.trainer?.achievements || []);
const availableShifts = computed(() => props.trainer?.available_shifts || []);
const isModalOpen = ref(false);

const form = useForm({
    trainer_profile_id: props.trainer?.id || '',
    booking_date: '',
    shift: 'Pagi',
    message: ''
});

const openModal = () => {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    form.booking_date = tomorrow.toISOString().split('T')[0];
    if (props.trainer?.available_shifts?.length) {
        form.shift = props.trainer.available_shifts[0];
    }
    isModalOpen.value = true;
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

const isImage = (achievement) => achievement.certificate_mime?.startsWith('image/');
const isPdf = (achievement) => achievement.certificate_mime === 'application/pdf';

const categories = [
    { value: 'certification', label: 'Sertifikasi Profesional' },
    { value: 'license', label: 'Lisensi Resmi' },
    { value: 'education', label: 'Pendidikan Formal' },
    { value: 'award', label: 'Penghargaan / Pencapaian' },
    { value: 'seminar', label: 'Seminar / Workshop' },
    { value: 'other', label: 'Lainnya' },
];

const getCategoryLabel = (val) => categories.find(c => c.value === val)?.label || val;

const isExpired = (achievement) => {
    if (!achievement.expires_at) return false;
    return new Date(achievement.expires_at) < new Date();
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
</script>

<template>
    <Head title="Profil Trainer - One Point" />

    <div class="pb-10">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- LEFT COLUMN: Bio + Credentials -->
            <div class="flex-1 space-y-8 min-w-0">
                <!-- Trainer Bio Card -->
                <div class="bg-[#121212]/70 border border-white/10 rounded-[2rem] p-8 space-y-6">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-gray-800 to-black flex items-center justify-center border-2 border-white/10 shadow-xl">
                            <PhUser :size="28" class="text-gray-300" />
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">{{ trainer.user?.name }}</h1>
                            <p class="text-sm text-gray-400">{{ trainer.user?.gender === 'male' ? 'Male Trainer' : 'Female Trainer' }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2" v-if="trainer.specialties?.length">
                        <span v-for="(spec, idx) in trainer.specialties" :key="idx" class="px-3 py-1 bg-white/5 text-gray-300 rounded-lg text-xs font-semibold border border-white/10">
                            {{ spec }}
                        </span>
                    </div>

                    <p class="text-gray-400 leading-relaxed">{{ trainer.description || 'Belum ada deskripsi profil.' }}</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-[#1a1a1a] rounded-xl border border-white/10 p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Gaya Melatih</p>
                            <p class="text-white font-semibold">{{ trainer.coaching_style || '-' }}</p>
                        </div>
                        <div class="bg-[#1a1a1a] rounded-xl border border-white/10 p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Shift Tersedia</p>
                            <div class="flex flex-wrap gap-2">
                                <span v-for="shift in availableShifts" :key="shift" class="px-2 py-1 bg-white/5 text-xs text-gray-300 rounded-lg border border-white/10">{{ shift }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credentials Section (Integrated) -->
                <div class="space-y-5">
                    <div>
                        <h2 class="text-xl font-bold text-white">Kredensial & Sertifikasi Profesional</h2>
                        <p class="text-sm text-gray-500 mt-1">Seluruh kredensial di bawah telah diverifikasi oleh tim admin One Point.</p>
                    </div>

                    <div v-if="achievements.length === 0" class="py-12 text-center text-gray-500 border border-dashed border-white/10 rounded-2xl bg-[#121212]/30">
                        Trainer ini belum memiliki kredensial publik yang terverifikasi.
                    </div>

                    <div v-else class="space-y-4">
                        <div v-for="achievement in achievements" :key="achievement.id" class="bg-[#121212]/80 border border-white/10 rounded-2xl overflow-hidden">
                            <div class="flex flex-col sm:flex-row">
                                <!-- Certificate Thumbnail (left side) -->
                                <div v-if="achievement.certificate_url" class="sm:w-48 sm:shrink-0">
                                    <div v-if="isImage(achievement)" class="bg-black/50 h-full">
                                        <img :src="achievement.certificate_url" alt="Dokumen Kredensial" class="w-full h-40 sm:h-full object-contain" />
                                    </div>
                                    <div v-else-if="isPdf(achievement)" class="h-full">
                                        <iframe :src="achievement.certificate_url" class="w-full h-40 sm:h-full bg-white" loading="lazy"></iframe>
                                    </div>
                                    <div v-else class="h-40 sm:h-full bg-gradient-to-br from-emerald-900/30 to-black flex items-center justify-center">
                                        <a :href="achievement.certificate_url" target="_blank" class="text-emerald-400 hover:text-emerald-300 text-xs font-semibold underline">📄 Unduh</a>
                                    </div>
                                </div>

                                <!-- Info (right side) -->
                                <div class="p-5 space-y-2 min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span v-if="achievement.category" class="inline-block px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            {{ getCategoryLabel(achievement.category) }}
                                        </span>
                                        <span v-if="isExpired(achievement)" class="inline-block px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                            Kedaluwarsa
                                        </span>
                                    </div>
                                    <h3 class="text-base font-bold text-white leading-tight">{{ achievement.title }}</h3>
                                    <p class="text-sm text-gray-400 font-medium">{{ achievement.issuer }}</p>

                                    <div class="flex flex-wrap gap-x-4 gap-y-0.5 text-xs text-gray-500 pt-1">
                                        <p v-if="achievement.achieved_at">
                                            Diperoleh: <span class="text-gray-300">{{ new Date(achievement.achieved_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' }) }}</span>
                                        </p>
                                        <p v-if="achievement.expires_at">
                                            Berlaku s/d: <span class="text-gray-300">{{ new Date(achievement.expires_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' }) }}</span>
                                        </p>
                                        <p v-else-if="achievement.achieved_at">
                                            Berlaku: <span class="text-gray-300">Selamanya</span>
                                        </p>
                                    </div>

                                    <p v-if="achievement.credential_id" class="text-xs text-gray-500">
                                        ID: <span class="font-mono text-gray-300">{{ achievement.credential_id }}</span>
                                    </p>

                                    <p v-if="achievement.description" class="text-xs text-gray-400 line-clamp-2">{{ achievement.description }}</p>

                                    <a v-if="achievement.verification_url" :href="achievement.verification_url" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs text-blue-400 hover:text-blue-300 font-semibold transition-colors">
                                        Verifikasi Online &nearr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Sidebar (Sticky) -->
            <div class="w-full lg:w-80 shrink-0">
                <div class="lg:sticky lg:top-6 space-y-6">
                    <!-- Pricing & CTA -->
                    <div class="bg-[#121212]/70 border border-white/10 rounded-[2rem] p-6 space-y-5">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Tarif Per Sesi</p>
                            <p class="text-3xl font-bold text-emerald-400">{{ formatRupiah(trainer.price_per_session) }}</p>
                        </div>
                        <button @click="openModal" class="w-full px-6 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                            <PhCalendarCheck :size="20" weight="fill" /> Booking Sesi
                        </button>
                    </div>

                    <!-- Insight -->
                    <div class="bg-[#121212]/70 border border-white/10 rounded-[2rem] p-6 space-y-4">
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <PhStar class="text-gym-yellow" weight="fill" /> Insight
                        </div>
                        <div class="space-y-3 text-sm text-gray-300">
                            <p>Pastikan Anda memilih PT yang sesuai tujuan latihan, jadwal, dan budget.</p>
                            <p>Kredensial yang tampil telah diverifikasi oleh tim admin.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="isModalOpen = false"></div>
        <div class="relative bg-[#121212] border border-white/10 w-full max-w-md rounded-3xl shadow-2xl p-6 sm:p-8 overflow-hidden">
            <button @click="isModalOpen = false" class="absolute top-6 right-6 text-gray-400 hover:text-white transition-colors bg-white/5 p-2 rounded-full">
                <PhX :size="20" weight="bold" />
            </button>

            <h2 class="text-2xl font-bold text-white mb-2">Booking Sesi</h2>
            <p class="text-sm text-gray-400 mb-6">Bersama <strong class="text-gym-yellow">{{ trainer.user?.name }}</strong></p>

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
                        <label v-for="shift in availableShifts" :key="shift" class="cursor-pointer">
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
                        Rp {{ new Intl.NumberFormat('id-ID').format(trainer.price_per_session) }}
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
