<template>
  <li class="sidebar-dropdown">
    <div class="dropdown-toggle" @click="toggleDropdown">
      <span class="icon">
        <font-awesome-icon :icon="['fas', icon]" />
      </span>
      <span class="label">{{ label }}</span>
    </div>
    <ul v-show="isOpen" class="dropdown-menu">
      <slot></slot>
    </ul>
  </li>
</template>

<script>
export default {
  props: {
    icon: String,
    label: String
  },
  data() {
    return {
      isOpen: false
    };
  },
  methods: {
    toggleDropdown() {
      this.isOpen = !this.isOpen;
    }
  }
};
</script>

<style scoped>
.sidebar-dropdown {
  cursor: pointer;
}
.dropdown-toggle {
  display: flex;
  align-items: center;
  padding: 10px;
  color: #ffffff;
}
.dropdown-toggle:hover {
  background: rgba(255, 255, 255, 0.1);
}
.dropdown-menu {
  list-style: none;
  padding-left: 20px;
}
</style>
