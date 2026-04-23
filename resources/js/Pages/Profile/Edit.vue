<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import MemberFitnessForm from './Partials/MemberFitnessForm.vue';
import TrainerParameterForm from './Partials/TrainerParameterForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { PhUserCircle, PhGear, PhHeartbeat, PhSlidersHorizontal } from '@phosphor-icons/vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    userProfile: {
        type: Object,
        default: null,
    },
    tab: {
        type: String,
        default: 'account'
    }
});
</script>

<template>
    <Head title="Pengaturan Profil" />

    <MemberLayout>
        <div class="max-w-6xl mx-auto pb-20">
            <!-- Header Settings -->
            <div class="mb-10">
                <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight mb-2">
                    Pengaturan <span class="text-transparent bg-clip-text bg-gradient-to-r from-gray-400 to-gray-100">Profil.</span>
                </h1>
                <p class="text-gray-400">Kelola informasi akun dan parameter persona Anda.</p>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar / Tabs -->
                <div class="w-full md:w-64 flex-shrink-0">
                    <nav class="flex md:flex-col gap-2 overflow-x-auto pb-4 md:pb-0 scrollbar-hide">
                        <Link 
                            :href="route('profile.edit', { tab: 'account' })"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all whitespace-nowrap"
                            :class="tab === 'account' ? 'bg-white/10 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5'"
                        >
                            <PhUserCircle :size="20" :weight="tab === 'account' ? 'fill' : 'regular'" />
                            Informasi Akun
                        </Link>
                        
                        <Link 
                            v-if="$page.props.auth.user.role === 'member'"
                            :href="route('profile.edit', { tab: 'kebugaran' })"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all whitespace-nowrap"
                            :class="tab === 'kebugaran' ? 'bg-gym-red/20 text-gym-red shadow-[inset_0_1px_0_rgba(214,0,0,0.2)]' : 'text-gray-400 hover:text-white hover:bg-white/5'"
                        >
                            <PhHeartbeat :size="20" :weight="tab === 'kebugaran' ? 'fill' : 'regular'" />
                            Profil Kebugaran
                        </Link>

                        <Link 
                            v-if="$page.props.auth.user.role === 'trainer'"
                            :href="route('profile.edit', { tab: 'parameter' })"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all whitespace-nowrap"
                            :class="tab === 'parameter' ? 'bg-blue-500/20 text-blue-400 shadow-[inset_0_1px_0_rgba(59,130,246,0.2)]' : 'text-gray-400 hover:text-white hover:bg-white/5'"
                        >
                            <PhSlidersHorizontal :size="20" :weight="tab === 'parameter' ? 'fill' : 'regular'" />
                            Parameter PT
                        </Link>

                        <div class="w-px md:w-full md:h-px bg-white/10 my-2 mx-2 md:mx-0 shrink-0"></div>

                        <Link 
                            :href="route('profile.edit', { tab: 'security' })"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all whitespace-nowrap"
                            :class="tab === 'security' ? 'bg-white/10 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-white/5'"
                        >
                            <PhGear :size="20" :weight="tab === 'security' ? 'fill' : 'regular'" />
                            Keamanan
                        </Link>
                    </nav>
                </div>

                <!-- Main Content Area -->
                <div class="flex-1 bg-[#121212]/80 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 md:p-10 shadow-2xl">
                    
                    <!-- TAB: ACCOUNT -->
                    <div v-if="tab === 'account'" class="animate-fade-in">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </div>

                    <!-- TAB: MEMBER FITNESS -->
                    <div v-if="tab === 'kebugaran' && $page.props.auth.user.role === 'member'" class="animate-fade-in">
                        <MemberFitnessForm :profile="userProfile" />
                    </div>

                    <!-- TAB: TRAINER PARAMETER -->
                    <div v-if="tab === 'parameter' && $page.props.auth.user.role === 'trainer'" class="animate-fade-in">
                        <TrainerParameterForm :profile="userProfile" />
                    </div>

                    <!-- TAB: SECURITY (Password & Delete) -->
                    <div v-if="tab === 'security'" class="space-y-10 animate-fade-in">
                        <UpdatePasswordForm />
                        <hr class="border-white/10" />
                        <DeleteUserForm />
                    </div>

                </div>
            </div>
        </div>
    </MemberLayout>
</template>

<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.animate-fade-in {
    animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
