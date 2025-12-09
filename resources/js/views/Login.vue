<template>
    <div class="login-container">
        <div class="login-box">
            <h1>Library Management System</h1>
            <h2>Login</h2>

            <div v-if="errorMessage" class="alert alert-error">
                {{ errorMessage }}
            </div>

            <form @submit.prevent="handleLogin">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        placeholder="Masukkan email"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        placeholder="Masukkan password"
                        required
                    />
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="loading"
                >
                    {{ loading ? "Loading..." : "Login" }}
                </button>
            </form>

            <p class="register-link">
                Belum punya akun?
                <router-link to="/register">Daftar di sini</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
    email: "",
    password: "",
});

const loading = ref(false);
const errorMessage = ref("");

const handleLogin = async () => {
    loading.value = true;
    errorMessage.value = "";

    const result = await authStore.login(form.value);

    if (result.success) {
        router.push("/dashboard");
    } else {
        errorMessage.value = result.message;
    }

    loading.value = false;
};
</script>

<style scoped>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.login-box {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 400px;
}

h1 {
    color: #667eea;
    font-size: 24px;
    text-align: center;
    margin-bottom: 10px;
}

h2 {
    color: #333;
    font-size: 28px;
    text-align: center;
    margin-bottom: 30px;
}

.alert {
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-error {
    background-color: #fee;
    color: #c33;
    border: 1px solid #fcc;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: #667eea;
}

.btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.register-link {
    text-align: center;
    margin-top: 20px;
    color: #666;
}

.register-link a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.register-link a:hover {
    text-decoration: underline;
}
</style>
