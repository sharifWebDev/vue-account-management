<!-- components/common/BaseInput.vue -->
<template>
    <div class="w-full">
        <!-- Label -->
        <label v-if="label" :for="inputId" class="block text-sm font-medium mb-1" :class="labelClasses">
            {{ label }}
            <span v-if="required" class="text-red-500 ml-0.5">*</span>
        </label>

        <!-- Input Container - Different layouts for different types -->
        <div class="relative">
            <!-- ========== TEXT, EMAIL, PASSWORD, NUMBER, TEL, SEARCH, URL, DATE, TIME, DATETIME-LOCAL ========== -->
            <template
                v-if="['text', 'email', 'password', 'number', 'tel', 'search', 'url', 'date', 'time', 'datetime-local'].includes(type)">
                <!-- Prefix Icon/Content -->
                <div v-if="prefixIcon || $slots.prefix" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    <slot name="prefix">
                        <i v-if="prefixIcon" :class="prefixIcon" class="text-gray-400"></i>
                    </slot>
                </div>

                <!-- Input Field -->
                <input :id="inputId" :value="modelValue" :type="internalType" :placeholder="placeholder"
                    :disabled="disabled" :readonly="readonly" :maxlength="maxlength" :min="min" :max="max" :step="step"
                    :autocomplete="autocomplete" :pattern="pattern" :class="[
                        'w-full px-3 py-2 border rounded-lg transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-offset-1',
                        inputSizeClass,
                        error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-gray-500 focus:ring-gray-500',
                        disabled ? 'bg-gray-100 dark:bg-gray-800 text-gray-500 cursor-not-allowed' : 'bg-white dark:bg-gray-700 dark:text-white',
                        (prefixIcon || $slots.prefix) ? 'pl-10' : '',
                        hasSuffix ? 'pr-10' : ''
                    ]" @input="handleInput" @change="handleChange" @blur="handleBlur" @focus="handleFocus"
                    @keyup="$emit('keyup', $event)" @keydown="$emit('keydown', $event)" />

                <!-- Password Toggle -->
                <button v-if="type === 'password' && showPasswordToggle" type="button" @click="togglePassword"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
                    :title="showPassword ? 'Hide password' : 'Show password'">
                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>

                <!-- Clear Button -->
                <button v-else-if="showClear && modelValue" type="button" @click="clearInput"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>

                <!-- Suffix Icon -->
                <div v-else-if="suffixIcon" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <i :class="suffixIcon" class="text-gray-400"></i>
                </div>

                <!-- Character Counter -->
                <div v-if="showCounter && maxlength"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-xs text-gray-400">
                    {{ (modelValue?.toString() || '').length }}/{{ maxlength }}
                </div>
            </template>

            <!-- ========== TEXTAREA ========== -->
            <template v-else-if="type === 'textarea'">
                <textarea :id="inputId" :value="modelValue" :placeholder="placeholder" :disabled="disabled"
                    :readonly="readonly" :maxlength="maxlength" :rows="rows" :cols="cols" :autocomplete="autocomplete"
                    :class="[
                        'w-full px-3 py-2 border rounded-lg transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-offset-1',
                        textareaSizeClass,
                        error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-gray-500 focus:ring-gray-500',
                        disabled ? 'bg-gray-100 dark:bg-gray-800 text-gray-500 cursor-not-allowed' : 'bg-white dark:bg-gray-700 dark:text-white',
                        autoGrow ? 'overflow-hidden' : '',
                        resize ? '' : 'resize-none'
                    ]" :style="autoGrow ? { height: textareaHeight } : {}" @input="handleInput" @change="handleChange"
                    @blur="handleBlur" @focus="handleFocus" @keyup="$emit('keyup', $event)"
                    @keydown="$emit('keydown', $event)" ref="textareaRef"></textarea>

                <!-- Character Counter for Textarea -->
                <div v-if="showCounter && maxlength" class="absolute bottom-2 right-2">
                    <span :class="[
                        'text-xs',
                        (modelValue?.toString() || '').length > maxlength ? 'text-red-500' : 'text-gray-400'
                    ]">
                        {{ (modelValue?.toString() || '').length }}/{{ maxlength }}
                    </span>
                </div>
            </template>

            <!-- ========== RICH TEXT EDITOR ========== -->
            <template v-else-if="type === 'richtext'">
                <!-- Toolbar -->
                <div v-if="!disabled && !readonly"
                    class="mb-2 border border-gray-300 dark:border-gray-600 rounded-t-lg bg-gray-50 dark:bg-gray-800 p-2 flex flex-wrap items-center gap-1">
                    <!-- Formatting Buttons -->
                    <button type="button" @click="formatText('bold')"
                        class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Bold">
                        <i class="fas fa-bold text-sm"></i>
                    </button>
                    <button type="button" @click="formatText('italic')"
                        class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Italic">
                        <i class="fas fa-italic text-sm"></i>
                    </button>
                    <button type="button" @click="formatText('underline')"
                        class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Underline">
                        <i class="fas fa-underline text-sm"></i>
                    </button>

                    <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                    <!-- List Buttons -->
                    <button type="button" @click="formatText('unorderedList')"
                        class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Bullet List">
                        <i class="fas fa-list-ul text-sm"></i>
                    </button>
                    <button type="button" @click="formatText('orderedList')"
                        class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Numbered List">
                        <i class="fas fa-list-ol text-sm"></i>
                    </button>

                    <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                    <!-- Link Button -->
                    <button type="button" @click="insertLink"
                        class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Insert Link">
                        <i class="fas fa-link text-sm"></i>
                    </button>

                    <!-- More Options -->
                    <div class="relative inline-block">
                        <button type="button" @click="showRichTextOptions = !showRichTextOptions"
                            class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="More options">
                            <i class="fas fa-ellipsis-h text-sm"></i>
                        </button>

                        <div v-if="showRichTextOptions"
                            class="absolute right-0 mt-1 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                            <button type="button" @click="formatText('heading1')"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-heading mr-2"></i> Heading 1
                            </button>
                            <button type="button" @click="formatText('heading2')"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-heading mr-2"></i> Heading 2
                            </button>
                            <button type="button" @click="formatText('quote')"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-quote-right mr-2"></i> Quote
                            </button>
                            <button type="button" @click="formatText('code')"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-code mr-2"></i> Code
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Rich Text Editor -->
                <div :class="[
                    'border rounded-lg transition-all duration-200 focus-within:ring-1 focus-within:ring-offset-1',
                    error ? 'border-red-500 focus-within:border-red-500 focus-within:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus-within:border-gray-500 focus-within:ring-gray-500',
                    disabled ? 'bg-gray-100 dark:bg-gray-800' : 'bg-white dark:bg-gray-700',
                    !disabled && !readonly ? 'rounded-t-none' : ''
                ]">
                    <div :id="inputId" :contenteditable="!disabled && !readonly" :class="[
                        'min-h-[150px] max-h-[400px] overflow-y-auto px-3 py-2 outline-none',
                        disabled ? 'text-gray-500 cursor-not-allowed' : 'dark:text-white'
                    ]" @input="handleRichTextInput" @blur="handleBlur" @focus="handleFocus" ref="richTextRef"></div>
                </div>
            </template>

            <!-- ========== RADIO GROUP ========== -->
            <template v-else-if="type === 'radio'">
                <div :class="[
                    'flex',
                    direction === 'horizontal' ? 'items-center space-x-6' : 'flex-col space-y-3',
                    containerClasses
                ]">
                    <label v-for="option in options" :key="option.value" :for="getRadioId(option.value)"
                        class="inline-flex items-center cursor-pointer group" :class="[
                            option.disabled ? 'cursor-not-allowed opacity-60' : '',
                            optionClasses
                        ]">
                        <div class="relative">
                            <input :id="getRadioId(option.value)" :checked="modelValue === option.value"
                                :value="option.value" :name="name" :disabled="option.disabled || disabled"
                                :readonly="readonly" type="radio" class="sr-only"
                                @change="handleRadioChange($event, option.value)" @focus="$emit('focus', $event)"
                                @blur="$emit('blur', $event)">

                            <div class="flex items-center justify-center w-4 h-4 border-2 rounded-full transition-all duration-200"
                                :class="[
                                    getRadioClasses(option.value),
                                    option.disabled || disabled ? 'cursor-not-allowed' : ''
                                ]">
                                <div v-if="modelValue === option.value"
                                    class="w-2 h-2 rounded-full transition-all duration-200"
                                    :class="getRadioInnerClasses(option.value)"></div>
                            </div>
                        </div>

                        <span class="ml-2 select-none" :class="getRadioLabelClasses(option.value)">
                            {{ option.label }}
                        </span>

                        <i v-if="option.icon" :class="['ml-2', option.icon, getRadioIconClasses(option.value)]"></i>

                        <span v-if="option.description" class="ml-2 text-xs"
                            :class="getRadioDescriptionClasses(option.value)">
                            {{ option.description }}
                        </span>
                    </label>
                </div>
            </template>

            <!-- ========== CHECKBOX ========== -->
            <template v-else-if="type === 'checkbox'">
                <label :for="inputId" class="inline-flex items-center cursor-pointer">
                    <div class="relative">
                        <input :id="inputId" :checked="modelValue" type="checkbox" :disabled="disabled"
                            :readonly="readonly" :class="[
                                'sr-only',
                                error ? 'border-red-500' : ''
                            ]" @change="handleCheckboxChange" @focus="$emit('focus', $event)"
                            @blur="$emit('blur', $event)" />

                        <!-- Custom Checkbox -->
                        <div class="flex items-center justify-center w-5 h-5 border-2 rounded transition-all duration-200"
                            :class="[
                                modelValue
                                    ? getCheckboxSelectedClasses()
                                    : getCheckboxUnselectedClasses(),
                                disabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer'
                            ]">
                            <!-- Checkmark -->
                            <svg v-if="modelValue" class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <span v-if="checkboxLabel" class="ml-2 text-sm" :class="[
                        'text-gray-700 dark:text-gray-300',
                        disabled ? 'text-gray-500 dark:text-gray-500' : ''
                    ]">
                        {{ checkboxLabel }}
                    </span>

                    <span v-if="checkboxDescription" class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                        {{ checkboxDescription }}
                    </span>
                </label>
            </template>

            <!-- ========== SELECT/DROPDOWN ========== -->
            <template v-else-if="type === 'select'">
                <div class="relative">
                    <select :id="inputId" :value="modelValue" :disabled="disabled" :readonly="readonly"
                        :multiple="multiple" :class="[
                            'w-full px-3 py-2 border rounded-lg transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-offset-1 appearance-none',
                            inputSizeClass,
                            error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-gray-500 focus:ring-gray-500',
                            disabled ? 'bg-gray-100 dark:bg-gray-800 text-gray-500 cursor-not-allowed' : 'bg-white dark:bg-gray-700 dark:text-white',
                            prefixIcon ? 'pl-10' : ''
                        ]" @change="handleSelectChange" @blur="handleBlur" @focus="handleFocus">
                        <option v-if="placeholder" value="" disabled hidden>{{ placeholder }}</option>
                        <option v-for="option in options" :key="option.value" :value="option.value"
                            :disabled="option.disabled">
                            {{ option.label }}
                        </option>
                    </select>

                    <!-- Dropdown Arrow -->
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>

                    <!-- Prefix Icon for Select -->
                    <div v-if="prefixIcon" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <i :class="prefixIcon" class="text-gray-400"></i>
                    </div>
                </div>
            </template>

            <!-- ========== FILE UPLOAD ========== -->
            <template v-else-if="type === 'file'">
                <div @dragover="onDragOver" @dragleave="onDragLeave" @drop="onDrop" @click="triggerFileInput" :class="[
                    'border-2 border-dashed rounded-lg transition-all duration-200',
                    disabled || readonly ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:border-gray-400 dark:hover:border-gray-500',
                    dragOver ? 'border-gray-400 dark:border-gray-500 bg-gray-50 dark:bg-gray-800/50' : 'border-gray-300 dark:border-gray-700',
                    error ? 'border-red-500 dark:border-red-500' : ''
                ]">
                    <!-- File Preview -->
                    <div v-if="filePreview && !multiple" class="p-4">
                        <div
                            class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div v-if="isImageFile(filePreview)" class="relative">
                                    <img :src="getFilePreview(filePreview)" :alt="filePreview.name"
                                        class="h-12 w-12 rounded object-cover">
                                </div>
                                <div v-else
                                    class="h-12 w-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded">
                                    <i :class="getFileIcon(filePreview.type)" class="text-xl text-gray-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ filePreview.name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ formatFileSize(filePreview.size) }}
                                    </p>
                                </div>
                            </div>
                            <button v-if="!disabled && !readonly" type="button" @click.stop="clearFile"
                                class="ml-2 p-1 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Upload Area -->
                    <div v-else class="p-6 text-center">
                        <div
                            class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ uploadText || 'Drag & drop files here or click to browse' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            {{ acceptText || (accept ? `Accepted: ${accept}` : 'All files') }}
                            <span v-if="maxSize">(Max: {{ formatFileSize(maxSize) }})</span>
                        </p>
                        <button v-if="!disabled && !readonly" type="button" @click.stop="triggerFileInput"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-upload mr-2"></i>
                            {{ buttonText || 'Browse Files' }}
                        </button>
                    </div>

                    <input :id="`${inputId}-file`" ref="fileInput" type="file" :name="name" :accept="accept"
                        :multiple="multiple" :disabled="disabled || readonly" @change="onFileChange" class="hidden" />
                </div>
            </template>

            <!-- ========== COLOR PICKER ========== -->
            <template v-else-if="type === 'color'">
                <div class="flex items-center space-x-3">
                    <input :id="inputId" :value="modelValue" type="color" :disabled="disabled" :readonly="readonly"
                        class="w-12 h-12 cursor-pointer border border-gray-300 dark:border-gray-600 rounded-lg"
                        @input="handleInput" @change="handleChange" @blur="handleBlur" @focus="handleFocus" />
                    <input :id="`${inputId}-hex`" :value="modelValue" type="text"
                        :placeholder="placeholder || '#000000'" :disabled="disabled" :readonly="readonly" :maxlength="7"
                        :pattern="'^#[0-9A-Fa-f]{6}$'" :class="[
                            'flex-1 px-3 py-2 border rounded-lg transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-offset-1',
                            inputSizeClass,
                            error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-gray-500 focus:ring-gray-500',
                            disabled ? 'bg-gray-100 dark:bg-gray-800 text-gray-500 cursor-not-allowed' : 'bg-white dark:bg-gray-700 dark:text-white'
                        ]" @input="handleColorInput" @change="handleChange" @blur="handleBlur" @focus="handleFocus" />
                </div>
            </template>

            <!-- ========== RANGE SLIDER ========== -->
            <template v-else-if="type === 'range'">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <input :id="inputId" :value="modelValue" type="range" :min="min" :max="max" :step="step"
                            :disabled="disabled" :readonly="readonly" :class="[
                                'w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer',
                                disabled ? 'cursor-not-allowed opacity-60' : ''
                            ]" @input="handleInput" @change="handleChange" @blur="handleBlur" @focus="handleFocus" />
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>{{ min }}</span>
                        <span class="font-medium">{{ modelValue }}</span>
                        <span>{{ max }}</span>
                    </div>
                </div>
            </template>

            <!-- ========== UNSUPPORTED TYPE ========== -->
            <template v-else>
                <div class="p-3 border border-red-300 dark:border-red-700 rounded-lg bg-red-50 dark:bg-red-900/20">
                    <p class="text-sm text-red-600 dark:text-red-400">
                        Unsupported input type: <strong>{{ type }}</strong>
                    </p>
                    <p class="text-xs text-red-500 dark:text-red-300 mt-1">
                        Supported types: text, email, password, number, tel, search, url, date, time,
                        datetime-local, textarea, richtext, radio, checkbox, select, file, color, range
                    </p>
                </div>
            </template>
        </div>

        <!-- Helper Text -->
        <p v-if="helperText && !error" class="text-xs mt-1" :class="helperTextClasses">
            {{ helperText }}
        </p>

        <!-- Error Message -->
        <p v-if="error" class="text-xs mt-1 text-red-600 dark:text-red-400">
            {{ errorMessage || error }}
        </p>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, nextTick } from 'vue';

const props = defineProps({
    // ========== BASIC PROPS ==========
    modelValue: {
        type: [String, Number, Boolean, Array, File, Object, null],
        default: null
    },
    type: {
        type: String,
        default: 'text',
        validator: (value) => [
            'text', 'email', 'password', 'number', 'tel', 'search', 'url',
            'date', 'time', 'datetime-local', 'month', 'week',
            'textarea', 'richtext',
            'radio', 'checkbox',
            'select',
            'file',
            'color',
            'range'
        ].includes(value)
    },
    label: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: ''
    },
    id: {
        type: String,
        default: ''
    },
    name: {
        type: String,
        default: ''
    },

    // ========== VALIDATION & STATE ==========
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

    // ========== COMMON ATTRIBUTES ==========
    maxlength: {
        type: [Number, String],
        default: null
    },
    min: {
        type: [Number, String],
        default: null
    },
    max: {
        type: [Number, String],
        default: null
    },
    step: {
        type: [Number, String],
        default: null
    },
    rows: {
        type: [Number, String],
        default: 3
    },
    cols: {
        type: [Number, String],
        default: null
    },
    autocomplete: {
        type: String,
        default: 'off'
    },
    pattern: {
        type: String,
        default: ''
    },

    // ========== ICONS ==========
    prefixIcon: {
        type: String,
        default: ''
    },
    suffixIcon: {
        type: String,
        default: ''
    },

    // ========== FEATURES ==========
    showPasswordToggle: {
        type: Boolean,
        default: false
    },
    showClear: {
        type: Boolean,
        default: false
    },
    showCounter: {
        type: Boolean,
        default: false
    },
    autoGrow: {
        type: Boolean,
        default: false
    },
    resize: {
        type: Boolean,
        default: false
    },

    // ========== RADIO & CHECKBOX SPECIFIC ==========
    options: {
        type: Array,
        default: () => []
    },
    direction: {
        type: String,
        default: 'horizontal',
        validator: (value) => ['horizontal', 'vertical'].includes(value)
    },
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'primary', 'success', 'warning', 'danger'].includes(value)
    },
    checkboxLabel: {
        type: String,
        default: ''
    },
    checkboxDescription: {
        type: String,
        default: ''
    },

    // ========== SELECT SPECIFIC ==========
    multiple: {
        type: Boolean,
        default: false
    },

    // ========== FILE UPLOAD SPECIFIC ==========
    accept: {
        type: String,
        default: '*/*'
    },
    maxSize: {
        type: Number,
        default: null
    },
    uploadText: {
        type: String,
        default: ''
    },
    buttonText: {
        type: String,
        default: ''
    },
    acceptText: {
        type: String,
        default: ''
    },

    // ========== SIZING ==========
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },

    // ========== CUSTOM CLASSES ==========
    containerClasses: {
        type: String,
        default: ''
    },
    optionClasses: {
        type: String,
        default: ''
    },
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
    'input',
    'change',
    'blur',
    'focus',
    'keyup',
    'keydown',
    'upload',
    'remove',
    'error'
]);

// ========== REFS ==========
const showPassword = ref(false);
const dragOver = ref(false);
const textareaRef = ref(null);
const textareaHeight = ref('auto');
const fileInput = ref(null);
const filePreview = ref(null);
const internalType = ref(props.type);
const showRichTextOptions = ref(false);
const richTextRef = ref(null);
const selectionStart = ref(0);
const selectionEnd = ref(0);

// ========== COMPUTED PROPERTIES ==========
const inputId = computed(() => {
    return props.id || `input-${Math.random().toString(36).substr(2, 9)}`;
});

const labelClasses = computed(() => {
    const base = 'text-gray-700 dark:text-gray-300';
    const error = props.error ? 'text-red-600 dark:text-red-400' : '';
    return `${base} ${error} ${props.labelClasses}`;
});

const helperTextClasses = computed(() => {
    return `text-gray-500 dark:text-gray-400 ${props.helperTextClasses}`;
});

const inputSizeClass = computed(() => {
    const sizes = {
        sm: 'text-sm py-1.5',
        md: 'text-sm py-2',
        lg: 'text-base py-2.5'
    };
    return sizes[props.size];
});

const textareaSizeClass = computed(() => {
    const sizes = {
        sm: 'text-sm py-1.5',
        md: 'text-sm py-2',
        lg: 'text-base py-2.5'
    };
    return sizes[props.size];
});

const hasSuffix = computed(() => {
    return props.suffixIcon ||
        (props.type === 'password' && props.showPasswordToggle) ||
        (props.showClear && props.modelValue);
});

// ========== BASIC INPUT METHODS ==========
const handleInput = (event) => {
    const value = event.target.type === 'number' ?
        (event.target.value === '' ? null : Number(event.target.value)) :
        event.target.value;

    emit('update:modelValue', value);
    emit('input', event);

    if (props.type === 'textarea' && props.autoGrow) {
        autoResize();
    }
};

const handleChange = (event) => {
    emit('change', event);
};

const handleBlur = (event) => {
    emit('blur', event);
};

const handleFocus = (event) => {
    emit('focus', event);
};

// ========== PASSWORD METHODS ==========
const togglePassword = () => {
    showPassword.value = !showPassword.value;
    internalType.value = showPassword.value ? 'text' : 'password';
};

// ========== CLEAR METHODS ==========
const clearInput = () => {
    emit('update:modelValue', '');
    emit('clear');
};

// ========== RADIO METHODS ==========
const handleRadioChange = (event, value) => {
    emit('update:modelValue', value);
    emit('change', event);
};

const getRadioId = (value) => {
    return `${props.name || 'radio'}-${String(value).replace(/\s+/g, '-')}`;
};

const getRadioClasses = (value) => {
    const isSelected = props.modelValue === value;
    const variantColors = {
        default: {
            selected: 'border-gray-600 dark:border-gray-400 bg-gray-600 dark:bg-gray-400',
            unselected: 'border-gray-400 dark:border-gray-600'
        },
        primary: {
            selected: 'border-blue-600 dark:border-blue-400 bg-blue-600 dark:bg-blue-400',
            unselected: 'border-gray-400 dark:border-gray-600'
        },
        success: {
            selected: 'border-green-600 dark:border-green-400 bg-green-600 dark:bg-green-400',
            unselected: 'border-gray-400 dark:border-gray-600'
        },
        warning: {
            selected: 'border-yellow-600 dark:border-yellow-400 bg-yellow-600 dark:bg-yellow-400',
            unselected: 'border-gray-400 dark:border-gray-600'
        },
        danger: {
            selected: 'border-red-600 dark:border-red-400 bg-red-600 dark:bg-red-400',
            unselected: 'border-gray-400 dark:border-gray-600'
        }
    };

    const colors = variantColors[props.variant];
    return isSelected ? colors.selected : colors.unselected;
};

const getRadioInnerClasses = (value) => {
    return 'bg-white';
};

const getRadioLabelClasses = (value) => {
    const base = 'text-sm';
    const isSelected = props.modelValue === value;
    const selected = isSelected
        ? 'font-medium text-gray-900 dark:text-white'
        : 'font-normal text-gray-700 dark:text-gray-300';
    const disabled = props.disabled ? 'text-gray-500 dark:text-gray-500' : '';

    return `${base} ${selected} ${disabled}`;
};

const getRadioIconClasses = (value) => {
    const isSelected = props.modelValue === value;
    return isSelected
        ? 'text-gray-700 dark:text-gray-300'
        : 'text-gray-500 dark:text-gray-400';
};

const getRadioDescriptionClasses = (value) => {
    const base = 'text-gray-500 dark:text-gray-400';
    const isSelected = props.modelValue === value;
    return isSelected
        ? 'text-gray-700 dark:text-gray-300'
        : base;
};

// ========== CHECKBOX METHODS ==========
const handleCheckboxChange = (event) => {
    emit('update:modelValue', event.target.checked);
    emit('change', event);
};

const getCheckboxSelectedClasses = () => {
    const variants = {
        default: 'bg-gray-600 dark:bg-gray-400 border-gray-600 dark:border-gray-400',
        primary: 'bg-blue-600 dark:bg-blue-400 border-blue-600 dark:border-blue-400',
        success: 'bg-green-600 dark:bg-green-400 border-green-600 dark:border-green-400',
        warning: 'bg-yellow-600 dark:bg-yellow-400 border-yellow-600 dark:border-yellow-400',
        danger: 'bg-red-600 dark:bg-red-400 border-red-600 dark:border-red-400'
    };
    return variants[props.variant] || variants.default;
};

const getCheckboxUnselectedClasses = () => {
    return 'border-gray-400 dark:border-gray-600';
};

// ========== SELECT METHODS ==========
const handleSelectChange = (event) => {
    const value = props.multiple ?
        Array.from(event.target.selectedOptions).map(option => option.value) :
        event.target.value;

    emit('update:modelValue', value);
    emit('change', event);
};

// ========== TEXTAREA METHODS ==========
const autoResize = () => {
    if (!textareaRef.value || !props.autoGrow) return;

    nextTick(() => {
        textareaRef.value.style.height = 'auto';
        const scrollHeight = textareaRef.value.scrollHeight;
        textareaHeight.value = `${scrollHeight}px`;
    });
};

// ========== RICH TEXT EDITOR METHODS ==========
const handleRichTextInput = (event) => {
    const html = richTextRef.value.innerHTML;
    emit('update:modelValue', html);
    emit('input', event);
};

const formatText = (format) => {
    if (!richTextRef.value || props.disabled || props.readonly) return;

    const selection = window.getSelection();
    if (!selection.rangeCount) return;

    const range = selection.getRangeAt(0);
    const selectedText = range.toString();

    let formattedText = selectedText;
    let wrapperStart = '';
    let wrapperEnd = '';

    switch (format) {
        case 'bold':
            wrapperStart = '<strong>';
            wrapperEnd = '</strong>';
            break;
        case 'italic':
            wrapperStart = '<em>';
            wrapperEnd = '</em>';
            break;
        case 'underline':
            wrapperStart = '<u>';
            wrapperEnd = '</u>';
            break;
        case 'unorderedList':
            wrapperStart = '<ul><li>';
            wrapperEnd = '</li></ul>';
            break;
        case 'orderedList':
            wrapperStart = '<ol><li>';
            wrapperEnd = '</li></ol>';
            break;
        case 'heading1':
            wrapperStart = '<h1>';
            wrapperEnd = '</h1>';
            break;
        case 'heading2':
            wrapperStart = '<h2>';
            wrapperEnd = '</h2>';
            break;
        case 'quote':
            wrapperStart = '<blockquote>';
            wrapperEnd = '</blockquote>';
            break;
        case 'code':
            wrapperStart = '<code>';
            wrapperEnd = '</code>';
            break;
    }

    const fragment = range.extractContents();
    const wrapper = document.createElement('span');
    wrapper.innerHTML = wrapperStart + fragment.textContent + wrapperEnd;
    range.insertNode(wrapper);

    // Update model value
    const html = richTextRef.value.innerHTML;
    emit('update:modelValue', html);
};

const insertLink = () => {
    if (!richTextRef.value || props.disabled || props.readonly) return;

    const url = prompt('Enter URL:', 'https://');
    if (!url) return;

    const selection = window.getSelection();
    if (!selection.rangeCount) return;

    const range = selection.getRangeAt(0);
    const selectedText = range.toString() || 'link';

    const link = document.createElement('a');
    link.href = url;
    link.textContent = selectedText;
    link.target = '_blank';
    link.rel = 'noopener noreferrer';

    range.deleteContents();
    range.insertNode(link);

    // Update model value
    const html = richTextRef.value.innerHTML;
    emit('update:modelValue', html);
};

// ========== FILE UPLOAD METHODS ==========
const triggerFileInput = () => {
    if (!props.disabled && !props.readonly && fileInput.value) {
        fileInput.value.click();
    }
};

const onFileChange = (event) => {
    const files = Array.from(event.target.files);

    if (files.length === 0) return;

    // Validate file size
    if (props.maxSize && files[0].size > props.maxSize) {
        emit('error', `File exceeds maximum size of ${formatFileSize(props.maxSize)}`);
        return;
    }

    // Update model value
    if (props.multiple) {
        emit('update:modelValue', files);
    } else {
        const file = files[0];
        emit('update:modelValue', file);
        filePreview.value = file;
    }

    emit('upload', files);
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
        const mockEvent = {
            target: {
                files: event.dataTransfer.files
            }
        };
        onFileChange(mockEvent);
    }
};

const clearFile = () => {
    emit('update:modelValue', null);
    filePreview.value = null;
    emit('remove');
};

const isImageFile = (file) => {
    if (!file) return false;
    return file.type.startsWith('image/');
};

const getFilePreview = (file) => {
    if (file instanceof File && isImageFile(file)) {
        return URL.createObjectURL(file);
    }
    return file;
};

const getFileIcon = (mimeType) => {
    if (!mimeType) return 'fas fa-file';

    const iconMap = {
        'application/pdf': 'fas fa-file-pdf',
        'application/msword': 'fas fa-file-word',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fas fa-file-word',
        'application/vnd.ms-excel': 'fas fa-file-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'fas fa-file-excel',
        'text/plain': 'fas fa-file-alt',
        'image/': 'fas fa-image',
        'audio/': 'fas fa-file-audio',
        'video/': 'fas fa-file-video',
        'default': 'fas fa-file'
    };

    for (const [key, icon] of Object.entries(iconMap)) {
        if (mimeType.startsWith(key)) return icon;
    }

    return iconMap.default;
};

const formatFileSize = (bytes) => {
    if (!bytes) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// ========== COLOR INPUT METHODS ==========
const handleColorInput = (event) => {
    let value = event.target.value;
    // Ensure it starts with #
    if (!value.startsWith('#')) {
        value = '#' + value;
    }
    // Validate hex color
    if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
        emit('update:modelValue', value);
    }
    emit('input', event);
};

// ========== WATCHERS ==========
watch(() => props.modelValue, (newValue, oldValue) => {
    // Auto-grow for textarea
    if (props.type === 'textarea' && props.autoGrow) {
        autoResize();
    }

    // Sync rich text editor content
    if (props.type === 'richtext' && richTextRef.value && newValue !== richTextRef.value.innerHTML) {
        richTextRef.value.innerHTML = newValue || '';
    }

    // Sync file preview
    if (props.type === 'file' && newValue instanceof File) {
        filePreview.value = newValue;
    } else if (props.type === 'file' && !newValue) {
        filePreview.value = null;
    }
}, { immediate: true });

// ========== LIFECYCLE HOOKS ==========
onMounted(() => {
    // Initialize auto-grow
    if (props.type === 'textarea' && props.autoGrow) {
        autoResize();
    }

    // Initialize rich text editor content
    if (props.type === 'richtext' && richTextRef.value && props.modelValue) {
        richTextRef.value.innerHTML = props.modelValue;
    }
});
</script>