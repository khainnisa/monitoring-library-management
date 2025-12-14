<template>
    <div class="min-h-screen bg-slate-50">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-8">
                <div class="flex justify-between items-center h-20">
                    <router-link to="/dashboard" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                            L
                        </div>
                        <span class="text-xl font-bold text-gray-900">LibraryHub</span>
                    </router-link>

                    <div class="flex items-center gap-1">
                        <router-link 
                            to="/books" 
                            class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium transition"
                            active-class="bg-blue-50 text-blue-600"
                        >
                            Books
                        </router-link>
                        <router-link 
                            to="/authors" 
                            class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium transition"
                            active-class="bg-blue-50 text-blue-600"
                        >
                            Authors
                        </router-link>
                        <router-link 
                            to="/categories" 
                            class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium transition"
                            active-class="bg-blue-50 text-blue-600"
                        >
                            Categories
                        </router-link>
                        <router-link 
                            to="/members" 
                            class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium transition"
                            active-class="bg-blue-50 text-blue-600"
                        >
                            Members
                        </router-link>
                        <router-link 
                            to="/borrowings" 
                            class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium transition"
                            active-class="bg-blue-50 text-blue-600"
                        >
                            Borrowings
                        </router-link>

                        <div class="w-px h-6 bg-gray-300 mx-2"></div>

                        <button 
                            @click="logout" 
                            class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition"
                        >
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-8 py-10">
            <!-- Welcome Section -->
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Good {{ timeOfDay }}, {{ authStore.user?.name || "Admin" }}
                </h1>
                <p class="text-gray-600">Here's your library overview for today</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-5 gap-6 mb-10">
                <!-- Books -->
                <router-link to="/books" class="group">
                    <div class="bg-white rounded-xl p-6 border-2 border-transparent hover:border-blue-200 transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-500 group-hover:text-blue-600 transition">View all →</span>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ loading ? '...' : stats.books }}
                        </div>
                        <div class="text-sm text-gray-600">Total Books</div>
                    </div>
                </router-link>

                <!-- Authors -->
                <router-link to="/authors" class="group">
                    <div class="bg-white rounded-xl p-6 border-2 border-transparent hover:border-emerald-200 transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-500 group-hover:text-emerald-600 transition">View all →</span>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ loading ? '...' : stats.authors }}
                        </div>
                        <div class="text-sm text-gray-600">Total Authors</div>
                    </div>
                </router-link>

                <!-- Categories -->
                <router-link to="/categories" class="group">
                    <div class="bg-white rounded-xl p-6 border-2 border-transparent hover:border-purple-200 transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-500 group-hover:text-purple-600 transition">View all →</span>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ loading ? '...' : stats.categories }}
                        </div>
                        <div class="text-sm text-gray-600">Categories</div>
                    </div>
                </router-link>

                <!-- Members -->
                <router-link to="/members" class="group">
                    <div class="bg-white rounded-xl p-6 border-2 border-transparent hover:border-amber-200 transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-500 group-hover:text-amber-600 transition">View all →</span>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ loading ? '...' : stats.members }}
                        </div>
                        <div class="text-sm text-gray-600">Members</div>
                    </div>
                </router-link>

                <!-- Borrowings -->
                <router-link to="/borrowings" class="group">
                    <div class="bg-white rounded-xl p-6 border-2 border-transparent hover:border-rose-200 transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-500 group-hover:text-rose-600 transition">View all →</span>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ loading ? '...' : stats.borrowings }}
                        </div>
                        <div class="text-sm text-gray-600">Active Loans</div>
                    </div>
                </router-link>
            </div>

            <!-- Recent Activity & Popular Section -->
            <div class="grid grid-cols-3 gap-6">
                <!-- Recent Borrowings -->
                <div class="col-span-2 bg-white rounded-2xl shadow-sm border p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Recent Borrowings</h2>
                        <router-link to="/borrowings" class="text-sm text-blue-600 hover:text-blue-700">
                            View all →
                        </router-link>
                    </div>
                    
                    <div v-if="loadingActivity" class="text-center py-8 text-gray-500">
                        Loading...
                    </div>
                    
                    <div v-else-if="recentBorrowings.length === 0" class="text-center py-8 text-gray-500">
                        No recent borrowings
                    </div>
                    
                    <div v-else class="space-y-4">
                        <div 
                            v-for="borrowing in recentBorrowings" 
                            :key="borrowing.id"
                            class="flex items-center gap-4 p-4 rounded-lg hover:bg-gray-50 transition"
                        >
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ borrowing.book?.title || 'Unknown Book' }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    by {{ borrowing.member?.name || 'Unknown Member' }}
                                </p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span 
                                    :class="[
                                        'px-2 py-1 text-xs font-medium rounded',
                                        borrowing.status === 'borrowed' ? 'bg-blue-100 text-blue-700' :
                                        borrowing.status === 'returned' ? 'bg-green-100 text-green-700' :
                                        'bg-red-100 text-red-700'
                                    ]"
                                >
                                    {{ borrowing.status }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ formatDate(borrowing.borrow_date) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Books -->
                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Top Books</h2>
                        <router-link to="/books" class="text-sm text-blue-600 hover:text-blue-700">
                            View all →
                        </router-link>
                    </div>
                    
                    <div v-if="loadingBooks" class="text-center py-8 text-gray-500">
                        Loading...
                    </div>
                    
                    <div v-else-if="topBooks.length === 0" class="text-center py-8 text-gray-500">
                        No books available
                    </div>
                    
                    <div v-else class="space-y-4">
                        <div 
                            v-for="(book, index) in topBooks" 
                            :key="book.id"
                            class="flex items-start gap-3"
                        >
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 font-bold text-gray-600 text-sm">
                                {{ index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ book.title }}
                                </p>
                                <p class="text-xs text-gray-600 truncate">
                                    {{ book.author?.name || 'Unknown Author' }}
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-gray-500">
                                        Stock: {{ book.stock }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuthStore } from "../stores/auth";
import axios from "axios";

export default {
    name: "Dashboard",
    data() {
        return {
            authStore: useAuthStore(),
            loading: true,
            loadingActivity: true,
            loadingBooks: true,
            stats: {
                books: 0,
                authors: 0,
                categories: 0,
                members: 0,
                borrowings: 0,
            },
            recentBorrowings: [],
            topBooks: [],
        };
    },
    computed: {
        timeOfDay() {
            const hour = new Date().getHours();
            if (hour < 12) return "morning";
            if (hour < 18) return "afternoon";
            return "evening";
        }
    },
    mounted() {
        this.fetchStats();
        this.fetchRecentBorrowings();
        this.fetchTopBooks();
    },
    methods: {
        async fetchStats() {
            this.loading = true;
            try {
                const [books, authors, categories, members, borrowings] = await Promise.all([
                    axios.get("/api/books?per_page=1"),
                    axios.get("/api/authors?per_page=1"),
                    axios.get("/api/categories?per_page=1"),
                    axios.get("/api/members?per_page=1"),
                    axios.get("/api/borrowings?per_page=1"),
                ]);

                // Handle different response formats
                this.stats.books = books.data.total || books.data.data?.length || 0;
                this.stats.authors = authors.data.total || authors.data.data?.length || 0;
                this.stats.categories = categories.data.total || categories.data.data?.length || 0;
                this.stats.members = members.data.total || members.data.data?.length || 0;
                this.stats.borrowings = borrowings.data.total || borrowings.data.data?.length || 0;

                console.log('Stats loaded:', this.stats);
            } catch (error) {
                console.error("Error fetching stats:", error);
            } finally {
                this.loading = false;
            }
        },
        async fetchRecentBorrowings() {
            this.loadingActivity = true;
            try {
                const response = await axios.get("/api/borrowings?per_page=5");
                this.recentBorrowings = response.data.data || [];
            } catch (error) {
                console.error("Error fetching recent borrowings:", error);
            } finally {
                this.loadingActivity = false;
            }
        },
        async fetchTopBooks() {
            this.loadingBooks = true;
            try {
                const response = await axios.get("/api/books?per_page=5");
                this.topBooks = response.data.data || [];
            } catch (error) {
                console.error("Error fetching top books:", error);
            } finally {
                this.loadingBooks = false;
            }
        },
        formatDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric' 
            });
        },
        async logout() {
                if (confirm("Are you sure you want to logout?")) {
                    // Logout dari store (akan clear token & state)
                    await this.authStore.logout();
                    
                    // Router guard akan otomatis redirect ke login
                    // karena isAuthenticated sudah false
                    this.$router.push({ name: "login" });
                }
            },
    },
};
</script>