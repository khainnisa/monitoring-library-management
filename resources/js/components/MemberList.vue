<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Members Management</h1>
      <router-link
        to="/members/create"
        class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition"
      >
        + Add New Member
      </router-link>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
      <div class="flex gap-4">
        <input
          v-model="search"
          type="text"
          placeholder="Search by name, email, or membership number..."
          class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          @keyup.enter="fetchMembers"
        />
        <select
          v-model="statusFilter"
          class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          @change="fetchMembers"
        >
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
        <button
          @click="fetchMembers"
          class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition"
        >
          Search
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
      <p class="mt-4 text-gray-600">Loading members...</p>
    </div>

    <!-- Table -->
    <div v-else class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Member #
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Name
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Email
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Phone
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="member in members" :key="member.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ member.membership_number }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ member.name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ member.email }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ member.phone }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                :class="[
                  'px-3 py-1 text-xs font-semibold rounded-full',
                  member.status === 'active'
                    ? 'bg-green-100 text-green-800'
                    : 'bg-red-100 text-red-800'
                ]"
              >
                {{ member.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <router-link
                :to="`/members/${member.id}/edit`"
                class="text-blue-600 hover:text-blue-900 mr-4"
              >
                Edit
              </router-link>
              <button
                @click="deleteMember(member.id)"
                class="text-red-600 hover:text-red-900"
              >
                Delete
              </button>
            </td>
          </tr>
          <tr v-if="members.length === 0">
            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
              No members found
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
  name: 'MemberList',
  data() {
    return {
      members: [],
      loading: true,
      currentPage: 1,
      lastPage: 1,
      search: '',
      statusFilter: '',
    };
  },
  mounted() {
    this.fetchMembers();
  },
  methods: {
    async fetchMembers() {
      this.loading = true;
      try {
        const params = {
          page: this.currentPage,
          ...(this.search && { search: this.search }),
          ...(this.statusFilter && { status: this.statusFilter }),
        };
        
        const response = await axios.get('/api/members', { params });
        this.members = response.data.data;
        this.currentPage = response.data.current_page;
        this.lastPage = response.data.last_page;
      } catch (error) {
        console.error('Error fetching members:', error);
        alert('Failed to fetch members. Please try again.');
      } finally {
        this.loading = false;
      }
    },
    async deleteMember(id) {
      if (!confirm('Are you sure you want to delete this member?')) return;
      
      try {
        await axios.delete(`/api/members/${id}`);
        alert('Member deleted successfully!');
        this.fetchMembers();
      } catch (error) {
        console.error('Error deleting member:', error);
        alert('Failed to delete member. Please try again.');
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.lastPage) {
        this.currentPage = page;
        this.fetchMembers();
      }
    },
  },
};
</script>