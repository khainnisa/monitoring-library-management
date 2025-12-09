<template>
    <div class="manager">
        <div class="header">
            <h2>✍️ Manage Authors</h2>
            <button @click="openAddModal" class="btn btn-primary">
                + Add New Author
            </button>
        </div>

        <div v-if="loading" class="loading">Loading...</div>

        <div v-else class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Bio</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="author in authors" :key="author.id">
                        <td>{{ author.id }}</td>
                        <td>{{ author.name }}</td>
                        <td>{{ author.email }}</td>
                        <td>{{ author.bio || "-" }}</td>
                        <td>
                            <button
                                @click="openEditModal(author)"
                                class="btn btn-sm btn-edit"
                            >
                                Edit
                            </button>
                            <button
                                @click="deleteAuthor(author.id)"
                                class="btn btn-sm btn-delete"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="modal" @click.self="closeModal">
            <div class="modal-content">
                <h3>{{ isEdit ? "Edit Author" : "Add New Author" }}</h3>
                <form @submit.prevent="saveAuthor">
                    <div class="form-group">
                        <label>Name</label>
                        <input v-model="form.name" type="text" required />
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input v-model="form.email" type="email" required />
                    </div>

                    <div class="form-group">
                        <label>Bio</label>
                        <textarea v-model="form.bio" rows="4"></textarea>
                    </div>

                    <div class="modal-actions">
                        <button
                            type="button"
                            @click="closeModal"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ isEdit ? "Update" : "Save" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const authors = ref([]);
const loading = ref(false);
const showModal = ref(false);
const isEdit = ref(false);
const form = ref({
    id: null,
    name: "",
    email: "",
    bio: "",
});

const fetchAuthors = async () => {
    loading.value = true;
    try {
        const response = await axios.get("/api/authors");
        authors.value = response.data.data;
    } catch (error) {
        alert("Failed to fetch authors");
    }
    loading.value = false;
};

const openAddModal = () => {
    isEdit.value = false;
    form.value = {
        id: null,
        name: "",
        email: "",
        bio: "",
    };
    showModal.value = true;
};

const openEditModal = (author) => {
    isEdit.value = true;
    form.value = {
        id: author.id,
        name: author.name,
        email: author.email,
        bio: author.bio || "",
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const saveAuthor = async () => {
    try {
        if (isEdit.value) {
            await axios.put(`/api/authors/${form.value.id}`, form.value);
            alert("Author updated successfully");
        } else {
            await axios.post("/api/authors", form.value);
            alert("Author created successfully");
        }
        closeModal();
        fetchAuthors();
    } catch (error) {
        alert(
            "Failed to save author: " +
                (error.response?.data?.message || error.message)
        );
    }
};

const deleteAuthor = async (id) => {
    if (!confirm("Are you sure you want to delete this author?")) return;

    try {
        await axios.delete(`/api/authors/${id}`);
        alert("Author deleted successfully");
        fetchAuthors();
    } catch (error) {
        alert("Failed to delete author");
    }
};

onMounted(() => {
    fetchAuthors();
});
</script>

<style scoped>
.manager {
    width: 100%;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

h2 {
    color: #333;
    margin: 0;
}

.loading {
    text-align: center;
    padding: 40px;
    color: #666;
    font-size: 18px;
}

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background-color: #f8f9fa;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
}

tbody tr:hover {
    background-color: #f8f9fa;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 14px;
    margin-right: 5px;
}

.btn-edit {
    background-color: #ffc107;
    color: #333;
}

.btn-edit:hover {
    background-color: #ffb300;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content h3 {
    margin-top: 0;
    color: #333;
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

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
}

.modal-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}
</style>
