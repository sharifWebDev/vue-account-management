<template>
  <li>
    <router-link :to="to" class="sidebar-item">
      <font-awesome-icon :icon="['fas', icon]" class="icon" />
      <span class="label">{{ label }}</span>
    </router-link>
  </li>
</template>

<script>
export default {
  props: {
    icon: String,
    label: String,
    to: String
  }
};
</script>

<style scoped>
.sidebar-item {
  display: flex;
  align-items: center;
  padding: 10px;
  color: #ffffff;
  text-decoration: none;
}
.sidebar-item:hover {
  background: rgba(255, 255, 255, 0.1);
}
.icon {
  margin-right: 10px;
}
</style>
