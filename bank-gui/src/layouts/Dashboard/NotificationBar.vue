<template>
  <div class="relative">
    <button @click="toggleNotifications"
      class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 relative">
      <i class="fas fa-bell"></i>
      <span v-if="unreadCount > 0" class="notification-badge bg-red-500 text-white rounded-full"
        style="position: absolute; top: -2px; right: -2px; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center;">
        {{ unreadCount }}
      </span>
    </button>

    <!-- Notification Dropdown -->
    <div v-if="showNotifications"
      class="notification-dropdown absolute right-0 mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-2 z-50 border border-gray-200 dark:border-gray-700"
      style="width: 380px; max-height: 500px;">
      <!-- Notification Header -->
      <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center">
          <h3 class="font-semibold text-gray-800 dark:text-white">Notifications</h3>
          <div class="flex items-center space-x-2">
            <button @click="markAllAsRead" class="text-xs text-gray-600 dark:text-gray-400 hover:underline">
              Mark all as read
            </button>
            <button @click="openSettings"
              class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
              <i class="fas fa-cog text-sm"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Notification List -->
      <div class="max-h-96 overflow-y-auto">
        <div v-for="notification in notifications" :key="notification.id" :class="[
          'notification-item px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer',
          notification.unread ? 'unread' : ''
        ]" @click="markAsRead(notification)">
          <div class="flex items-start space-x-3">
            <div :class="['p-2 rounded-lg', getNotificationClass(notification.type)]">
              <i :class="getNotificationIcon(notification.type)"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-800 dark:text-white">{{ notification.title }}</p>
              <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ notification.message }}</p>
              <div class="flex items-center justify-between mt-2">
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ notification.time }}</span>
                <span v-if="notification.unread"
                  class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-300 rounded-full">
                  New
                </span>
              </div>
            </div>
            <button @click.stop="removeNotification(notification.id)"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
              <i class="fas fa-times text-xs"></i>
            </button>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="notifications.length === 0" class="px-4 py-8 text-center">
          <i class="fas fa-bell-slash text-gray-400 text-2xl mb-2"></i>
          <p class="text-gray-500 dark:text-gray-400 text-sm">No notifications</p>
          <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">You're all caught up!</p>
        </div>
      </div>

      <!-- Notification Footer -->
      <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
        <router-link to="/notifications"
          class="block text-center text-sm text-gray-600 dark:text-gray-400 hover:underline">
          View all notifications
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const showNotifications = ref(false)
const notifications = ref([
  {
    id: 1,
    title: 'Transaction Completed',
    message: 'Your transaction has been successfully completed.',
    time: '2 hours ago',
    type: 'success',
    unread: true
  },
  {
    id: 2,
    title: 'Storage limit warning',
    message: 'You\'ve used 85% of your available storage. Consider upgrading your plan.',
    time: '5 hours ago',
    type: 'warning',
    unread: true
  },
  {
    id: 3,
    title: 'New feature available',
    message: 'AI-powered form builder is now available for all Pro users.',
    time: 'Yesterday',
    type: 'info',
    unread: false
  },
  {
    id: 4,
    title: 'Payment received',
    message: 'Your payment of $49.99 for the Pro plan has been processed successfully.',
    time: '2 days ago',
    type: 'success',
    unread: false
  },
  {
    id: 5,
    title: 'Build failed',
    message: 'Your app "CRM System" failed to build due to database connection issues.',
    time: '3 days ago',
    type: 'error',
    unread: false
  }
])

const unreadCount = computed(() => {
  return notifications.value.filter(n => n.unread).length
})

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value
}

const markAllAsRead = () => {
  notifications.value.forEach(notification => {
    notification.unread = false
  })
}

const markAsRead = (notification) => {
  notification.unread = false
}

const removeNotification = (id) => {
  notifications.value = notifications.value.filter(n => n.id !== id)
}

const openSettings = () => {
  alert('Notification settings would open here')
}

const getNotificationClass = (type) => {
  const classes = {
    success: 'notification-success',
    warning: 'notification-warning',
    error: 'notification-error',
    info: 'notification-info'
  }
  return classes[type] || 'notification-info'
}

const getNotificationIcon = (type) => {
  const icons = {
    success: 'fas fa-check-circle text-sm',
    warning: 'fas fa-exclamation-triangle text-sm',
    error: 'fas fa-exclamation-circle text-sm',
    info: 'fas fa-info-circle text-sm'
  }
  return icons[type] || 'fas fa-info-circle text-sm'
}

// Close notifications when clicking outside
const handleClickOutside = (event) => {
  const dropdown = document.querySelector('.notification-dropdown')
  const button = document.querySelector('[data-notification-button]')

  if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
    showNotifications.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>