<template>
    <div>
        <nav
            style="
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 1rem;
            "
        >
            <div
                style="
                    max-width: 1200px;
                    margin: 0 auto;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                "
            >
                <h1>📚 Library Management</h1>
                <div style="display: flex; align-items: center; gap: 15px">
                    <span>{{ authStore.getUser?.name }}</span>
                    <button
                        @click="handleLogout"
                        style="
                            background: rgba(255, 255, 255, 0.2);
                            color: white;
                            border: 2px solid white;
                            padding: 8px 20px;
                            border-radius: 5px;
                            cursor: pointer;
                            font-weight: 600;
                        "
                    >
                        Logout
                    </button>
                </div>
            </div>
        </nav>

        <div style="max-width: 1200px; margin: 30px auto; padding: 0 20px">
            <div
                style="
                    display: flex;
                    gap: 10px;
                    margin-bottom: 30px;
                    background: white;
                    padding: 10px;
                    border-radius: 10px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                "
            >
                <button
                    v-for="tab in tabs"
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    :style="{
                        flex: 1,
                        padding: '12px 24px',
                        border: 'none',
                        background:
                            activeTab === tab.id
                                ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
                                : 'transparent',
                        color: activeTab === tab.id ? 'white' : '#666',
                        fontSize: '16px',
                        fontWeight: 600,
                        cursor: 'pointer',
                        borderRadius: '5px',
                        transition: 'all 0.3s',
                    }"
                >
                    {{ tab.label }}
                </button>
            </div>

            <div
                style="
                    background: white;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                    min-height: 500px;
                "
            >
                <BooksManager v-if="activeTab === 'books'" />
                <AuthorsManager v-if="activeTab === 'authors'" />
                <CategoriesManager v-if="activeTab === 'categories'" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import BooksManager from "../components/BooksManager.vue";
import AuthorsManager from "../components/AuthorsManager.vue";
import CategoriesManager from "../components/CategoriesManager.vue";

const router = useRouter();
const authStore = useAuthStore();

const activeTab = ref("books");

const tabs = [
    { id: "books", label: "Books" },
    { id: "authors", label: "Authors" },
    { id: "categories", label: "Categories" },
];

const handleLogout = async () => {
    await authStore.logout();
    router.push("/");
};
</script>

<style scoped>
.dashboard {
    min-height: 100vh;
    background-color: #f5f5f5;
}

.navbar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
}

.user-section {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-name {
    font-weight: 500;
}

.btn-logout {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid white;
    padding: 8px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-logout:hover {
    background: white;
    color: #667eea;
}

.main-content {
    padding: 30px 20px;
}

.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    background: white;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.tab-button {
    flex: 1;
    padding: 12px 24px;
    border: none;
    background: transparent;
    color: #666;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    border-radius: 5px;
    transition: all 0.3s;
}

.tab-button:hover {
    background: #f0f0f0;
}

.tab-button.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.tab-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    min-height: 500px;
}
</style>
