<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-xl border-white/10 bg-white/5 text-white focus:border-gym-red focus:ring-gym-red shadow-sm placeholder-slate-500 w-full p-3"
        v-model="model"
        ref="input"
    />
</template>
