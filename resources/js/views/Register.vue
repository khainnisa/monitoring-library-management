<template>
    <div class="register-container">
        <div class="register-box">
            <h1>Library Management System</h1>
            <h2>Registrasi</h2>

            <div v-if="errorMessage" class="alert alert-error">
                {{ errorMessage }}
            </div>

            <form @submit.prevent="handleRegister">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        placeholder="Masukkan nama lengkap"
                        required
                    />
                </div>

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
                        placeholder="Minimal 8 karakter"
                        required
                        minlength="8"
                    />
                </div>

                <div class="form-group">
                    <label for="password_confirmation"
                        >Konfirmasi Password</label
                    >
                    <input
                        type="password"
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        placeholder="Ulangi password"
                        required
                    />
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="loading"
                >
                    {{ loading ? "Loading..." : "Daftar" }}
                </button>
            </form>

            <p class="login-link">
                Sudah punya akun?
                <router-link to="/">Login di sini</router-link>
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
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const loading = ref(false);
const errorMessage = ref("");

const handleRegister = async () => {
    // Validasi password match
    if (form.value.password !== form.value.password_confirmation) {
        errorMessage.value = "Password dan konfirmasi password tidak sama";
        return;
    }

    loading.value = true;
    errorMessage.value = "";

    const result = await authStore.register(form.value);

    if (result.success) {
        router.push("/dashboard");
    } else {
        errorMessage.value = result.message;
    }

    loading.value = false;
};
</script>

<style scoped>
.register-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
}

.register-box {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 450px;
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

.login-link {
    text-align: center;
    margin-top: 20px;
    color: #666;
}

.login-link a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.login-link a:hover {
    text-decoration: underline;
}
</style>
