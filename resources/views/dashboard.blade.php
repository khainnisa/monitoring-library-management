<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Library Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .navbar {
            background: #667eea;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 10px 20px;
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .tab-btn.active {
            background: #667eea;
            color: white;
        }
        .content-section {
            display: none;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .content-section.active { display: block; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
        }
        .btn {
            padding: 8px 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px;
        }
        .btn-danger { background: #e74c3c; }
        .btn-success { background: #27ae60; }
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Library Management System</h2>
        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>

    <div class="container">
        <div class="tab-buttons">
            <button class="tab-btn active" onclick="showTab('books')">Books</button>
            <button class="tab-btn" onclick="showTab('authors')">Authors</button>
            <button class="tab-btn" onclick="showTab('categories')">Categories</button>
        </div>

        <div id="books" class="content-section active">
            <h3>Books Management</h3>
            <button class="btn btn-success" onclick="alert('Add Book Form')">Add New Book</button>
            <table id="booksTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="authors" class="content-section">
            <h3>Authors Management</h3>
            <button class="btn btn-success" onclick="alert('Add Author Form')">Add New Author</button>
            <table id="authorsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="categories" class="content-section">
            <h3>Categories Management</h3>
            <button class="btn btn-success" onclick="alert('Add Category Form')">Add New Category</button>
            <table id="categoriesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        
        if (!token) {
            window.location.href = '/';
        }

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        function showTab(tabName) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
            
            if (tabName === 'books') loadBooks();
            if (tabName === 'authors') loadAuthors();
            if (tabName === 'categories') loadCategories();
        }

        async function loadBooks() {
            try {
                const response = await axios.get('/api/books');
                const tbody = document.querySelector('#booksTable tbody');
                tbody.innerHTML = '';
                
                response.data.data.forEach(book => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${book.id}</td>
                            <td>${book.title}</td>
                            <td>${book.author?.name || 'N/A'}</td>
                            <td>${book.category?.name || 'N/A'}</td>
                            <td>${book.stock}</td>
                            <td>
                                <button class="btn" onclick="editBook(${book.id})">Edit</button>
                                <button class="btn btn-danger" onclick="deleteBook(${book.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error('Error loading books:', error);
            }
        }

        async function loadAuthors() {
            try {
                const response = await axios.get('/api/authors');
                const tbody = document.querySelector('#authorsTable tbody');
                tbody.innerHTML = '';
                
                response.data.data.forEach(author => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${author.id}</td>
                            <td>${author.name}</td>
                            <td>${author.email}</td>
                            <td>
                                <button class="btn" onclick="editAuthor(${author.id})">Edit</button>
                                <button class="btn btn-danger" onclick="deleteAuthor(${author.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error('Error loading authors:', error);
            }
        }

        async function loadCategories() {
            try {
                const response = await axios.get('/api/categories');
                const tbody = document.querySelector('#categoriesTable tbody');
                tbody.innerHTML = '';
                
                response.data.data.forEach(category => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${category.id}</td>
                            <td>${category.name}</td>
                            <td>${category.description || 'N/A'}</td>
                            <td>
                                <button class="btn" onclick="editCategory(${category.id})">Edit</button>
                                <button class="btn btn-danger" onclick="deleteCategory(${category.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        async function logout() {
            try {
                await axios.post('/api/logout');
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');
                window.location.href = '/';
            }
        }

        // Load initial data
        loadBooks();
    </script>
</body>
</html>