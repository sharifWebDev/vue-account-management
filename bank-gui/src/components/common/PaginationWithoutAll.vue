<template>
    <div v-if="totalItems > 0" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Left: Showing results -->
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Showing 
                <span class="font-medium">{{ showingFrom }}</span> 
                to 
                <span class="font-medium">{{ showingTo }}</span> 
                of 
                <span class="font-medium">{{ totalItems }}</span> 
                results
            </div>

            <!-- Center: Per page dropdown -->
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Show</span>
                <select 
                    v-model="localPerPage"
                    @change="handlePerPageChange"
                    class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:border-gray-600 transition-all shadow-sm"
                >
                    <option v-for="option in perPageOptions" :key="option" :value="option">
                        {{ option }}
                    </option>
                </select>
                <span class="text-sm text-gray-600 dark:text-gray-400">entries</span>
            </div>

            <!-- Right: Pagination buttons -->
            <div class="flex space-x-1">
                <button 
                    @click="goToPage(1)"
                    :disabled="currentPage === 1"
                    :class="[
    'px-2 py-1.5 bg-gray-200 rounded-lg text-sm font-medium transition-all',
                        currentPage === 1 ? 
                            'text-gray-400 cursor-not-allowed' :
                            'text-gray-700 hover:bg-gray-300 dark:text-gray-300 dark:hover:bg-gray-700 hover:shadow-sm'
                    ]"
                    title="First Page"
                >
                    <i class="fas fa-angle-double-left"></i>
                </button>
                <button 
                    @click="goToPage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    :class="[
    'px-2 py-1.5 bg-gray-200 rounded-lg text-sm font-medium transition-all',
                        currentPage === 1 ? 
                            'text-gray-400 cursor-not-allowed' :
                            'text-gray-700 hover:bg-gray-300 dark:text-gray-300 dark:hover:bg-gray-700 hover:shadow-sm'
                    ]"
                    title="Previous Page"
                >
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <!-- Page Numbers -->
                <template v-for="page in getPageNumbers()" :key="page">
                    <button 
                        v-if="page !== '...'"
                        @click="goToPage(page)"
                        :class="[
                            'px-2 py-1.5 rounded-lg text-sm font-medium min-w-[2rem] transition-all',
                            page === currentPage ? 
                                'bg-gray-600 text-white shadow-sm' :
                                'text-gray-700 dark:text-gray-300 bg-gray-200 hover:bg-gray-300 dark:text-gray-300 dark:hover:bg-gray-700 hover:shadow-sm'
                        ]"
                    >
                        {{ page }}
                    </button>
                    <span 
                        v-else
 class="px-2 py-1.5 text-gray-500"
                    >
                        {{ page }}
                    </span>
                </template>
                
                <button 
                    @click="goToPage(currentPage + 1)"
                    :disabled="currentPage === lastPage"
                    :class="[
    'px-2 bg-gray-200 py-1.5 rounded-lg text-sm font-medium transition-all',
                        currentPage === lastPage ? 
                            'text-gray-400 cursor-not-allowed' :
                            'text-gray-700 hover:bg-gray-300 dark:text-gray-300 dark:hover:bg-gray-700 hover:shadow-sm'
                    ]"
                    title="Next Page"
                >
                    <i class="fas fa-chevron-right"></i>
                </button>
                <button 
                    @click="goToPage(lastPage)"
                    :disabled="currentPage === lastPage"
                    :class="[
    'px-2 bg-gray-200 py-1.5 rounded-lg text-sm font-medium transition-all',
                        currentPage === lastPage ? 
                            'text-gray-400 cursor-not-allowed' :
                            'text-gray-700 hover:bg-gray-300 dark:text-gray-300 dark:hover:bg-gray-700 hover:shadow-sm'
                    ]"
                    title="Last Page"
                >
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    totalItems: {
        type: Number,
        required: true,
        default: 0
    },
    perPage: {
        type: Number,
        required: true,
        default: 10
    },
    currentPage: {
        type: Number,
        required: true,
        default: 1
    },
    lastPage: {
        type: Number,
        required: true,
        default: 1
    }
});

const emit = defineEmits(['update:perPage', 'update:currentPage']);

const localPerPage = ref(props.perPage);
const perPageOptions = [10, 20, 50, 100, 500];

const showingFrom = computed(() => {
    return (props.currentPage - 1) * props.perPage + 1;
});

const showingTo = computed(() => {
    const to = props.currentPage * props.perPage;
    return to > props.totalItems ? props.totalItems : to;
});

watch(() => props.perPage, (newValue) => {
    localPerPage.value = newValue;
});

const handlePerPageChange = () => {
    emit('update:perPage', localPerPage.value);
};

const goToPage = (page) => {
    if (page >= 1 && page <= props.lastPage) {
        emit('update:currentPage', page);
    }
};

const getPageNumbers = () => {
    const current = props.currentPage;
    const last = props.lastPage;
    const delta = 2;
    const range = [];
    const rangeWithDots = [];
    let l;

    for (let i = 1; i <= last; i++) {
        if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
            range.push(i);
        }
    }

    range.forEach((i) => {
        if (l) {
            if (i - l === 2) {
                rangeWithDots.push(l + 1);
            } else if (i - l !== 1) {
                rangeWithDots.push('...');
            }
        }
        rangeWithDots.push(i);
        l = i;
    });

    return rangeWithDots;
};
</script>