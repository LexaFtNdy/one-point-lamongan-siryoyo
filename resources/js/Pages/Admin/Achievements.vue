<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { PhMedal, PhCheckCircle, PhXCircle, PhClock, PhUser, PhBarbell, PhWarning, PhFunnel } from '@phosphor-icons/vue';
import { toast } from 'vue-sonner';

defineOptions({ layout: MemberLayout });

const props = defineProps({
    achievements: { type: Object, default: () => ({ data: [] }) },
    pendingCount: { type: Number, default: 0 },
    filters: { type: Object, default: () => ({}) },
});

const items = computed(() => props.achievements.data || []);

const rejectingId = ref(null);
const rejectReason = ref('');
const confirmApproveId = ref(null);

const categories = [
    { value: 'certification', label: 'Sertifikasi Profesional' },
    { value: 'license', label: 'Lisensi Resmi' },
    { value: 'education', label: 'Pendidikan Formal' },
    { value: 'award', label: 'Penghargaan / Pencapaian' },
    { value: 'seminar', label: 'Seminar / Workshop' },
    { value: 'other', label: 'Lainnya' },
];

const getCategoryLabel = (val) => categories.find(c => c.value === val)?.label || val;

const requestApprove = (id) => confirmApproveId.value = id;
const cancelApprove = () => confirmApproveId.value = null;
const confirmApprove = (id) => {
    router.patch(route('admin.achievements.approve', id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            confirmApproveId.value = null;
            toast.success('Kredensial berhasil disetujui.');
        },
    });
};

const requestReject = (id) => { rejectingId.value = id; rejectReason.value = ''; };
const cancelReject = () => { rejectingId.value = null; rejectReason.value = ''; };
const confirmReject = (id) => {
    if (!rejectReason.value.trim()) {
        toast.error('Alasan penolakan wajib diisi.');
        return;
    }
    router.patch(route('admin.achievements.reject', id), { reject_reason: rejectReason.value }, {
        preserveScroll: true,
        onSuccess: () => {
            rejectingId.value = null;
            rejectReason.value = '';
            toast.success('Kredensial ditolak dengan alasan.');
        },
    });
};

const filterByStatus = (status) => {
    router.get(route('admin.achievements'), status ? { status } : {}, { preserveState: true, replace: true });
};

const statusLabel = (status) => {
    switch (status) {
        case 'approved': return 'Disetujui';
        case 'rejected': return 'Ditolak';
        default: return 'Menunggu';
    }
};

const statusClass = (status) => {
    switch (status) {
        case 'approved': return 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30';
        case 'rejected': return 'bg-red-500/10 text-red-400 border-red-500/30';
        default: return 'bg-amber-500/10 text-amber-400 border-amber-500/30';
    }
};

const statusIcon = (status) => {
    switch (status) {
        case 'approved': return PhCheckCircle;
        case 'rejected': return PhXCircle;
        default: return PhClock;
    }
};

const isImage = (a) => a.certificate_mime?.startsWith('image/');
const isPdf = (a) => a.certificate_mime === 'application/pdf';
</script>

<template>
    <Head title="Verifikasi Kredensial - Admin" />

    <div class="max-w-6xl mx-auto pb-20 space-y-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight flex items-center gap-3">
                    <PhMedal class="text-amber-400" /> Verifikasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-500">Kredensial.</span>
                </h1>
                <p class="text-gray-400">Tinjau kredensial profesional sebelum dipublikasikan kepada member.</p>
            </div>
            <div class="bg-[#121212]/70 border border-white/10 rounded-2xl px-5 py-3 text-sm text-gray-300">
                Menunggu: <span class="text-amber-400 font-bold">{{ pendingCount }}</span>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 flex-wrap">
            <button
                @click="filterByStatus(null)"
                :class="['px-4 py-2 rounded-xl text-xs font-bold border transition-colors', !filters.status ? 'bg-white/10 text-white border-white/20' : 'bg-transparent text-gray-400 border-white/5 hover:bg-white/5']"
            >
                Semua
            </button>
            <button
                @click="filterByStatus('pending')"
                :class="['px-4 py-2 rounded-xl text-xs font-bold border transition-colors', filters.status === 'pending' ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' : 'bg-transparent text-gray-400 border-white/5 hover:bg-white/5']"
            >
                Menunggu
            </button>
            <button
                @click="filterByStatus('approved')"
                :class="['px-4 py-2 rounded-xl text-xs font-bold border transition-colors', filters.status === 'approved' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-transparent text-gray-400 border-white/5 hover:bg-white/5']"
            >
                Disetujui
            </button>
            <button
                @click="filterByStatus('rejected')"
                :class="['px-4 py-2 rounded-xl text-xs font-bold border transition-colors', filters.status === 'rejected' ? 'bg-red-500/10 text-red-400 border-red-500/30' : 'bg-transparent text-gray-400 border-white/5 hover:bg-white/5']"
            >
                Ditolak
            </button>
        </div>

        <div v-if="items.length === 0" class="py-16 text-center text-gray-500 border border-dashed border-white/10 rounded-3xl bg-[#121212]/30">
            Tidak ada data kredensial untuk filter ini.
        </div>

        <div v-else class="space-y-5">
            <div v-for="achievement in items" :key="achievement.id" class="bg-[#121212]/80 border border-white/10 rounded-2xl overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Document Preview -->
                    <div v-if="achievement.certificate_url" class="lg:w-56 lg:shrink-0">
                        <div v-if="isImage(achievement)" class="bg-black/50 h-full">
                            <img :src="achievement.certificate_url" alt="Dokumen" class="w-full h-48 lg:h-full object-contain" />
                        </div>
                        <div v-else-if="isPdf(achievement)" class="h-full">
                            <iframe :src="achievement.certificate_url" class="w-full h-48 lg:h-full bg-white" loading="lazy"></iframe>
                        </div>
                        <div v-else class="h-48 lg:h-full bg-gradient-to-br from-emerald-900/20 to-black flex items-center justify-center">
                            <a :href="achievement.certificate_url" target="_blank" class="text-emerald-400 hover:text-emerald-300 text-xs font-semibold underline">📄 Lihat Dokumen</a>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 p-5 space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span :class="['inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border', statusClass(achievement.status)]">
                                <component :is="statusIcon(achievement.status)" weight="bold" :size="14" />
                                {{ statusLabel(achievement.status) }}
                            </span>
                            <span v-if="achievement.category" class="px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-bold bg-white/5 text-gray-400 border border-white/10">
                                {{ getCategoryLabel(achievement.category) }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-bold border border-white/10 text-gray-500">
                                {{ achievement.visibility === 'public' ? 'Publik' : 'Privat' }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-base font-bold text-white leading-tight">{{ achievement.title }}</h3>
                            <p class="text-sm text-gray-400">Institusi: <span class="text-gray-200">{{ achievement.issuer }}</span></p>
                        </div>

                        <div class="flex flex-wrap gap-x-5 gap-y-1 text-xs text-gray-500">
                            <p v-if="achievement.achieved_at">Tanggal Perolehan: <span class="text-gray-300">{{ new Date(achievement.achieved_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span></p>
                            <p v-if="achievement.expires_at">Masa Berlaku: <span class="text-gray-300">{{ new Date(achievement.expires_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span></p>
                            <p v-else>Masa Berlaku: <span class="text-gray-300">Selamanya</span></p>
                        </div>

                        <p v-if="achievement.credential_id" class="text-xs text-gray-500">ID Kredensial: <span class="font-mono text-gray-300">{{ achievement.credential_id }}</span></p>
                        <a v-if="achievement.verification_url" :href="achievement.verification_url" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs text-blue-400 hover:text-blue-300 font-semibold">Tautan Verifikasi &nearr;</a>
                        <p v-if="achievement.description" class="text-xs text-gray-400">{{ achievement.description }}</p>

                        <!-- Trainer Info -->
                        <div class="flex items-center gap-3 text-xs text-gray-400 pt-1 border-t border-white/5">
                            <span class="inline-flex items-center gap-1.5"><PhUser class="text-blue-400" :size="14" /> {{ achievement.trainer_profile?.user?.name || 'Unknown' }}</span>
                            <span class="inline-flex items-center gap-1.5"><PhBarbell class="text-orange-400" :size="14" /> Personal Trainer</span>
                            <span v-if="achievement.reviewer" class="text-gray-600">Reviewer: {{ achievement.reviewer.name }}</span>
                        </div>

                        <!-- Reject Reason Display (for already rejected items) -->
                        <div v-if="achievement.status === 'rejected' && achievement.reject_reason" class="flex items-start gap-2 p-3 rounded-xl bg-red-500/10 border border-red-500/20">
                            <PhWarning class="text-red-400 shrink-0 mt-0.5" :size="16" weight="bold" />
                            <p class="text-xs text-red-300">{{ achievement.reject_reason }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div v-if="achievement.status === 'pending'" class="flex items-center gap-2 pt-2">
                            <template v-if="confirmApproveId === achievement.id">
                                <div class="flex items-center gap-2 p-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30">
                                    <button @click="confirmApprove(achievement.id)" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white transition-colors">Ya, Setujui</button>
                                    <button @click="cancelApprove" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white/5 text-gray-300 hover:bg-white/10 transition-colors">Batal</button>
                                </div>
                            </template>
                            <template v-else-if="rejectingId === achievement.id">
                                <div class="w-full space-y-2 p-3 rounded-xl bg-red-500/10 border border-red-500/30">
                                    <textarea
                                        v-model="rejectReason"
                                        rows="2"
                                        class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 resize-none"
                                        placeholder="Tuliskan alasan penolakan untuk trainer..."
                                    ></textarea>
                                    <div class="flex items-center gap-2">
                                        <button @click="confirmReject(achievement.id)" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-red-600 hover:bg-red-500 text-white transition-colors">Konfirmasi Tolak</button>
                                        <button @click="cancelReject" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white/5 text-gray-300 hover:bg-white/10 transition-colors">Batal</button>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <button @click="requestApprove(achievement.id)" class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white transition-colors">Setujui</button>
                                <button @click="requestReject(achievement.id)" class="px-4 py-2 rounded-xl text-xs font-bold bg-red-600 hover:bg-red-500 text-white transition-colors">Tolak</button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="achievements.links && achievements.links.length > 3" class="flex items-center justify-center gap-1 pt-4">
            <Link
                v-for="link in achievements.links"
                :key="link.label"
                :href="link.url || '#'"
                :class="[
                    'px-3 py-2 rounded-xl text-xs font-bold border transition-colors',
                    link.active ? 'bg-white/10 text-white border-white/20' : 'text-gray-400 border-white/5 hover:bg-white/5',
                    !link.url ? 'opacity-30 pointer-events-none' : ''
                ]"
                v-html="link.label"
                preserve-scroll
            />
        </div>
    </div>
</template>
