<template>
    <div class="container-xl mt-2">
      <!-- Error Messages -->
      <div v-if="errors.length || errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error(s):</strong>
        <ul>
          <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
          <li v-if="errorMessage">{{ errorMessage }}</li>
        </ul>
        <button type="button" class="btn-close" @click="dismissError"></button>
      </div>
  
      <!-- Success Message -->
      <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex">
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                 stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M5 12l5 5l10 -10" />
            </svg>
          </div>
          <div>
            <strong>Success:</strong> {{ successMessage }}
          </div>
        </div>
        <button type="button" class="btn-close" @click="dismissSuccess"></button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from "vue";
  
  const props = defineProps({
    errors: Array, // List of error messages
    errorMessage: String, // Single error message
    successMessage: String, // Success message
  });
  
  const emit = defineEmits(["clearErrors", "clearSuccess"]);
  
  // Dismiss Error Alert
  const dismissError = () => {
    emit("clearErrors");
  };
  
  // Dismiss Success Alert
  const dismissSuccess = () => {
    emit("clearSuccess");
  };
  
  // Auto-dismiss after 5 seconds
  onMounted(() => {
    setTimeout(() => {
      dismissError();
      dismissSuccess();
    }, 5000);
  });
  </script>
  
  <style scoped>
  .alert {
    transition: opacity 0.5s ease-in-out;
  }
  </style>
  