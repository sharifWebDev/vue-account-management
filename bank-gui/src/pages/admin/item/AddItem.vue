<template>
  <div class="page-body">
    <div class="container-xl">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Add New Item</h3>
        </div>
        <div v-if="loading" class="loaderw">
          <tableSkeleton />
        </div>
        <div class="card-body" v-else>
          <form @submit.prevent="handleSubmit">
            <div class="row">
              <!-- Name -->
              <div class="col-md-6 col-lg-4 mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': errors.name }"
                  id="name"
                  placeholder="Enter name..."
                  @keyup="validateField('name')"
                />
                <div v-if="errors.name" class="invalid-feedback">
                  {{ errors.name[0] }}
                </div>
              </div>

              <!-- Icon -->
              <div class="col-md-6 col-lg-4 mb-3">
                <label for="icon" class="form-label">Icon</label>
                <input
                  v-model="form.icon"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': errors.icon }"
                  id="icon"
                  placeholder="Enter icon..."
                  @keyup="validateField('icon')"
                />
                <div v-if="errors.icon" class="invalid-feedback">
                  {{ errors.icon[0] }}
                </div>
              </div>

              <!-- Menu Title -->
              <div class="col-md-6 col-lg-4 mb-3">
                <label for="menu_title" class="form-label">Menu Title</label>
                <input
                  v-model="form.menu_title"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': errors.menu_title }"
                  id="menu_title"
                  placeholder="Enter menu title..."
                  @keyup="validateField('menu_title')"
                />
                <div v-if="errors.menu_title" class="invalid-feedback">
                  {{ errors.menu_title[0] }}
                </div>
              </div>

              <!-- Menu Subtitle -->
              <div class="col-md-6 col-lg-4 mb-3">
                <label for="menu_subtitle" class="form-label">Menu Subtitle</label>
                <input
                  v-model="form.menu_subtitle"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': errors.menu_subtitle }"
                  id="menu_subtitle"
                  placeholder="Enter menu subtitle..."
                  @keyup="validateField('menu_subtitle')"
                />
                <div v-if="errors.menu_subtitle" class="invalid-feedback">
                  {{ errors.menu_subtitle[0] }}
                </div>
              </div>

              <!-- Title -->
              <div class="col-md-6 col-lg-4 mb-3">
                <label for="title" class="form-label">Title</label>
                <input
                  v-model="form.title"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': errors.title }"
                  id="title"
                  placeholder="Enter title..."
                  @keyup="validateField('title')"
                />
                <div v-if="errors.title" class="invalid-feedback">
                  {{ errors.title[0] }}
                </div>
              </div>

              <div class="col-md-6 col-lg-4 mb-3">
                <label for="field_count" class="form-label">Field count</label>
                <input
                  v-model="form.field_count"
                  type="number"
                  class="form-control"
                  :class="{ 'is-invalid': errors.field_count }"
                  id="field_count"
                  placeholder="Enter field count..."
                  @keyup="validateField('field_count')"
                />
                <div v-if="errors.field_count" class="invalid-feedback">
                  {{ errors.field_count[0] }}
                </div>
              </div>

              <!-- field_type -->
              <div class="col-md-6 col-lg-4 mb-3">
                <label for="field_type" class="form-label">Field Type</label>
                <select
                  v-model="form.field_type"
                  class="form-select"
                  :class="{ 'is-invalid': errors.field_type }"
                  id="field_type"
                  placeholder="Enter field type..."
                  @keyup="validateField('field_type')"
                  @change="validateField('field_type')"
                >
                  <option value="text">Text</option>
                  <option value="number">Number</option>
                  <option value="date">Date</option>
                  <option value="time">Time</option>
                  <option value="select">Select</option>
                </select>
                <div v-if="errors.field_type" class="invalid-feedback">
                  {{ errors.field_type[0] }}
                </div>
              </div>

              <!-- Description -->
              <div class="col-md-6 col-lg-4 mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea
                  v-model="form.description"
                  class="form-control"
                  :class="{ 'is-invalid': errors.description }"
                  id="description"
                  placeholder="Enter description..."
                  @keyup="validateField('description')"
                ></textarea>
                <div v-if="errors.description" class="invalid-feedback">
                  {{ errors.description[0] }}
                </div>
              </div>

              <!-- Subdescription -->
              <div class="col-md-6 col-lg-4 mb-3">
                <label for="subdescription" class="form-label">Subdescription</label>
                <textarea
                  v-model="form.subdescription"
                  class="form-control"
                  :class="{ 'is-invalid': errors.subdescription }"
                  id="subdescription"
                  placeholder="Enter subdescription..."
                  @keyup="validateField('subdescription')"
                ></textarea>
                <div v-if="errors.subdescription" class="invalid-feedback">
                  {{ errors.subdescription[0] }}
                </div>
              </div>

              <!-- Quick Tips -->
              <div class="col-md-6 col-lg-4 mb-3">
                <label for="quick_tips" class="form-label">Quick Tips</label>
                <textarea
                  v-model="form.quick_tips"
                  class="form-control"
                  :class="{ 'is-invalid': errors.quick_tips }"
                  id="quick_tips"
                  placeholder="Enter quick tips..."
                  @keyup="validateField('quick_tips')"
                ></textarea>
                <div v-if="errors.quick_tips" class="invalid-feedback">
                  {{ errors.quick_tips[0] }}
                </div>
              </div>

              <!-- Priority -->
              <div class="col-md-6 col-lg-4 mb-3">
                <label for="priority" class="form-label">Priority</label>
                <input
                  v-model="form.priority"
                  type="number"
                  class="form-control"
                  :class="{ 'is-invalid': errors.priority }"
                  id="priority"
                  placeholder="Enter priority..."
                  @keyup="validateField('priority')"
                />
                <div v-if="errors.priority" class="invalid-feedback">
                  {{ errors.priority[0] }}
                </div>
              </div>
            </div>
 
            <router-link to="/item" class="btn btn-danger btn-sm me-2 py-1 px-3">Close</router-link>
              
            <button class="btn btn-outline-secondary btn-sm me-2" type="reset">
              Reset
            </button>
 
            <button 
              @click="handleSubmit" 
class="btn btn-gray btn-sm me-2" type="submit"
              :disabled="loadingBtn"
              >
              <span v-if="loadingBtn">
                <span class="spinner-border spinner-border-sm me-2"></span> Submit
              </span>
              <span v-else>Submit</span>
            </button>

          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref , onMounted} from "vue";
import { useRouter } from "vue-router";
import { addItemAPI } from "@/services/ItemService.js";
import tableSkeleton from "@/components/core/FormSkeleton.vue";
import { success, error, warning, info } from "toastr";

const router = useRouter();
const loading = ref(true);
const loadingBtn = ref(false);
 
onMounted(() => {
  setTimeout(() => {
    loading.value = false;
  }, 200);
});

const form = ref({
  name: "",
  icon: "",
  menu_title: "",
  menu_subtitle: "",
  title: "",
  description: "",
  subdescription: "",
  quick_tips: "",
  priority: 0,
  field_count: 0,
  field_type: "text",
});

const errors = ref({});

// Validate a specific field
const validateField = (field) => {
  if (!form.value[field]) {
    errors.value[field] = [`The ${formatLabel(field)} field is required.`];
  } else {
    delete errors.value[field]; // Properly remove errors when field is valid
  }
};

// Format labels for better display
const formatLabel = (key) =>
  key.replace(/_/g, " ").replace(/\b\w/g, (char) => char.toUpperCase());

// Validate entire form
const validateForm = () => {
  Object.keys(form.value).forEach((field) => validateField(field));
  return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
  if (!validateForm()) return;

  try {
    loadingBtn.value = true;
    await addItemAPI(form.value);
    success("Item added successfully!");
    router.push("/item");
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
    } else {
      error("An error occurred!");
      console.error("An error occurred:", error);
    }
  } finally {
    setTimeout(() => (loadingBtn.value = false), 300);
  }
};
</script>

<style scoped>
.invalid-feedback {
  color: red;
  font-size: 0.875em;
}
.is-invalid {
  border-color: red;
  background-color: rgba(255, 214, 214, 0.2);
}

button {
  margin: 5px;
  padding: 5px 10px;
  cursor: pointer;
}

.spinner-border {
  width: 15px;
  height: 15px;
  border-width: 2px;
}
</style>
