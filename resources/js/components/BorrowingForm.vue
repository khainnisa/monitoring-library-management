<template>
  <div class="container mx-auto px-4 py-8 max-w-3xl">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">
        {{ isEdit ? 'Edit Borrowing' : 'Add New Borrowing' }}
      </h1>
      <p class="text-gray-600 mt-2">
        {{ isEdit ? 'Update borrowing information' : 'Fill in the form to record a new borrowing' }}
      </p>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="bg-white shadow-md rounded-lg p-6">
      <!-- Member -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Member <span class="text-red-500">*</span>
        </label>
        <select
          v-model="formData.member_id"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        >
          <option value="">-- Select Member --</option>
          <option v-for="member in members" :key="member.id" :value="member.id">
            {{ member.name }} ({{ member.membership_number }})
          </option>
        </select>
        <p v-if="errors.member_id" class="text-red-500 text-sm mt-1">
          {{ errors.member_id[0] }}
        </p>
      </div>

      <!-- Book -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Book <span class="text-red-500">*</span>
        </label>
        <select
          v-model="formData.book_id"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        >
          <option value="">-- Select Book --</option>
          <option v-for="book in books" :key="book.id" :value="book.id">
            {{ book.title }} - {{ book.author?.name || 'Unknown' }}
          </option>
        </select>
        <p v-if="errors.book_id" class="text-red-500 text-sm mt-1">
          {{ errors.book_id[0] }}
        </p>
      </div>

      <!-- Borrow Date -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Borrow Date <span class="text-red-500">*</span>
        </label>
        <input
          v-model="formData.borrow_date"
          type="date"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
        <p v-if="errors.borrow_date" class="text-red-500 text-sm mt-1">
          {{ errors.borrow_date[0] }}
        </p>
      </div>

      <!-- Due Date -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Due Date <span class="text-red-500">*</span>
        </label>
        <input
          v-model="formData.due_date"
          type="date"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
        <p v-if="errors.due_date" class="text-red-500 text-sm mt-1">
          {{ errors.due_date[0] }}
        </p>
      </div>

      <!-- Return Date (Optional) -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Return Date (if returned)
        </label>
        <input
          v-model="formData.return_date"
          type="date"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <p v-if="errors.return_date" class="text-red-500 text-sm mt-1">
          {{ errors.return_date[0] }}
        </p>
      </div>

      <!-- Status -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Status <span class="text-red-500">*</span>
        </label>
        <select
          v-model="formData.status"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        >
          <option value="borrowed">Borrowed</option>
          <option value="returned">Returned</option>
          <option value="overdue">Overdue</option>
        </select>
      </div>

      <!-- Fine Amount (Optional) -->
      <div class="mb-6">
        <label class="block text-gray-700 font-semibold mb-2">
          Fine Amount (Rp)
        </label>
        <input
          v-model="formData.fine_amount"
          type="number"
          min="0"
          step="1000"
          placeholder="0"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <p v-if="errors.fine_amount" class="text-red-500 text-sm mt-1">
          {{ errors.fine_amount[0] }}
        </p>
      </div>

      <!-- Buttons -->
      <div class="flex gap-4">
        <button
          type="submit"
          :disabled="loading"
          class="flex-1 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition font-semibold"
        >
          {{ loading ? 'Saving...' : isEdit ? 'Update Borrowing' : 'Create Borrowing' }}
        </button>
        <button
          type="button"
          @click="$router.push('/borrowings')"
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
  name: 'BorrowingForm',
  data() {
    return {
      formData: {
        member_id: '',
        book_id: '',
        borrow_date: '',
        due_date: '',
        return_date: '',
        status: 'borrowed',
        fine_amount: '',
      },
      members: [],
      books: [],
      errors: {},
      loading: false,
      isEdit: false,
    };
  },
  mounted() {
    this.fetchMembers();
    this.fetchBooks();
    
    const id = this.$route.params.id;
    if (id) {
      this.isEdit = true;
      this.fetchBorrowing(id);
    }
  },
  methods: {
    async fetchMembers() {
      try {
        const response = await axios.get('/api/members?per_page=100');
        this.members = response.data.data;
      } catch (error) {
        console.error('Error fetching members:', error);
      }
    },
    async fetchBooks() {
      try {
        const response = await axios.get('/api/books?per_page=100');
        this.books = response.data.data;
      } catch (error) {
        console.error('Error fetching books:', error);
      }
    },
    async fetchBorrowing(id) {
      try {
        const response = await axios.get(`/api/borrowings/${id}`);
        const borrowing = response.data;
        
        // Format semua tanggal untuk input type="date"
        this.formData = {
          member_id: borrowing.member_id,
          book_id: borrowing.book_id,
          borrow_date: this.formatDateForInput(borrowing.borrow_date),
          due_date: this.formatDateForInput(borrowing.due_date),
          return_date: this.formatDateForInput(borrowing.return_date),
          status: borrowing.status,
          fine_amount: borrowing.fine_amount || '',
        };
      } catch (error) {
        console.error('Error fetching borrowing:', error);
        alert('Failed to fetch borrowing data');
        this.$router.push('/borrowings');
      }
    },
    formatDateForInput(dateString) {
      if (!dateString) return '';
      
      // Jika sudah format YYYY-MM-DD, langsung return
      if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
        return dateString;
      }
      
      // Jika format lain, convert ke YYYY-MM-DD
      const date = new Date(dateString);
      if (isNaN(date.getTime())) return '';
      
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      
      return `${year}-${month}-${day}`;
    },
    async handleSubmit() {
      this.loading = true;
      this.errors = {};

      try {
        if (this.isEdit) {
          const id = this.$route.params.id;
          await axios.put(`/api/borrowings/${id}`, this.formData);
          alert('Borrowing updated successfully!');
        } else {
          await axios.post('/api/borrowings', this.formData);
          alert('Borrowing created successfully!');
        }
        this.$router.push('/borrowings');
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          alert('Failed to save borrowing. Please try again.');
        }
        console.error('Error saving borrowing:', error);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>