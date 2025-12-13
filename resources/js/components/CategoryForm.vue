<template>
  <div class="container mx-auto px-4 py-8 max-w-3xl">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">
        {{ isEdit ? 'Edit Category' : 'Add New Category' }}
      </h1>
      <p class="text-gray-600 mt-2">
        {{ isEdit ? 'Update category information' : 'Fill in the form to add a new category' }}
      </p>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="bg-white shadow-md rounded-lg p-6">
      <!-- Name -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Name <span class="text-red-500">*</span>
        </label>
        <input
          v-model="formData.name"
          type="text"
          placeholder="Enter category name"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
        <p v-if="errors.name" class="text-red-500 text-sm mt-1">
          {{ errors.name[0] }}
        </p>
      </div>

      <!-- Description -->
      <div class="mb-6">
        <label class="block text-gray-700 font-semibold mb-2">
          Description
        </label>
        <textarea
          v-model="formData.description"
          rows="5"
          placeholder="Enter category description (optional)"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        ></textarea>
        <p v-if="errors.description" class="text-red-500 text-sm mt-1">
          {{ errors.description[0] }}
        </p>
      </div>

      <!-- Buttons -->
      <div class="flex gap-4">
        <button
          type="submit"
          :disabled="loading"
          class="flex-1 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition font-semibold"
        >
          {{ loading ? 'Saving...' : isEdit ? 'Update Category' : 'Create Category' }}
        </button>
        <button
          type="button"
          @click="$router.push('/categories')"
          class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400 transition font-semibold"
        >
          Cancel
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CategoryForm',
  data() {
    return {
      formData: {
        name: '',
        description: '',
      },
      errors: {},
      loading: false,
      isEdit: false,
    };
  },
  mounted() {
    const id = this.$route.params.id;
    if (id) {
      this.isEdit = true;
      this.fetchCategory(id);
    }
  },
  methods: {
    async fetchCategory(id) {
      try {
        const response = await axios.get(`/api/categories/${id}`);
        this.formData = {
          name: response.data.name,
          description: response.data.description || '',
        };
      } catch (error) {
        console.error('Error fetching category:', error);
        alert('Failed to fetch category data');
        this.$router.push('/categories');
      }
    },
    async handleSubmit() {
      this.loading = true;
      this.errors = {};

      try {
        if (this.isEdit) {
          const id = this.$route.params.id;
          await axios.put(`/api/categories/${id}`, this.formData);
          alert('Category updated successfully!');
        } else {
          await axios.post('/api/categories', this.formData);
          alert('Category created successfully!');
        }
        this.$router.push('/categories');
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          alert('Failed to save category. Please try again.');
        }
        console.error('Error saving category:', error);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>