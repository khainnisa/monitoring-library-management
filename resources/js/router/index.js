import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

// Import components
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import Register from '../views/Register.vue';

// Books
import BookList from '../components/BookList.vue';
import BookForm from '../components/BookForm.vue';

// Authors
import AuthorList from '../components/AuthorList.vue';
import AuthorForm from '../components/AuthorForm.vue';

// Categories
import CategoryList from '../components/CategoryList.vue';
import CategoryForm from '../components/CategoryForm.vue';

// Members
import MemberList from '../components/MemberList.vue';
import MemberForm from '../components/MemberForm.vue';

// Borrowings
import BorrowingList from '../components/BorrowingList.vue';
import BorrowingForm from '../components/BorrowingForm.vue';

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresGuest: true }
  },
  {
  path: '/register',
  name: 'register',
  component: Register,
  meta: { requiresGuest: true }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  
  // Books Routes
  {
    path: '/books',
    name: 'books',
    component: BookList,
    meta: { requiresAuth: true }
  },
  {
    path: '/books/create',
    name: 'books-create',
    component: BookForm,
    meta: { requiresAuth: true }
  },
  {
    path: '/books/:id/edit',
    name: 'books-edit',
    component: BookForm,
    meta: { requiresAuth: true }
  },
  
  // Authors Routes
  {
    path: '/authors',
    name: 'authors',
    component: AuthorList,
    meta: { requiresAuth: true }
  },
  {
    path: '/authors/create',
    name: 'authors-create',
    component: AuthorForm,
    meta: { requiresAuth: true }
  },
  {
    path: '/authors/:id/edit',
    name: 'authors-edit',
    component: AuthorForm,
    meta: { requiresAuth: true }
  },
  
  // Categories Routes
  {
    path: '/categories',
    name: 'categories',
    component: CategoryList,
    meta: { requiresAuth: true }
  },
  {
    path: '/categories/create',
    name: 'categories-create',
    component: CategoryForm,
    meta: { requiresAuth: true }
  },
  {
    path: '/categories/:id/edit',
    name: 'categories-edit',
    component: CategoryForm,
    meta: { requiresAuth: true }
  },
  
  // Members Routes
  {
    path: '/members',
    name: 'members',
    component: MemberList,
    meta: { requiresAuth: true }
  },
  {
    path: '/members/create',
    name: 'members-create',
    component: MemberForm,
    meta: { requiresAuth: true }
  },
  {
    path: '/members/:id/edit',
    name: 'members-edit',
    component: MemberForm,
    meta: { requiresAuth: true }
  },
  
  // Borrowings Routes
  {
    path: '/borrowings',
    name: 'borrowings',
    component: BorrowingList,
    meta: { requiresAuth: true }
  },
  {
    path: '/borrowings/create',
    name: 'borrowings-create',
    component: BorrowingForm,
    meta: { requiresAuth: true }
  },
  {
    path: '/borrowings/:id/edit',
    name: 'borrowings-edit',
    component: BorrowingForm,
    meta: { requiresAuth: true }
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation Guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  
  // Check if route requires authentication
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login' });
  } 
  // Check if route requires guest (not authenticated)
  else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next({ name: 'dashboard' });
  } 
  else {
    next();
  }
});

export default router;