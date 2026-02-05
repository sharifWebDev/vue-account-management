<!-- components/common/BaseFileUpload.vue -->
<template>
    <div class="w-full">
        <!-- Label -->
        <label v-if="label" class="block text-sm font-medium mb-1" :class="labelClasses">
            {{ label }}
            <span v-if="required" class="text-red-500 ml-0.5">*</span>
        </label>

        <!-- Main Upload Area -->
        <div @dragover="onDragOver" @dragleave="onDragLeave" @drop="onDrop" @click="triggerFileInput" :class="[
            'border-2 border-dashed rounded-lg transition-all duration-200 cursor-pointer',
            'hover:border-gray-400 dark:hover:border-gray-500',
            dragOver ? 'border-gray-400 dark:border-gray-500 bg-gray-50 dark:bg-gray-800/50' : 'border-gray-300 dark:border-gray-700',
            error ? 'border-red-500 dark:border-red-500 hover:border-red-600' : '',
            disabled ? 'cursor-not-allowed opacity-60 pointer-events-none' : ''
        ]">
            <!-- Preview Container -->
            <div v-if="hasPreview" class="p-4">
                <!-- Multiple Files Preview -->
                <div v-if="multiple && previewFiles.length > 0" class="space-y-3">
                    <!-- Files List -->
                    <div v-for="(file, index) in previewFiles" :key="getFileKey(file, index)"
                        class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <!-- File Info -->
                        <div class="flex items-center space-x-3">
                            <!-- File Icon/Preview -->
                            <div v-if="isImageFile(file)" class="relative">
                                <img :src="getFilePreview(file)" :alt="file.name"
                                    class="h-12 w-12 rounded object-cover">
                            </div>
                            <div v-else
                                class="h-12 w-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded">
                                <i :class="getFileIcon(file.type)" class="text-xl text-gray-400"></i>
                            </div>

                            <!-- File Details -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ file.name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ formatFileSize(file.size) }}
                                </p>
                            </div>
                        </div>

                        <!-- Remove Button -->
                        <button v-if="!disabled && !readonly" type="button" @click.stop="removeFile(index)"
                            class="ml-2 p-1 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                            title="Remove file">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Add More Files Button -->
                    <button v-if="!disabled && !readonly" type="button" @click.stop="triggerFileInput"
                        class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add More Files</span>
                    </button>
                </div>

                <!-- Single File Preview -->
                <div v-else-if="!multiple && previewUrl" class="flex flex-col items-center justify-center space-y-4">
                    <!-- Image Preview -->
                    <div v-if="isImageFile(previewFile)" class="relative">
                        <img :src="previewUrl" :alt="previewFile?.name || 'Preview'"
                            class="max-h-48 rounded-lg object-contain">
                        <!-- Remove Button -->
                        <button v-if="!disabled && !readonly" type="button" @click.stop="clearFiles"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 transition-colors shadow-lg"
                            title="Remove image">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>

                    <!-- Non-Image File Preview -->
                    <div v-else
                        class="flex items-center justify-between w-full p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div
                                class="h-12 w-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded">
                                <i :class="getFileIcon(previewFile.type)" class="text-xl text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ previewFile?.name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ formatFileSize(previewFile?.size) }}
                                </p>
                            </div>
                        </div>
                        <button v-if="!disabled && !readonly" type="button" @click.stop="clearFiles"
                            class="ml-2 p-1 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                            title="Remove file">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Upload Prompt (No Files) -->
                <div v-else class="p-6 text-center">
                    <div
                        class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ uploadText }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                        {{ acceptText }}
                        <span v-if="maxSize">(Max: {{ formatFileSize(maxSize) }})</span>
                    </p>
                    <button v-if="!disabled && !readonly" type="button" @click.stop="triggerFileInput"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-upload mr-2"></i>
                        {{ buttonText }}
                    </button>
                </div>
            </div>

            <input :id="inputId" ref="fileInput" type="file" :name="name" :accept="accept" :multiple="multiple"
                :disabled="disabled || readonly" @change="onFileChange" class="hidden" />
        </div>
    </div>

    <!-- Helper Text -->
    <p v-if="helperText && !error" class="text-xs mt-1" :class="helperTextClasses">
        {{ helperText }}
    </p>

    <!-- Error Message -->
    <p v-if="error" class="text-xs mt-1 text-red-600 dark:text-red-400">
        {{ errorMessage || error }}
    </p>

    <!-- File List (for form submission) -->
    <div v-if="multiple && modelValue.length > 0" class="mt-2 space-y-1">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Selected {{ modelValue.length }} file(s)
        </p>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
    // Basic Props
    modelValue: {
        type: [Array, File, null],
        default: null
    },
    label: {
        type: String,
        default: ''
    },
    name: {
        type: String,
        default: ''
    },

    // Configuration
    accept: {
        type: String,
        default: '*/*'
    },
    multiple: {
        type: Boolean,
        default: false
    },
    maxSize: {
        type: Number,
        default: null // in bytes
    },
    maxFiles: {
        type: Number,
        default: null
    },

    // Validation & State
    required: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    readonly: {
        type: Boolean,
        default: false
    },
    error: {
        type: [Boolean, String],
        default: false
    },
    errorMessage: {
        type: String,
        default: ''
    },
    helperText: {
        type: String,
        default: ''
    },

    // Custom Texts
    uploadText: {
        type: String,
        default: 'Drag & drop files here or click to browse'
    },
    buttonText: {
        type: String,
        default: 'Browse Files'
    },
    acceptText: {
        type: String,
        default: ''
    },

    // Custom Classes
    labelClasses: {
        type: String,
        default: ''
    },
    helperTextClasses: {
        type: String,
        default: ''
    }
});

const emit = defineEmits([
    'update:modelValue',
    'change',
    'upload',
    'remove',
    'error',
    'clear'
]);

// Refs
const fileInput = ref(null);
const dragOver = ref(false);
const previewUrl = ref(null);
const previewFiles = ref([]);
const previewFile = ref(null);

// Computed properties
const hasPreview = computed(() => {
    return (props.multiple && previewFiles.value.length > 0) ||
        (!props.multiple && previewUrl.value);
});

const inputId = computed(() => {
    return props.name || `file-upload-${Math.random().toString(36).substr(2, 9)}`;
});

// Helper methods
const triggerFileInput = () => {
    if (!props.disabled && !props.readonly && fileInput.value) {
        fileInput.value.click();
    }
};

const handleUpdate = (value) => {
    emit('update:modelValue', value);
};

const onFileChange = (event) => {
    const files = Array.from(event.target.files);

    if (files.length === 0) return;

    // Validate file count
    if (props.maxFiles && files.length > props.maxFiles) {
        emit('error', `Maximum ${props.maxFiles} files allowed`);
        return;
    }

    if (props.multiple) {
        const currentFiles = Array.isArray(props.modelValue) ? props.modelValue : [];
        const newFiles = [...currentFiles, ...validFiles];
        handleUpdate(newFiles); // Use handleUpdate instead of direct emit
    } else {
        const file = validFiles[0];
        handleUpdate(file);
    }

    // Process each file
    const validFiles = [];

    for (const file of files) {
        // Validate file size
        if (props.maxSize && file.size > props.maxSize) {
            emit('error', `File "${file.name}" exceeds maximum size of ${formatFileSize(props.maxSize)}`);
            continue;
        }

        // Validate file type
        if (!isFileTypeAccepted(file)) {
            emit('error', `File "${file.name}" type not accepted`);
            continue;
        }

        validFiles.push(file);
    }

    if (validFiles.length === 0) return;

    // Update model value
    if (props.multiple) {
        const currentFiles = Array.isArray(props.modelValue) ? props.modelValue : [];
        const newFiles = [...currentFiles, ...validFiles];
        emit('update:modelValue', newFiles);
        updatePreviews(newFiles);
    } else {
        const file = validFiles[0];
        emit('update:modelValue', file);
        updatePreviews([file]);
    }

    emit('upload', validFiles);
    emit('change', event);

    // Reset input
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const onDragOver = (event) => {
    event.preventDefault();
    if (!props.disabled && !props.readonly) {
        dragOver.value = true;
    }
};

const onDragLeave = () => {
    dragOver.value = false;
};

const onDrop = (event) => {
    event.preventDefault();
    dragOver.value = false;

    if (props.disabled || props.readonly) return;

    const files = Array.from(event.dataTransfer.files);
    if (files.length > 0) {
        // Create a mock event object for onFileChange
        const mockEvent = {
            target: {
                files: event.dataTransfer.files
            }
        };
        onFileChange(mockEvent);
    }
};

const removeFile = (index) => {
    if (props.multiple) {
        const files = [...props.modelValue];
        const removedFile = files.splice(index, 1)[0];
        emit('update:modelValue', files);
        updatePreviews(files);
        emit('remove', removedFile);
    } else {
        clearFiles();
    }
};

const clearFiles = () => {
    if (props.multiple) {
        emit('update:modelValue', []);
        previewFiles.value = [];
    } else {
        emit('update:modelValue', null);
        previewUrl.value = null;
        previewFile.value = null;
    }
    emit('clear');
};

const updatePreviews = (files) => {
    if (!files || files.length === 0) {
        previewUrl.value = null;
        previewFiles.value = [];
        previewFile.value = null;
        return;
    }

    if (props.multiple) {
        previewFiles.value = files;
    } else {
        const file = files[0];
        previewFile.value = file;

        if (isImageFile(file)) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewUrl.value = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
};

const getFilePreview = (file) => {
    if (file instanceof File && isImageFile(file)) {
        return URL.createObjectURL(file);
    }
    return file;
};

const isImageFile = (file) => {
    if (!file) return false;
    return file.type.startsWith('image/');
};

const getFileIcon = (mimeType) => {
    if (!mimeType) return 'fas fa-file';

    const iconMap = {
        // Documents
        'application/pdf': 'fas fa-file-pdf',
        'application/msword': 'fas fa-file-word',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fas fa-file-word',
        'application/vnd.ms-excel': 'fas fa-file-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'fas fa-file-excel',
        'application/vnd.ms-powerpoint': 'fas fa-file-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': 'fas fa-file-powerpoint',
        'text/plain': 'fas fa-file-alt',
        'text/csv': 'fas fa-file-csv',

        // Archives
        'application/zip': 'fas fa-file-archive',
        'application/x-rar-compressed': 'fas fa-file-archive',
        'application/x-7z-compressed': 'fas fa-file-archive',

        // Default
        'default': 'fas fa-file'
    };

    return iconMap[mimeType] || iconMap['default'];
};

const formatFileSize = (bytes) => {
    if (!bytes) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const isFileTypeAccepted = (file) => {
    if (props.accept === '*/*') return true;

    const acceptTypes = props.accept.split(',').map(type => type.trim());

    return acceptTypes.some(type => {
        if (type.startsWith('.')) {
            // File extension
            const extension = type.toLowerCase();
            return file.name.toLowerCase().endsWith(extension);
        } else {
            // MIME type
            return file.type.match(new RegExp(type.replace('*', '.*')));
        }
    });
};

const getFileKey = (file, index) => {
    return file.name + '-' + file.size + '-' + index;
};

// Watch for model value changes
watch(() => props.modelValue, (newValue) => {
    if (newValue) {
        if (props.multiple) {
            updatePreviews(Array.isArray(newValue) ? newValue : [newValue]);
        } else {
            updatePreviews(newValue ? [newValue] : []);
        }
    } else {
        updatePreviews([]);
    }
}, { immediate: true });

// Cleanup
const cleanup = () => {
    if (previewUrl.value && previewFile.value && isImageFile(previewFile.value)) {
        URL.revokeObjectURL(previewUrl.value);
    }
};

onMounted(() => {
    // Initialize preview if modelValue exists
    if (props.modelValue) {
        updatePreviews(props.multiple ? props.modelValue : [props.modelValue]);
    }
});

// Cleanup on unmount
onUnmounted(() => {
    cleanup();
});
</script>