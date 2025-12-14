<template>
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                {{ isEdit ? "Edit Book" : "Add New Book" }}
            </h1>
            <p class="text-gray-600 mt-2">
                {{
                    isEdit
                        ? "Update book information"
                        : "Fill in the form to add a new book"
                }}
            </p>
        </div>

        <!-- Form -->
        <form
            @submit.prevent="handleSubmit"
            class="bg-white shadow-md rounded-lg p-6"
        >
            <!-- Title -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input
                    v-model="formData.title"
                    type="text"
                    placeholder="Enter book title"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                />
                <p v-if="errors.title" class="text-red-500 text-sm mt-1">
                    {{ errors.title[0] }}
                </p>
            </div>

            <!-- Author -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">
                    Author <span class="text-red-500">*</span>
                </label>
                <select
                    v-model="formData.author_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                    <option value="">-- Select Author --</option>
                    <option
                        v-for="author in authors"
                        :key="author.id"
                        :value="author.id"
                    >
                        {{ author.name }}
                    </option>
                </select>
                <p v-if="errors.author_id" class="text-red-500 text-sm mt-1">
                    {{ errors.author_id[0] }}
                </p>
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <select
                    v-model="formData.category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                    <option value="">-- Select Category --</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
                <p v-if="errors.category_id" class="text-red-500 text-sm mt-1">
                    {{ errors.category_id[0] }}
                </p>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">
                    Description
                </label>
                <textarea
                    v-model="formData.description"
                    rows="4"
                    placeholder="Enter book description"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
                <p v-if="errors.description" class="text-red-500 text-sm mt-1">
                    {{ errors.description[0] }}
                </p>
            </div>

            <!-- Stock -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Stock <span class="text-red-500">*</span>
                </label>
                <input
                    v-model="formData.stock"
                    type="number"
                    min="0"
                    placeholder="0"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                />
                <p v-if="errors.stock" class="text-red-500 text-sm mt-1">
                    {{ errors.stock[0] }}
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button
                    type="submit"
                    :disabled="loading"
                    class="flex-1 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition font-semibold"
                >
                    {{
                        loading
                            ? "Saving..."
                            : isEdit
                            ? "Update Book"
                            : "Create Book"
                    }}
                </button>
                <button
                    type="button"
                    @click="$router.push('/books')"
                    class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400 transition font-semibold"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "BookForm",
    data() {
        return {
            formData: {
                title: "",
                author_id: "",
                category_id: "",
                description: "",
                stock: 0,
            },
            authors: [],
            categories: [],
            errors: {},
            loading: false,
            isEdit: false,
        };
    },
    mounted() {
        this.fetchAuthors();
        this.fetchCategories();

        const id = this.$route.params.id;
        if (id) {
            this.isEdit = true;
            this.fetchBook(id);
        }
    },
    methods: {
        async fetchAuthors() {
            try {
                const response = await axios.get("/api/authors?per_page=100");
                this.authors = response.data.data;
            } catch (error) {
                console.error("Error fetching authors:", error);
            }
        },
        async fetchCategories() {
            try {
                const response = await axios.get(
                    "/api/categories?per_page=100"
                );
                this.categories = response.data.data;
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },
        async fetchBook(id) {
            try {
                const { data: response } = await axios.get(`/api/books/${id}`);
                this.formData = {
                    title: response.data.title,
                    author_id: response.data.author_id,
                    category_id: response.data.category_id,
                    description: response.data.description || "",
                    stock: response.data.stock,
                };
            } catch (error) {
                console.error("Error fetching book:", error);
                alert("Failed to fetch book data");
                this.$router.push("/books");
            }
        },
        async handleSubmit() {
            this.loading = true;
            this.errors = {};

            try {
                if (this.isEdit) {
                    const id = this.$route.params.id;
                    await axios.put(`/api/books/${id}`, this.formData);
                    alert("Book updated successfully!");
                } else {
                    await axios.post("/api/books", this.formData);
                    alert("Book created successfully!");
                }
                this.$router.push("/books");
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert("Failed to save book. Please try again.");
                }
                console.error("Error saving book:", error);
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
