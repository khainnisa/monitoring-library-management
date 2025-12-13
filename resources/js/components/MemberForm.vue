<template>
  <div class="container mx-auto px-4 py-8 max-w-3xl">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">
        {{ isEdit ? 'Edit Member' : 'Add New Member' }}
      </h1>
      <p class="text-gray-600 mt-2">
        {{ isEdit ? 'Update member information' : 'Fill in the form to add a new member' }}
      </p>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="bg-white shadow-md rounded-lg p-6">
      <!-- Membership Number -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Membership Number <span class="text-red-500">*</span>
        </label>
        <input
          v-model="formData.membership_number"
          type="text"
          placeholder="MEM-0001"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
        <p class="text-xs text-gray-500 mt-1">Format: MEM-0001</p>
        <p v-if="errors.membership_number" class="text-red-500 text-sm mt-1">
          {{ errors.membership_number[0] }}
        </p>
      </div>

      <!-- Full Name -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Full Name <span class="text-red-500">*</span>
        </label>
        <input
          v-model="formData.name"
          type="text"
          placeholder="Enter full name"
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
          Email Address <span class="text-red-500">*</span>
        </label>
        <input
          v-model="formData.email"
          type="email"
          placeholder="member@example.com"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
        <p v-if="errors.email" class="text-red-500 text-sm mt-1">
          {{ errors.email[0] }}
        </p>
      </div>

      <!-- Phone Number -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Phone Number <span class="text-red-500">*</span>
        </label>
        <input
          v-model="formData.phone"
          type="text"
          placeholder="081234567890"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
        <p class="text-xs text-gray-500 mt-1">Format: 08xxxxxxxxxx (Indonesian format)</p>
        <p v-if="errors.phone" class="text-red-500 text-sm mt-1">
          {{ errors.phone[0] }}
        </p>
      </div>

      <!-- Address -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Address <span class="text-red-500">*</span>
        </label>
        <textarea
          v-model="formData.address"
          rows="3"
          placeholder="Enter full address"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        ></textarea>
        <p v-if="errors.address" class="text-red-500 text-sm mt-1">
          {{ errors.address[0] }}
        </p>
      </div>

      <!-- Join Date -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
          Join Date <span class="text-red-500">*</span>
        </label>
        <input
          v-model="formData.join_date"
          type="date"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
        <p v-if="errors.join_date" class="text-red-500 text-sm mt-1">
          {{ errors.join_date[0] }}
        </p>
      </div>

      <!-- Status -->
      <div class="mb-6">
        <label class="block text-gray-700 font-semibold mb-2">
          Status <span class="text-red-500">*</span>
        </label>
        <select
          v-model="formData.status"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        >
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
        <p v-if="errors.status" class="text-red-500 text-sm mt-1">
          {{ errors.status[0] }}
        </p>
      </div>

      <!-- Buttons -->
      <div class="flex gap-4">
        <button
          type="submit"
          :disabled="loading"
          class="flex-1 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition font-semibold"
        >
          {{ loading ? 'Saving...' : isEdit ? 'Update Member' : 'Create Member' }}
        </button>
        <button
          type="button"
          @click="$router.push('/members')"
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
  name: 'MemberForm',
  data() {
    return {
      formData: {
        membership_number: '',
        name: '',
        email: '',
        phone: '',
        address: '',
        join_date: '',
        status: 'active',
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
      this.fetchMember(id);
    }
  },
  methods: {
    async fetchMember(id) {
      try {
        const response = await axios.get(`/api/members/${id}`);
        const member = response.data;
        
        // Format date untuk input type="date" (YYYY-MM-DD)
        this.formData = {
          membership_number: member.membership_number,
          name: member.name,
          email: member.email,
          phone: member.phone,
          address: member.address,
          join_date: this.formatDateForInput(member.join_date),
          status: member.status,
        };
      } catch (error) {
        console.error('Error fetching member:', error);
        alert('Failed to fetch member data');
        this.$router.push('/members');
      }
    },
    formatDateForInput(dateString) {
      if (!dateString) return '';
      
      // Jika format tanggal dari API adalah "YYYY-MM-DD", langsung return
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
          await axios.put(`/api/members/${id}`, this.formData);
          alert('Member updated successfully!');
        } else {
          await axios.post('/api/members', this.formData);
          alert('Member created successfully!');
        }
        this.$router.push('/members');
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          alert('Failed to save member. Please try again.');
        }
        console.error('Error saving member:', error);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>