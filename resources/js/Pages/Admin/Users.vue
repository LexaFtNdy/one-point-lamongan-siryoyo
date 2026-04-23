<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { PhUsers, PhUser, PhBarbell, PhTrash, PhArrowCounterClockwise, PhShieldCheck, PhWarning, PhX } from '@phosphor-icons/vue';
import MemberLayout from '@/Layouts/MemberLayout.vue';
import { ref } from 'vue';

defineOptions({ layout: MemberLayout });

const props = defineProps({
    users: { type: Array, default: () => [] }
});

const confirmModal = ref(false);
const targetUser = ref(null);
const isProcessing = ref(false);

const openConfirm = (user) => {
    targetUser.value = user;
    confirmModal.value = true;
};

const executeAction = () => {
    if (!targetUser.value) return;
    isProcessing.value = true;
    router.patch(route('admin.suspend-user', targetUser.value.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            isProcessing.value = false;
            confirmModal.value = false;
            targetUser.value = null;
        }
    });
};
</script>

<template>
    <Head title="Kelola Pengguna - Admin" />

    <div class="max-w-6xl mx-auto pb-20">
        <div class="mb-10">
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight mb-2 flex items-center gap-3">
                <PhUsers class="text-blue-400" />
                Kelola <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">Pengguna.</span>
            </h1>
            <p class="text-gray-400">Lihat dan kelola semua akun Member dan Personal Trainer yang terdaftar.</p>
        </div>

        <div class="bg-[#121212]/80 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama</th>
                            <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
                            <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gender</th>
                            <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="pb-4 pt-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr v-for="user in users" :key="user.id" :class="['hover:bg-white/5 transition-colors', user.deleted_at ? 'opacity-50' : '']">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-full flex items-center justify-center', user.role === 'trainer' ? 'bg-orange-500/20 text-orange-400' : 'bg-blue-500/20 text-blue-400']">
                                        <component :is="user.role === 'trainer' ? PhBarbell : PhUser" weight="fill" :size="18" />
                                    </div>
                                    <span class="text-sm font-bold text-white">{{ user.name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-400">{{ user.email }}</td>
                            <td class="py-4 px-4">
                                <span :class="['px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider', user.role === 'trainer' ? 'bg-orange-500/10 text-orange-400 border border-orange-500/30' : 'bg-blue-500/10 text-blue-400 border border-blue-500/30']">
                                    {{ user.role }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-400 capitalize">{{ user.gender === 'no_preference' ? '-' : user.gender }}</td>
                            <td class="py-4 px-4">
                                <span v-if="user.deleted_at" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/30">
                                    Suspended
                                </span>
                                <span v-else class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/30">
                                    <PhShieldCheck :size="12" weight="bold" /> Aktif
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <button
                                    @click="openConfirm(user)"
                                    :class="['inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all', user.deleted_at ? 'bg-green-500/10 text-green-400 hover:bg-green-500/20 border border-green-500/30' : 'bg-red-500/10 text-red-400 hover:bg-red-500/20 border border-red-500/30']"
                                >
                                    <component :is="user.deleted_at ? PhArrowCounterClockwise : PhTrash" :size="14" weight="bold" />
                                    {{ user.deleted_at ? 'Aktifkan' : 'Suspend' }}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Custom Confirm Modal -->
    <div v-if="confirmModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="confirmModal = false"></div>
        <div class="relative bg-[#121212] border border-white/10 w-full max-w-sm rounded-3xl shadow-2xl p-8 overflow-hidden">
            <button @click="confirmModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors bg-white/5 p-2 rounded-full">
                <PhX :size="16" weight="bold" />
            </button>

            <div class="flex flex-col items-center text-center">
                <div :class="['w-16 h-16 rounded-full flex items-center justify-center mb-5', targetUser?.deleted_at ? 'bg-green-500/10 border border-green-500/30' : 'bg-red-500/10 border border-red-500/30']">
                    <PhWarning :size="32" :class="targetUser?.deleted_at ? 'text-green-400' : 'text-red-400'" weight="fill" />
                </div>

                <h3 class="text-xl font-bold text-white mb-2">
                    {{ targetUser?.deleted_at ? 'Aktifkan Kembali?' : 'Suspend Akun?' }}
                </h3>
                <p class="text-sm text-gray-400 mb-6">
                    Apakah Anda yakin ingin {{ targetUser?.deleted_at ? 'mengaktifkan kembali' : 'men-suspend' }} akun
                    <strong class="text-white">{{ targetUser?.name }}</strong>?
                </p>

                <div class="flex gap-3 w-full">
                    <button @click="confirmModal = false" class="flex-1 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 font-bold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button
                        @click="executeAction"
                        :disabled="isProcessing"
                        :class="['flex-1 py-3 font-bold rounded-xl transition-colors disabled:opacity-50', targetUser?.deleted_at ? 'bg-green-600 hover:bg-green-500 text-white' : 'bg-red-600 hover:bg-red-500 text-white']"
                    >
                        {{ isProcessing ? 'Memproses...' : (targetUser?.deleted_at ? 'Ya, Aktifkan' : 'Ya, Suspend') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
