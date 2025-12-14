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
    // INIT AUTH (AMBIL DARI LOCALSTORAGE)
    initializeAuth() {
      const token = localStorage.getItem("token");
      const user = localStorage.getItem("user");

      if (token && user) {
        this.token = token;
        this.user = JSON.parse(user);

        axios.defaults.headers.common[
          "Authorization"
        ] = `Bearer ${token}`;

        console.log("✅ Auth initialized");
      }
    },

    // LOGIN
    async login(credentials) {
      try {
        const response = await axios.post("/api/login", credentials);

        this.token = response.data.token;
        this.user = response.data.user;

        localStorage.setItem("token", this.token);
        localStorage.setItem("user", JSON.stringify(this.user));

        axios.defaults.headers.common[
          "Authorization"
        ] = `Bearer ${this.token}`;

        return { success: true };
      } catch (error) {
        return {
          success: false,
          message:
            error.response?.data?.message ||
            "Login gagal",
        };
      }
    },

    // REGISTER  
    async register(data) {
      try {
        const response = await axios.post("/api/register", data);

        this.token = response.data.token;
        this.user = response.data.user;

        localStorage.setItem("token", this.token);
        localStorage.setItem("user", JSON.stringify(this.user));

        axios.defaults.headers.common[
          "Authorization"
        ] = `Bearer ${this.token}`;

        return { success: true };
      } catch (error) {
        return {
          success: false,
          message:
            error.response?.data?.message ||
            "Registrasi gagal",
        };
      }
    },

    // LOGOUT
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

        delete axios.defaults.headers.common["Authorization"];
      }
    },

    // Tambahkan method ini setelah logout()
    clearAuth() {
      this.token = null;
      this.user = null;
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      delete axios.defaults.headers.common["Authorization"];
    },
  },
});
