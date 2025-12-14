<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <button
            @click="goBack"
            type="button"
            class="p-2 hover:bg-gray-100 rounded-lg transition"
            title="Back to Dashboard"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
          </button>
          <h1 class="text-3xl font-bold text-gray-800">Authors Management</h1>
        </div>
        <router-link
          to="/authors/create"
          class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition"
        >
          + Add New Author
        </router-link>
      </div>
    </div>

    <!-- Search -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
      <div class="flex gap-4 flex-wrap">
        <input
          v-model="search"
          type="text"
          placeholder="Search by name or email..."
          class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          @keyup.enter="fetchAuthors"
        />
        <button
          @click="fetchAuthors"
          class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition"
        >
          Search
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
      <p class="mt-4 text-gray-600">Loading authors...</p>
    </div>

    <!-- Table -->
    <div v-else class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Name
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Email
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Bio
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="author in authors" :key="author.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ author.name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ author.email }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-500 max-w-md truncate">
              {{ author.bio || '-' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <router-link
                :to="`/authors/${author.id}/edit`"
                class="text-blue-600 hover:text-blue-900 mr-4"
              >
                Edit
              </router-link>
              <button
                @click="deleteAuthor(author.id)"
                class="text-red-600 hover:text-red-900"
              >
                Delete
              </button>
            </td>
          </tr>
          <tr v-if="authors.length === 0">
            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
              No authors found
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center items-center gap-4 mt-6">
      <button
        @click="changePage(currentPage - 1)"
        :disabled="currentPage === 1"
        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition"
      >
        ← Previous
      </button>
      <span class="text-gray-700 font-medium">
        Page {{ currentPage }} of {{ lastPage }}
      </span>
      <button
        @click="changePage(currentPage + 1)"
        :disabled="currentPage === lastPage"
        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition"
      >
        Next →
      </button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AuthorList',
  data() {
    return {
      authors: [],
      loading: true,
      currentPage: 1,
      lastPage: 1,
      search: '',
    };
  },
  mounted() {
    this.fetchAuthors();
  },
  methods: {
    async fetchAuthors() {
      this.loading = true;
      try {
        const params = {
          page: this.currentPage,
          ...(this.search && { search: this.search }),
        };
        
        const response = await axios.get('/api/authors', { params });
        this.authors = response.data.data;
        this.currentPage = response.data.current_page;
        this.lastPage = response.data.last_page;
      } catch (error) {
        console.error('Error fetching authors:', error);
        alert('Failed to fetch authors. Please try again.');
      } finally {
        this.loading = false;
      }
    },
    async deleteAuthor(id) {
      if (!confirm('Are you sure you want to delete this author?')) return;
      
      try {
        await axios.delete(`/api/authors/${id}`);
        alert('Author deleted successfully!');
        this.fetchAuthors();
      } catch (error) {
        console.error('Error deleting author:', error);
        alert('Cannot delete this author because there are still books associated with them. Please delete or reassign the books first.');
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.lastPage) {
        this.currentPage = page;
        this.fetchAuthors();
      }
    },
    goBack() {
      // Kembali ke dashboard
      this.$router.push('/dashboard');
    },
  },
};
</script>