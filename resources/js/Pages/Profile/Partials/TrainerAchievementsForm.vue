<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { PhCheckCircle, PhClock, PhXCircle, PhPencilSimple, PhTrash, PhUploadSimple, PhGlobe, PhLock, PhCalendarBlank, PhCaretDown, PhCaretLeft, PhCaretRight, PhCheck, PhWarning } from '@phosphor-icons/vue';
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue';
import { toast } from 'vue-sonner';

const props = defineProps({
    profile: Object
});

const profile = computed(() => props.profile);
const achievements = computed(() => profile.value?.achievements || []);
const maxAchievements = 3;

const isEditing = ref(false);
const editingId = ref(null);
const editingAchievement = ref(null);
const pendingDeleteId = ref(null);
const isDragActive = ref(false);

const form = useForm({
    category: 'certification',
    title: '',
    issuer: '',
    credential_id: '',
    verification_url: '',
    description: '',
    achieved_at: '',
    expires_at: '',
    visibility: 'private',
    certificate: null,
});

const categories = [
    { value: 'certification', label: 'Sertifikasi Profesional' },
    { value: 'license', label: 'Lisensi Resmi' },
    { value: 'education', label: 'Pendidikan Formal' },
    { value: 'award', label: 'Penghargaan / Pencapaian' },
    { value: 'seminar', label: 'Seminar / Workshop' },
    { value: 'other', label: 'Lainnya' },
];

const selectedCategoryLabel = computed(() => {
    return categories.find(c => c.value === form.category)?.label || 'Pilih Kategori';
});

const getCategoryLabel = (val) => categories.find(c => c.value === val)?.label || val;

const allowedMimes = [
    'image/jpeg',
    'image/png',
    'image/webp',
    'application/pdf',
];

const canAdd = computed(() => achievements.value.length < maxAchievements || isEditing.value);

// --- CALENDAR LOGIC ---
const activeDateType = ref(null);
const displayMonth = ref(new Date().getMonth());
const displayYear = ref(new Date().getFullYear());
const calendarRef = ref(null);

const monthLabels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const weekdayLabels = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

const calendarDays = computed(() => {
    const firstDayIndex = new Date(displayYear.value, displayMonth.value, 1).getDay();
    const daysInMonth = new Date(displayYear.value, displayMonth.value + 1, 0).getDate();
    const days = [];
    for (let i = 0; i < firstDayIndex; i++) days.push(null);
    for (let day = 1; day <= daysInMonth; day++) days.push(new Date(displayYear.value, displayMonth.value, day));
    while (days.length < 42) days.push(null);
    return days;
});

const isSameDay = (d1, d2) => d1 && d2 && d1.getFullYear() === d2.getFullYear() && d1.getMonth() === d2.getMonth() && d1.getDate() === d2.getDate();
const isToday = (date) => date && isSameDay(date, new Date());
const isSelectedDate = (date) => {
    if (!date) return false;
    const targetDateStr = activeDateType.value === 'achieved' ? form.achieved_at : form.expires_at;
    if (!targetDateStr) return false;
    const [y, m, d] = targetDateStr.split('-').map(Number);
    return date.getFullYear() === y && date.getMonth() === m - 1 && date.getDate() === d;
};

const openCalendar = (type) => {
    if (activeDateType.value === type) {
        activeDateType.value = null;
        return;
    }
    activeDateType.value = type;
    const currentVal = type === 'achieved' ? form.achieved_at : form.expires_at;
    if (currentVal) {
        const [y, m, d] = currentVal.split('-');
        displayYear.value = parseInt(y);
        displayMonth.value = parseInt(m) - 1;
    } else {
        displayYear.value = new Date().getFullYear();
        displayMonth.value = new Date().getMonth();
    }
};

const closeCalendar = () => activeDateType.value = null;

const selectDate = (date) => {
    if (!date) return;
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    const formatted = `${y}-${m}-${d}`;
    
    if (activeDateType.value === 'achieved') form.achieved_at = formatted;
    if (activeDateType.value === 'expires') form.expires_at = formatted;
    closeCalendar();
};

const clearDate = (type) => {
    if (type === 'achieved') form.achieved_at = '';
    if (type === 'expires') form.expires_at = '';
    closeCalendar();
};

const prevMonth = () => {
    if (displayMonth.value === 0) { displayMonth.value = 11; displayYear.value--; }
    else { displayMonth.value--; }
};
const nextMonth = () => {
    if (displayMonth.value === 11) { displayMonth.value = 0; displayYear.value++; }
    else { displayMonth.value++; }
};

const onClickOutside = (event) => {
    if (calendarRef.value && !calendarRef.value.contains(event.target)) {
        closeCalendar();
    }
};

onMounted(() => document.addEventListener('click', onClickOutside));
onUnmounted(() => document.removeEventListener('click', onClickOutside));

const formatDisplayDate = (dateStr) => {
    if (!dateStr) return 'Pilih Tanggal';
    const [y, m, d] = dateStr.split('-');
    return `${d} ${monthLabels[parseInt(m) - 1].substring(0,3)} ${y}`;
};

// --- FORM LOGIC ---
const resetForm = () => {
    form.reset();
    form.clearErrors();
    form.visibility = 'private';
    form.category = 'certification';
    isEditing.value = false;
    editingId.value = null;
    editingAchievement.value = null;
    closeCalendar();
};

const startEdit = (achievement) => {
    isEditing.value = true;
    editingId.value = achievement.id;
    editingAchievement.value = achievement;
    form.category = achievement.category || 'certification';
    form.title = achievement.title || '';
    form.issuer = achievement.issuer || '';
    form.credential_id = achievement.credential_id || '';
    form.verification_url = achievement.verification_url || '';
    form.description = achievement.description || '';
    form.achieved_at = achievement.achieved_at ? achievement.achieved_at.split('T')[0] : '';
    form.expires_at = achievement.expires_at ? achievement.expires_at.split('T')[0] : '';
    form.visibility = achievement.visibility || 'private';
    form.certificate = null;
    closeCalendar();
};

const handleFile = (file) => {
    if (!file) return;
    if (!allowedMimes.includes(file.type)) {
        toast.error('Format file tidak didukung. Gunakan JPG, PNG, WebP, atau PDF.');
        return;
    }
    form.certificate = file;
};

const onFileChange = (event) => handleFile(event.target.files?.[0]);
const onDrop = (event) => { isDragActive.value = false; handleFile(event.dataTransfer?.files?.[0]); };
const onDragOver = () => isDragActive.value = true;
const onDragLeave = () => isDragActive.value = false;
const clearFile = () => form.certificate = null;

const submit = () => {
    if (!canAdd.value) {
        toast.error('Maksimal 3 kredensial per personal trainer.');
        return;
    }

    const wasEditing = isEditing.value;
    const options = {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            resetForm();
            toast.success(wasEditing ? 'Kredensial profesional berhasil diperbarui.' : 'Kredensial profesional berhasil ditambahkan.');
        },
    };

    if (isEditing.value && editingId.value) {
        form.patch(route('trainer.achievements.update', editingId.value), options);
        return;
    }

    form.post(route('trainer.achievements.store'), options);
};

const requestDelete = (achievementId) => pendingDeleteId.value = achievementId;
const cancelDelete = () => pendingDeleteId.value = null;
const confirmDelete = (achievementId) => {
    router.delete(route('trainer.achievements.destroy', achievementId), {
        preserveScroll: true,
        onSuccess: () => {
            pendingDeleteId.value = null;
            toast.success('Kredensial profesional berhasil dihapus.');
        },
    });
};

const statusLabel = (status) => {
    switch (status) {
        case 'approved': return 'Disetujui';
        case 'rejected': return 'Ditolak';
        default: return 'Menunggu Verifikasi';
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

const isImage = (achievement) => achievement.certificate_mime?.startsWith('image/');
const isPdf = (achievement) => achievement.certificate_mime === 'application/pdf';
</script>

<template>
    <section class="space-y-8" @click="onClickOutside">
        <header class="space-y-2">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-bold text-white">Kredensial Profesional</h2>
                <span class="text-xs font-semibold text-gray-400">{{ achievements.length }}/{{ maxAchievements }} Terpakai</span>
            </div>
            <p class="text-sm text-gray-400">Tambahkan sertifikasi, lisensi, atau riwayat pendidikan. Tim admin akan melakukan verifikasi sebelum dipublikasikan ke member.</p>
        </header>

        <div v-if="form.errors.limit" class="p-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-400 text-sm">
            {{ form.errors.limit }}
        </div>

        <div v-if="!profile" class="p-6 rounded-2xl border border-amber-500/20 bg-amber-500/10 text-amber-300 text-sm">
            Lengkapi parameter profil (Tarif, Jadwal, Spesialisasi) terlebih dahulu sebelum menambahkan kredensial.
        </div>

        <form v-else @submit.prevent="submit" class="space-y-6 bg-[#121212]/50 p-6 rounded-2xl border border-white/5 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- CUSTOM DROPDOWN (Headless UI) -->
                <div class="relative z-50">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kategori Kredensial <span class="text-xs text-red-400">(Wajib)</span></label>
                    <Listbox v-model="form.category">
                        <div class="relative mt-1">
                            <ListboxButton
                                class="relative w-full cursor-pointer rounded-xl bg-[#1a1a1a] border border-white/10 py-3 pl-4 pr-10 text-left text-sm text-white focus:outline-none focus-visible:border-emerald-500 focus-visible:ring-1 focus-visible:ring-emerald-500"
                            >
                                <span class="block truncate">{{ selectedCategoryLabel }}</span>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <PhCaretDown class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                </span>
                            </ListboxButton>

                            <transition
                                leave-active-class="transition duration-100 ease-in"
                                leave-from-class="opacity-100"
                                leave-to-class="opacity-0"
                            >
                                <ListboxOptions
                                    class="absolute mt-2 max-h-60 w-full overflow-auto rounded-xl bg-[#1e1e1e] border border-white/10 py-1 text-base shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm z-[100]"
                                >
                                    <ListboxOption
                                        v-slot="{ active, selected }"
                                        v-for="cat in categories"
                                        :key="cat.value"
                                        :value="cat.value"
                                        as="template"
                                    >
                                        <li
                                            :class="[
                                                active ? 'bg-emerald-500/10 text-emerald-400' : 'text-gray-300',
                                                'relative cursor-pointer select-none py-2.5 pl-10 pr-4 transition-colors'
                                            ]"
                                        >
                                            <span :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">{{ cat.label }}</span>
                                            <span
                                                v-if="selected"
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-emerald-400"
                                            >
                                                <PhCheck class="h-4 w-4" aria-hidden="true" />
                                            </span>
                                        </li>
                                    </ListboxOption>
                                </ListboxOptions>
                            </transition>
                        </div>
                    </Listbox>
                    <div v-if="form.errors.category" class="text-xs text-red-400 mt-2">{{ form.errors.category }}</div>
                </div>

                <div class="relative z-40">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Kredensial / Gelar <span class="text-xs text-red-400">(Wajib)</span></label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        placeholder="Contoh: Certified Personal Trainer (CPT)"
                    />
                    <div v-if="form.errors.title" class="text-xs text-red-400 mt-2">{{ form.errors.title }}</div>
                </div>

                <div class="relative z-30">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Institusi Penerbit <span class="text-xs text-red-400">(Wajib)</span></label>
                    <input
                        v-model="form.issuer"
                        type="text"
                        class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        placeholder="Contoh: NASM, ACE, ISSA, BNSP"
                    />
                    <div v-if="form.errors.issuer" class="text-xs text-red-400 mt-2">{{ form.errors.issuer }}</div>
                </div>

                <div class="relative z-20">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nomor Identitas Kredensial (Opsional)</label>
                    <input
                        v-model="form.credential_id"
                        type="text"
                        class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        placeholder="Contoh: CPT-2025-8891"
                    />
                    <div v-if="form.errors.credential_id" class="text-xs text-red-400 mt-2">{{ form.errors.credential_id }}</div>
                </div>

                <!-- CUSTOM DATEPICKER: Tanggal Perolehan -->
                <div class="relative" ref="calendarRef" @click.stop>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Perolehan <span class="text-xs text-red-400">(Wajib)</span></label>
                    <button
                        type="button"
                        @click="openCalendar('achieved')"
                        class="w-full flex items-center justify-between bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-left focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        :class="form.achieved_at ? 'text-white' : 'text-gray-500'"
                    >
                        <span class="text-sm">{{ formatDisplayDate(form.achieved_at) }}</span>
                        <PhCalendarBlank class="w-5 h-5 text-gray-400" />
                    </button>
                    <div v-if="form.errors.achieved_at" class="text-xs text-red-400 mt-2">{{ form.errors.achieved_at }}</div>
                    
                    <div v-if="activeDateType === 'achieved'" class="absolute mt-2 w-full sm:w-80 bg-[#1e1e1e] border border-white/10 rounded-2xl shadow-2xl z-50 p-4">
                        <div class="flex items-center justify-between mb-4">
                            <button type="button" @click.stop="prevMonth" class="p-2 hover:bg-white/10 rounded-xl text-gray-300 transition-colors"><PhCaretLeft :size="16" weight="bold" /></button>
                            <span class="text-sm font-bold text-white">{{ monthLabels[displayMonth] }} {{ displayYear }}</span>
                            <button type="button" @click.stop="nextMonth" class="p-2 hover:bg-white/10 rounded-xl text-gray-300 transition-colors"><PhCaretRight :size="16" weight="bold" /></button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            <div v-for="day in weekdayLabels" :key="day" class="text-center text-[10px] font-bold text-gray-500 py-1">{{ day }}</div>
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            <div v-for="(dateObj, i) in calendarDays" :key="i" class="aspect-square">
                                <button
                                    v-if="dateObj"
                                    type="button"
                                    @click.stop="selectDate(dateObj)"
                                    class="w-full h-full flex items-center justify-center text-xs rounded-xl transition-all"
                                    :class="[
                                        isSelectedDate(dateObj) ? 'bg-emerald-500 text-white font-bold shadow-lg shadow-emerald-500/30' : 
                                        isToday(dateObj) ? 'bg-white/10 text-white font-bold border border-white/20' : 
                                        'text-gray-300 hover:bg-white/5'
                                    ]"
                                >
                                    {{ dateObj.getDate() }}
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-white/10 flex justify-end">
                            <button type="button" @click.stop="clearDate('achieved')" class="text-xs text-red-400 hover:text-red-300 px-3 py-1.5 font-medium transition-colors">Clear Tanggal</button>
                        </div>
                    </div>
                </div>

                <!-- CUSTOM DATEPICKER: Masa Berlaku -->
                <div class="relative" @click.stop>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Masa Berlaku / Kedaluwarsa (Opsional)</label>
                    <button
                        type="button"
                        @click="openCalendar('expires')"
                        class="w-full flex items-center justify-between bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-left focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        :class="form.expires_at ? 'text-white' : 'text-gray-500'"
                    >
                        <span class="text-sm">{{ formatDisplayDate(form.expires_at) }}</span>
                        <PhCalendarBlank class="w-5 h-5 text-gray-400" />
                    </button>
                    <div v-if="form.errors.expires_at" class="text-xs text-red-400 mt-2">{{ form.errors.expires_at }}</div>

                    <div v-if="activeDateType === 'expires'" class="absolute mt-2 w-full sm:w-80 bg-[#1e1e1e] border border-white/10 rounded-2xl shadow-2xl z-[60] p-4">
                        <div class="flex items-center justify-between mb-4">
                            <button type="button" @click.stop="prevMonth" class="p-2 hover:bg-white/10 rounded-xl text-gray-300 transition-colors"><PhCaretLeft :size="16" weight="bold" /></button>
                            <span class="text-sm font-bold text-white">{{ monthLabels[displayMonth] }} {{ displayYear }}</span>
                            <button type="button" @click.stop="nextMonth" class="p-2 hover:bg-white/10 rounded-xl text-gray-300 transition-colors"><PhCaretRight :size="16" weight="bold" /></button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            <div v-for="day in weekdayLabels" :key="day" class="text-center text-[10px] font-bold text-gray-500 py-1">{{ day }}</div>
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            <div v-for="(dateObj, i) in calendarDays" :key="i" class="aspect-square">
                                <button
                                    v-if="dateObj"
                                    type="button"
                                    @click.stop="selectDate(dateObj)"
                                    class="w-full h-full flex items-center justify-center text-xs rounded-xl transition-all"
                                    :class="[
                                        isSelectedDate(dateObj) ? 'bg-emerald-500 text-white font-bold shadow-lg shadow-emerald-500/30' : 
                                        isToday(dateObj) ? 'bg-white/10 text-white font-bold border border-white/20' : 
                                        'text-gray-300 hover:bg-white/5'
                                    ]"
                                >
                                    {{ dateObj.getDate() }}
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-white/10 flex justify-end">
                            <button type="button" @click.stop="clearDate('expires')" class="text-xs text-red-400 hover:text-red-300 px-3 py-1.5 font-medium transition-colors">Clear Tanggal</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10">
                <label class="block text-sm font-medium text-gray-300 mb-2">Tautan Verifikasi Online (Opsional)</label>
                <input
                    v-model="form.verification_url"
                    type="url"
                    class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                    placeholder="Contoh: https://credential.net/verify/12345"
                />
                <div v-if="form.errors.verification_url" class="text-xs text-red-400 mt-2">{{ form.errors.verification_url }}</div>
            </div>

            <div class="relative z-10">
                <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi / Detail Kredensial (Opsional)</label>
                <textarea
                    v-model="form.description"
                    rows="3"
                    class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                    placeholder="Ringkasan singkat mengenai kompetensi yang didapat atau topik utama"
                ></textarea>
                <div v-if="form.errors.description" class="text-xs text-red-400 mt-2">{{ form.errors.description }}</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative z-10">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Visibilitas Profil</label>
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-white/10 bg-[#1a1a1a] cursor-pointer hover:bg-white/5 transition-colors">
                            <input type="radio" v-model="form.visibility" value="public" class="w-4 h-4 text-emerald-500 bg-black border-white/20 focus:ring-emerald-500 focus:ring-offset-black">
                            <PhGlobe class="text-emerald-400" />
                            <span class="text-sm text-gray-300">Publik (Ditampilkan ke katalog member)</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-white/10 bg-[#1a1a1a] cursor-pointer hover:bg-white/5 transition-colors">
                            <input type="radio" v-model="form.visibility" value="private" class="w-4 h-4 text-emerald-500 bg-black border-white/20 focus:ring-emerald-500 focus:ring-offset-black">
                            <PhLock class="text-amber-400" />
                            <span class="text-sm text-gray-300">Privat (Hanya untuk verifikasi internal)</span>
                        </label>
                    </div>
                    <div v-if="form.errors.visibility" class="text-xs text-red-400 mt-2">{{ form.errors.visibility }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Unggah Dokumen (JPG/PNG/PDF)</label>
                    <label
                        class="block px-4 py-4 bg-[#1a1a1a] border border-white/10 rounded-xl cursor-pointer transition-colors"
                        :class="isDragActive ? 'border-emerald-500/80 bg-emerald-500/10' : 'hover:border-emerald-500/60'"
                        @dragover.prevent="onDragOver"
                        @dragleave.prevent="onDragLeave"
                        @drop.prevent="onDrop"
                    >
                        <input
                            type="file"
                            class="hidden"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                            @change="onFileChange"
                        />
                        <div class="flex items-center gap-3">
                            <PhUploadSimple class="text-emerald-400" />
                            <div>
                                <p class="text-sm text-gray-300">Pilih dokumen atau seret ke area ini.</p>
                                <p class="text-xs text-gray-500">Maksimal 5MB per file.</p>
                            </div>
                        </div>
                        <div v-if="form.certificate" class="mt-3 flex items-center justify-between rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-3 py-2">
                            <span class="text-xs text-emerald-300 truncate font-medium">{{ form.certificate.name }}</span>
                            <button type="button" @click.stop.prevent="clearFile" class="text-xs text-red-400 hover:text-red-300 ml-2">Hapus</button>
                        </div>
                        <div v-else-if="isEditing && editingAchievement?.certificate_url && !form.certificate" class="mt-3 flex items-center justify-between rounded-lg border border-blue-500/30 bg-blue-500/10 px-3 py-2">
                            <span class="text-xs text-blue-300 font-medium">📄 File sertifikat saat ini sudah ada. Upload baru untuk mengganti.</span>
                        </div>
                    </label>
                    <div v-if="form.errors.certificate" class="text-xs text-red-400 mt-2">{{ form.errors.certificate }}</div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-white/5 mt-6 relative z-10">
                <button
                    type="submit"
                    :disabled="form.processing || !canAdd"
                    class="px-6 py-3 mt-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all disabled:opacity-50"
                >
                    {{ isEditing ? 'Simpan Perubahan' : 'Tambahkan Kredensial' }}
                </button>
                <button
                    v-if="isEditing"
                    type="button"
                    @click="resetForm"
                    class="px-5 py-3 mt-4 bg-white/10 hover:bg-white/15 text-gray-200 font-medium rounded-xl transition-all"
                >
                    Batal Edit
                </button>
            </div>
        </form>

        <div class="space-y-4">
            <div v-if="achievements.length === 0" class="py-12 text-center text-gray-500 border border-dashed border-white/10 rounded-3xl bg-[#121212]/30">
                Data kredensial atau sertifikasi Anda masih kosong.
            </div>

            <div
                v-for="achievement in achievements"
                :key="achievement.id"
                class="bg-[#121212]/70 border border-white/10 rounded-3xl p-6 space-y-4"
            >
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-bold bg-white/10 text-gray-300">
                                {{ getCategoryLabel(achievement.category) }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-white leading-tight">{{ achievement.title }}</h3>
                        <p class="text-sm text-gray-400 font-medium">{{ achievement.issuer }}</p>
                        
                        <div class="grid grid-cols-2 gap-x-6 gap-y-1 pt-2">
                            <p class="text-xs text-gray-500">Tanggal Perolehan: <span class="text-gray-300">{{ new Date(achievement.achieved_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span></p>
                            <p v-if="achievement.expires_at" class="text-xs text-gray-500">Masa Berlaku: <span class="text-gray-300">{{ new Date(achievement.expires_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span></p>
                            <p v-else class="text-xs text-gray-500">Masa Berlaku: <span class="text-gray-300">Selamanya</span></p>
                            
                            <p v-if="achievement.credential_id" class="text-xs text-gray-500 mt-1">ID Kredensial: <span class="font-mono text-gray-300">{{ achievement.credential_id }}</span></p>
                            <a v-if="achievement.verification_url" :href="achievement.verification_url" target="_blank" rel="noopener" class="text-xs text-blue-400 hover:text-blue-300 mt-1 inline-flex items-center gap-1">
                                Cek Verifikasi Online &nearr;
                            </a>
                        </div>

                        <p v-if="achievement.description" class="text-sm text-gray-400 mt-3">{{ achievement.description }}</p>

                        <div class="flex items-center gap-2 pt-3">
                            <span :class="['inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border', statusClass(achievement.status)]">
                                <component :is="statusIcon(achievement.status)" weight="bold" />
                                {{ statusLabel(achievement.status) }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border border-white/10 text-gray-400 bg-black/20">
                                {{ achievement.visibility === 'public' ? 'Publik' : 'Privat' }}
                            </span>
                        </div>

                        <!-- Reject Reason -->
                        <div v-if="achievement.status === 'rejected' && achievement.reject_reason" class="flex items-start gap-2 p-3 rounded-xl bg-red-500/10 border border-red-500/20 mt-2">
                            <PhWarning class="text-red-400 shrink-0 mt-0.5" :size="16" weight="bold" />
                            <div>
                                <p class="text-[10px] text-red-400 font-bold uppercase tracking-wider mb-0.5">Alasan Penolakan</p>
                                <p class="text-xs text-red-300">{{ achievement.reject_reason }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <button
                            type="button"
                            @click="startEdit(achievement)"
                            class="px-3 py-2 rounded-xl text-xs font-bold bg-white/5 text-gray-300 border border-white/10 hover:bg-white/10 transition-colors"
                        >
                            <PhPencilSimple :size="14" weight="bold" class="inline mr-1" /> Edit
                        </button>
                        <button
                            v-if="pendingDeleteId !== achievement.id"
                            type="button"
                            @click="requestDelete(achievement.id)"
                            class="px-3 py-2 rounded-xl text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/30 hover:bg-red-500/20 transition-colors"
                        >
                            <PhTrash :size="14" weight="bold" class="inline mr-1" /> Hapus
                        </button>
                        <div v-else class="flex items-center gap-2 p-1 rounded-xl bg-red-500/10 border border-red-500/30">
                            <button
                                type="button"
                                @click="confirmDelete(achievement.id)"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold bg-red-600 hover:bg-red-500 text-white transition-colors"
                            >
                                Yakin
                            </button>
                            <button
                                type="button"
                                @click="cancelDelete"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white/5 text-gray-300 hover:bg-white/10 transition-colors"
                            >
                                Batal
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="achievement.certificate_url" class="space-y-3 pt-2">
                    <div v-if="isImage(achievement)" class="rounded-2xl overflow-hidden border border-white/10 bg-black/50">
                        <img :src="achievement.certificate_url" alt="Dokumen Kredensial" class="w-full h-auto max-h-72 object-contain" />
                    </div>
                    <div v-else-if="isPdf(achievement)" class="rounded-2xl overflow-hidden border border-white/10">
                        <iframe :src="achievement.certificate_url" class="w-full h-72 bg-white" loading="lazy"></iframe>
                    </div>
                    <div v-else class="text-sm text-gray-400 flex items-center gap-2 bg-white/5 p-3 rounded-xl border border-white/10">
                        <span>📄 File sertifikat berhasil diunggah.</span>
                        <a :href="achievement.certificate_url" target="_blank" class="text-emerald-400 hover:text-emerald-300 font-semibold underline">Unduh/Lihat</a>
                    </div>
                </div>
                <div v-else class="text-xs text-gray-600 italic pt-2">
                    Tidak ada lampiran dokumen untuk kredensial ini.
                </div>
            </div>
        </div>
    </section>
</template>
