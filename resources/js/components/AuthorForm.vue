<template>
  <div class="container mx-auto px-4 py-8 max-w-3xl">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">
        {{ isEdit ? 'Edit Author' : 'Add New Author' }}
      </h1>
      <p class="text-gray-600 mt-2">
        {{ isEdit ? 'Update author information' : 'Fill in the form to add a new author' }}
      </p>
    </div>

    <!-- Loading State saat fetch data -->
    <div v-if="fetchingData" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
      <p class="mt-4 text-gray-600">Loading author data...</p>
    </div>

    <!-- Form -->
    <form v-else @submit.prevent="handleSubmit" class="bg-white shadow-md rounded-lg p-6">
      <!-- Name -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Name <span class="text-red-500">*</span>
        </label>
        <input
          v-model="formData.name"
          type="text"
          placeholder="Enter author name"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
        <p v-if="errors.name" class="text-red-500 text-sm mt-1">
          {{ errors.name[0] }}
        </p>
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Email <span class="text-red-500">*</span>
        </label>
        <input
          v-model="formData.email"
          type="email"
          placeholder="author@example.com"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
        <p v-if="errors.email" class="text-red-500 text-sm mt-1">
          {{ errors.email[0] }}
        </p>
      </div>

      <!-- Bio -->
      <div class="mb-6">
        <label class="block text-gray-700 font-semibold mb-2">
          Bio
        </label>
        <textarea
          v-model="formData.bio"
          rows="5"
          placeholder="Enter author biography (optional)"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        ></textarea>
        <p v-if="errors.bio" class="text-red-500 text-sm mt-1">
          {{ errors.bio[0] }}
        </p>
      </div>

      <!-- Buttons -->
      <div class="flex gap-4">
        <button
          type="submit"
          :disabled="loading"
          class="flex-1 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition font-semibold"
        >
          {{ loading ? 'Saving...' : isEdit ? 'Update Author' : 'Create Author' }}
        </button>
        <button
          type="button"
          @click="$router.push('/authors')"
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
  name: 'AuthorForm',
  data() {
    return {
      formData: {
        name: '',
        email: '',
        bio: '',
      },
      errors: {},
      loading: false,
      fetchingData: false, // Tambahan untuk loading state
      isEdit: false,
    };
  },
  mounted() {
    const id = this.$route.params.id;
    if (id) {
      this.isEdit = true;
      this.fetchAuthor(id);
    }
  },
  methods: {
    async fetchAuthor(id) {
      this.fetchingData = true; // Mulai loading
      try {
        const response = await axios.get(`/api/authors/${id}`);
        
        // ✅ FIX: Akses response.data.data
        const authorData = response.data.data;
        
        this.formData = {
          name: authorData.name || '',
          email: authorData.email || '',
          bio: authorData.bio || '',
        };
        
        console.log('Fetched author data:', this.formData); // Debug
      } catch (error) {
        console.error('Error fetching author:', error);
        alert('Failed to fetch author data');
        this.$router.push('/authors');
      } finally {
        this.fetchingData = false; // Selesai loading
      }
    },
    async handleSubmit() {
      this.loading = true;
      this.errors = {};

      try {
        if (this.isEdit) {
          const id = this.$route.params.id;
          const response = await axios.put(`/api/authors/${id}`, this.formData);
          alert(response.data.message || 'Author updated successfully!');
        } else {
          const response = await axios.post('/api/authors', this.formData);
          alert(response.data.message || 'Author created successfully!');
        }
        this.$router.push('/authors');
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          alert('Failed to save author. Please try again.');
        }
        console.error('Error saving author:', error);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>/*************  ✨ Windsurf Command ⭐  *************/
/*******  d8e59427-53de-4089-a379-2263a6d1ad78  *******//**

 * Go back to dashboard

 * @return {void}

