<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Books Management</h1>
      <router-link
        to="/books/create"
        class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition"
      >
        + Add New Book
      </router-link>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
      <div class="flex gap-4 flex-wrap">
        <input
          v-model="search"
          type="text"
          placeholder="Search by title or author..."
          class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          @keyup.enter="fetchBooks"
        />
        <select
          v-model="categoryFilter"
          class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          @change="fetchBooks"
        >
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
        <button
          @click="fetchBooks"
          class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition"
        >
          Search
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
      <p class="mt-4 text-gray-600">Loading books...</p>
    </div>

    <!-- Table -->
    <div v-else class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Title
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Author
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Category
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Stock
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Description
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="book in books" :key="book.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ book.title }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ book.author?.name || 'N/A' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                {{ book.category?.name || 'N/A' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <span
                :class="[
                  'px-3 py-1 text-xs font-semibold rounded-full',
                  book.stock > 10 ? 'bg-green-100 text-green-800' :
                  book.stock > 0 ? 'bg-yellow-100 text-yellow-800' :
                  'bg-red-100 text-red-800'
                ]"
              >
                {{ book.stock }} units
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
              {{ book.description || '-' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <router-link
                :to="`/books/${book.id}/edit`"
                class="text-blue-600 hover:text-blue-900 mr-4"
              >
                Edit
              </router-link>
              <button
                @click="deleteBook(book.id)"
                class="text-red-600 hover:text-red-900"
              >
                Delete
              </button>
            </td>
          </tr>
          <tr v-if="books.length === 0">
            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
              No books found
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
  name: 'BookList',
  data() {
    return {
      books: [],
      categories: [],
      loading: true,
      currentPage: 1,
      lastPage: 1,
      search: '',
      categoryFilter: '',
    };
  },
  mounted() {
    this.fetchCategories();
    this.fetchBooks();
  },
  methods: {
    async fetchCategories() {
      try {
        const response = await axios.get('/api/categories?per_page=100');
        this.categories = response.data.data;
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    async fetchBooks() {
      this.loading = true;
      try {
        const params = {
          page: this.currentPage,
          ...(this.search && { search: this.search }),
          ...(this.categoryFilter && { category_id: this.categoryFilter }),
        };
        
        const response = await axios.get('/api/books', { params });
        this.books = response.data.data;
        this.currentPage = response.data.current_page;
        this.lastPage = response.data.last_page;
      } catch (error) {
        console.error('Error fetching books:', error);
        alert('Failed to fetch books. Please try again.');
      } finally {
        this.loading = false;
      }
    },
    async deleteBook(id) {
      if (!confirm('Are you sure you want to delete this book?')) return;
      
      try {
        await axios.delete(`/api/books/${id}`);
        alert('Book deleted successfully!');
        this.fetchBooks();
      } catch (error) {
        console.error('Error deleting book:', error);
        alert('Failed to delete book. Please try again.');
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.lastPage) {
        this.currentPage = page;
        this.fetchBooks();
      }
    },
  },
};
</script>