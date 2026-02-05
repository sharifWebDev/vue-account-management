<!-- components/common/ConfirmationModal.vue -->
<template>
    <BaseModal :show="show" :title="title" :maxWidth="maxWidth" :showClose="showClose" @close="$emit('close')">
        <div class="px-6 py-3">
            <div class="text-center">
                <!-- Icon -->
                <div :class="iconContainerClasses">
                    <i :class="iconClasses" class="text-xl"></i>
                </div>

                <!-- Title -->
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    {{ title }}
                </h3>

                <!-- Message -->
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    <slot name="message">
                        {{ defaultMessage }}
                    </slot>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-3">
                <!-- Cancel Button -->
                <button v-if="!hideCancel" @click="$emit('close')" :disabled="loading"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    {{ cancelButtonText }}
                </button>

                <!-- Confirm Button -->
                <button @click="$emit('confirm')" :disabled="loading" :class="confirmButtonClasses">
                    <span v-if="loading">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        {{ loadingText }}
                    </span>
                    <span v-else>{{ confirmButtonText }}</span>
                </button>
            </div>
        </div>
    </BaseModal>
</template>

<script setup>
import BaseModal from './BaseModal.vue';
import { computed } from 'vue';

const props = defineProps({
    // Modal visibility
    show: {
        type: Boolean,
        default: false
    },

    // Modal content
    title: {
        type: String,
        default: 'Confirmation'
    },
    type: {
        type: String,
        default: 'danger', // 'danger', 'warning', 'success', 'info'
        validator: (value) => ['danger', 'warning', 'success', 'info'].includes(value)
    },

    // Custom messages
    defaultMessage: {
        type: String,
        default: 'Are you sure you want to proceed?'
    },
    confirmButtonText: {
        type: String,
        default: 'Confirm'
    },
    cancelButtonText: {
        type: String,
        default: 'Cancel'
    },
    loadingText: {
        type: String,
        default: 'Processing...'
    },

    // Behavior
    loading: {
        type: Boolean,
        default: false
    },
    hideCancel: {
        type: Boolean,
        default: false
    },
    showClose: {
        type: Boolean,
        default: false
    },
    maxWidth: {
        type: String,
        default: 'lg'
    }
});

defineEmits(['close', 'confirm']);

// Computed properties for dynamic styling
const iconContainerClasses = computed(() => {
    const base = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4';
    const colors = {
        danger: 'bg-red-100 dark:bg-red-900',
        warning: 'bg-yellow-100 dark:bg-yellow-900',
        success: 'bg-green-100 dark:bg-green-900',
        info: 'bg-blue-100 dark:bg-blue-900'
    };
    return `${base} ${colors[props.type]}`;
});

const iconClasses = computed(() => {
    const colors = {
        danger: 'fas fa-exclamation-triangle text-red-600 dark:text-red-300',
        warning: 'fas fa-exchange-alt text-yellow-600 dark:text-yellow-300',
        success: 'fas fa-check-circle text-green-600 dark:text-green-300',
        info: 'fas fa-info-circle text-blue-600 dark:text-blue-300'
    };
    return colors[props.type];
});

const confirmButtonClasses = computed(() => {
    const base = 'px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed';
    const colors = {
        danger: 'bg-red-600 hover:bg-red-700',
        warning: 'bg-yellow-600 hover:bg-yellow-700',
        success: 'bg-green-600 hover:bg-green-700',
        info: 'bg-blue-600 hover:bg-blue-700'
    };
    return `${base} ${colors[props.type]}`;
});
</script>