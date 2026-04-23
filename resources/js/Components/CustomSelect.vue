<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { PhCaretDown, PhCheck } from '@phosphor-icons/vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        required: true
    },
    options: {
        type: Array,
        required: true
    },
    placeholder: {
        type: String,
        default: 'Pilih salah satu...'
    }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const dropdownRef = ref(null);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const selectOption = (option) => {
    emit('update:modelValue', option.value);
    isOpen.value = false;
};

const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
});

// Helper to get selected label
const getSelectedLabel = () => {
    const selected = props.options.find(opt => opt.value === props.modelValue);
    return selected ? selected.label : props.placeholder;
};
</script>

<template>
    <div class="relative w-full" ref="dropdownRef">
        <!-- Select Button -->
        <button 
            type="button"
            @click="toggleDropdown"
            class="w-full flex items-center justify-between bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gym-red focus:ring-1 focus:ring-gym-red transition-all duration-300 shadow-inner"
            :class="isOpen ? 'border-gym-red ring-1 ring-gym-red bg-[#222222]' : 'hover:border-white/20 hover:bg-[#222222]'"
        >
            <span class="block truncate font-medium text-[15px]">{{ getSelectedLabel() }}</span>
            <PhCaretDown :size="16" class="text-gray-400 transition-transform duration-300" :class="isOpen ? 'rotate-180 text-gym-red' : ''" />
        </button>

        <!-- Dropdown Menu -->
        <transition 
            enter-active-class="transition duration-200 ease-out" 
            enter-from-class="transform scale-95 opacity-0" 
            enter-to-class="transform scale-100 opacity-100" 
            leave-active-class="transition duration-100 ease-in" 
            leave-from-class="transform scale-100 opacity-100" 
            leave-to-class="transform scale-95 opacity-0"
        >
            <div 
                v-if="isOpen" 
                class="absolute z-50 w-full mt-2 bg-[#121212]/95 border border-white/10 rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.8)] overflow-hidden backdrop-blur-2xl"
            >
                <ul class="max-h-60 overflow-y-auto py-2 custom-scrollbar">
                    <li 
                        v-for="option in options" 
                        :key="option.value"
                        @click="selectOption(option)"
                        class="relative cursor-pointer select-none py-3 pl-12 pr-4 text-gray-300 hover:bg-white/5 hover:text-white transition-colors"
                        :class="modelValue === option.value ? 'bg-gym-red/10 text-gym-red font-bold' : ''"
                    >
                        <span class="block truncate">{{ option.label }}</span>
                        
                        <span v-if="modelValue === option.value" class="absolute inset-y-0 left-0 flex items-center pl-4 text-gym-red">
                            <PhCheck :size="18" weight="bold" />
                        </span>
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, 0.3);
}
</style>
