import axios from "axios";

window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// ← TAMBAHKAN INI: Set base URL backend
// Deteksi otomatis: localhost untuk dev, IP VPS untuk production
window.axios.defaults.baseURL = 
    window.location.hostname === 'localhost' 
        ? 'http://localhost:8000'  // Development
        : 'http://103.150.191.185:8000';  // Production - IP VPS kamu

// ← TAMBAHKAN INI: Request interceptor untuk attach token
axios.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("token");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor untuk handle 401
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            console.error("❌ 401 Unauthorized - Token invalid/expired");
            
            // Clear auth data
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            
            // ← UBAH INI: Jangan pakai window.location (hard reload)
            // Pakai router untuk smooth transition
            if (window.location.pathname !== "/login") {
                window.location.href = "/login"; // Fallback jika router belum ready
            }
        }
        return Promise.reject(error);
    }
);

console.log("✅ Axios configured with base URL and interceptors");