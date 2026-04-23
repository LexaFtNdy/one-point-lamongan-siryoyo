<script setup>
import { useForm } from '@inertiajs/vue3';
import { PhRuler, PhBarbell, PhTarget, PhHeartbeat, PhFloppyDisk } from '@phosphor-icons/vue';
import { toast } from 'vue-sonner';

const props = defineProps({
    profile: Object
});

const form = useForm({
    height: props.profile?.height || '',
    weight: props.profile?.weight || '',
    injury_history: props.profile?.injury_history || '',
});

const submit = () => {
    form.post(route('member.profile.update'), {
        preserveScroll: true,
        onSuccess: () => toast.success('Profil Kebugaran berhasil diperbarui.'),
        onError: () => toast.error('Terjadi kesalahan saat menyimpan data.')
    });
};
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-xl font-bold text-white mb-2">Profil Fisik & Rekam Medis</h2>
            <p class="text-sm text-gray-400">
                Data fisik ini bersifat statis dan akan digunakan Algoritma kami setiap kali Anda mencari Personal Trainer.
            </p>
        </header>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Body Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300 flex items-center gap-2"><PhRuler class="text-blue-400" /> Tinggi Badan (cm)</label>
                    <input 
                        v-model="form.height" 
                        type="number" 
                        class="w-full bg-[#121212] border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gym-red focus:ring-1 focus:ring-gym-red outline-none transition-all [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                        placeholder="Misal: 175"
                    />
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300 flex items-center gap-2"><PhBarbell class="text-orange-400" /> Berat Badan (kg)</label>
                    <input 
                        v-model="form.weight" 
                        type="number" 
                        class="w-full bg-[#121212] border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gym-red focus:ring-1 focus:ring-gym-red outline-none transition-all [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                        placeholder="Misal: 70"
                    />
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-300">Riwayat Cedera Khusus (Optional)</label>
                <textarea 
                    v-model="form.injury_history"
                    rows="3"
                    class="w-full bg-[#121212] border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gym-red focus:ring-1 focus:ring-gym-red outline-none transition-all"
                    placeholder="Ketik 'Tidak ada' jika Anda sehat walafiat. Contoh: Cedera lutut kiri, nyeri punggung bawah..."
                ></textarea>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-white/10">
                <button type="submit" :disabled="form.processing" class="flex items-center gap-2 px-6 py-3 bg-gym-red hover:bg-red-700 text-white font-bold rounded-xl transition-all">
                    <PhFloppyDisk weight="bold" /> Simpan Profil
                </button>
            </div>
        </form>
    </section>
</template>
