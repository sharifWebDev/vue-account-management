<template>
  <thead class="bg-gray-50 dark:bg-gray-800">
    <tr>
      <!-- SL Column -->
      <th v-if="showSlColumn" scope="col"
        class="px-6 py-3 wtext-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer  w-[60px]"
        @click="handleSort('id')" :class="{ 'bg-gray-100 dark:bg-gray-700': currentSortColumn === 'id' }">
        <div class="flex items-center justify-start">
          <span>S/L</span>
          <i :class="[
            'fas ml-1',
            currentSortColumn === 'id'
              ? currentSortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down'
              : 'fa-sort text-gray-300'
          ]"></i>
        </div>
      </th>

      <!-- Dynamic Columns -->
      <th v-for="column in columns" :key="column.key"
        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
        @click="handleSort(column.key)" :class="{
          'bg-gray-100 dark:bg-gray-700': currentSortColumn === column.key,
          'text-right': column.align === 'right'
        }" :style="column.width ? `width: ${column.width}` : ''">
        <div :class="['flex items-center', column.align === 'right' ? 'justify-end' : '']">
          <span>{{ column.label }}</span>
          <i v-if="column.sortable !== false" :class="[
            'fas ml-1',
            currentSortColumn === column.key
              ? currentSortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down'
              : 'fa-sort text-gray-300'
          ]"></i>
        </div>
      </th>

      <!-- Actions Column -->
      <th v-if="showActionsColumn" scope="col"
        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
        Actions
      </th>
    </tr>
  </thead>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  columns: {
    type: Array,
    required: true,
    default: () => []
  },

  // Sort State - Accept both string or object
  sortBy: {
    type: [String, Object],
    default: ''
  },
  sortDirection: {
    type: String,
    default: 'asc',
    validator: (value) => ['asc', 'desc'].includes(value)
  },

  // Column Visibility
  showSlColumn: {
    type: Boolean,
    default: true
  },
  showActionsColumn: {
    type: Boolean,
    default: true
  }
});

const emit = defineEmits(['sort']);

// Computed properties to handle both cases
const currentSortColumn = computed(() => {
  if (typeof props.sortBy === 'string') {
    return props.sortBy;
  } else if (props.sortBy && typeof props.sortBy === 'object' && props.sortBy.column) {
    return props.sortBy.column;
  }
  return '';
});

const currentSortDirection = computed(() => {
  if (typeof props.sortBy === 'string') {
    return props.sortDirection;
  } else if (props.sortBy && typeof props.sortBy === 'object' && props.sortBy.direction) {
    return props.sortBy.direction;
  }
  return props.sortDirection || 'asc';
});

// Methods
const handleSort = (columnKey) => {
  const column = props.columns.find(col => col.key === columnKey);

  // Check if column is sortable
  if (column && column.sortable === false) {
    return;
  }

  let newDirection = 'asc';

  if (currentSortColumn.value === columnKey) {
    // Toggle direction if same column
    newDirection = currentSortDirection.value === 'asc' ? 'desc' : 'asc';
  }

  emit('sort', {
    column: columnKey,
    direction: newDirection
  });
};
</script>