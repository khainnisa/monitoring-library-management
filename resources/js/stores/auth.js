import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    token: null,
  }),
  
  getters: {
    isAuthenticated: (state) => !!state.token,
    getUser: (state) => state.user,
  },
  
  actions: {
    // TAMBAHKAN METHOD INI - PENTING!
    initializeAuth() {
      const token = localStorage.getItem("token");
      const user = localStorage.getItem("user");
      
      if (token && user) {
        this.token = token;
        this.user = JSON.parse(user);
        
        // Set axios default header
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        
        console.log("✅ Auth initialized from localStorage");
      } else {
        console.log("ℹ️ No saved auth found");
      }
    },
    
    async login(credentials) {
      try {
        const response = await axios.post("/api/login", credentials);
        this.token = response.data.token;
        this.user = response.data.user;
        
        localStorage.setItem("token", this.token);
        localStorage.setItem("user", JSON.stringify(this.user));
        
        // Set axios default header setelah login
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        
        console.log("✅ Login success");
        
        return { success: true };
      } catch (error) {
        console.error("❌ Login error:", error);
        return { 
          success: false, 
          message: error.response?.data?.message || "Login gagal" 
        };
      }
    },
    
    async logout() {
      try {
        await axios.post("/api/logout");
      } catch (error) {
        console.error("Logout error:", error);
      } finally {
        this.token = null;
        this.user = null;
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        
        // Hapus axios default header
        delete axios.defaults.headers.common['Authorization'];
        
        console.log("✅ Logged out");
      }
    },
  },
});