<template>
  <div class="page-body">
    <div class="container-xl">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">View Item</h3>
        </div>
        <div class="card-body">
          <!-- Show Skeleton while loading -->
          <div v-if="loading" class="loaderw">
            <tableSkeleton />
          </div>
          <div v-else>
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6 mb-3">
                <label class="form-label">Icon</label>
                <input type="text" class="form-control" v-model="item.icon" disabled />
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Menu Title</label>
                <input type="text" class="form-control" v-model="item.menu_title" disabled />
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Menu Subtitle</label>
                <input type="text" class="form-control" v-model="item.menu_subtitle" disabled />
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" v-model="item.title" disabled />
              </div>

              <!-- Right Column -->
              <div class="col-md-6 mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" v-model="item.description" disabled></textarea>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Subdescription</label>
                <textarea class="form-control" v-model="item.subdescription" disabled></textarea>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Quick Tips</label>
                <textarea class="form-control" v-model="item.quick_tips" disabled></textarea>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Priority</label>
                <input type="number" class="form-control" v-model="item.priority" disabled />
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <router-link to="/item" class="btn btn-danger btn-sm">Close</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { fetchItemByIdAPI } from "@/services/ItemService.js";
import tableSkeleton from "@/components/core/FormSkeleton.vue"; // Import the Skeleton Component

const route = useRoute();
const item = ref({});
const loading = ref(true);

onMounted(async () => {
  try {
    const itemId = route.params.id;
    const response = await fetchItemByIdAPI(itemId);
    item.value = response.data;
  } catch (error) {
    console.error("Error fetching item:", error);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
textarea {
  resize: none;
}
</style>
