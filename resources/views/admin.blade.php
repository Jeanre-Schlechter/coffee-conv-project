<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- Add Bulma CSS file -->
    <link rel="stylesheet" href="{{ mix('css/bulma.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-carousel@4.0.3/dist/css/bulma-carousel.min.css">
</head>
<body>

<div id="app">
<nav class="navbar is-transparent" role="navigation" aria-label="dropdown navigation">
     <a href="/home" class="navbar-item">
        <img src="https://worldofprintables.com/wp-content/uploads/2024/01/Free-Coffee-SVG.svg" alt="Free Coffee" width="100" height="100">
     </a>
     <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">
                Administrator Page
            </a>
            <div class="navbar-dropdown is-boxed">
                @if(Auth::check())
                    <a href="/home" class="navbar-item">
                        <i class="fa-solid fa-house"></i>&nbsp; Home
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item" v-on:click="logout">
                        <i class="fa-solid fa-user-slash"></i>&nbsp; Log Out
                    </a>
                @endif
            </div>
     </div>
    <a class="navbar-item">
        @if(Auth::check())
            {{ Auth::user()->name }}
        @endif
    </a>
</nav>
@if(Auth::check())
    @if(Auth::user()->isAdminUser())
    <div class="container">
        <nav class="breadcrumb has-bullet-separator is-centered" aria-label="breadcrumbs">
            <ul>
                <li v-for="item in breadcrumbItems" :key="item">
                    <a @click="changeView(item)">
                        @{{ item }}
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <section>
        <div class="container">
            <div class="columns">
                <div class="column is-10">
                    <h1 class="title is-2">@{{ selectedView === 'Products' ? 'Products' : (selectedView === 'Categories' ? 'Categories' : 'Users') }}</h1>
                </div>
                <div class="column">
                    <a class="button is-success" @click="openModal()">Click for new @{{ selectedView === 'Products' ? 'Product' : (selectedView === 'Categories' ? 'Category' : 'User') }}</a>
                </div>
            </div>
            <table class="table is-fullwidth">
                <thead>
                    <tr v-if="selectedView === 'Products'">
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Options</th>
                    </tr>
                    <tr v-else-if="selectedView === 'Categories'">
                        <th>Category ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Logo</th>
                        <th>Options</th>
                    </tr>
                    <tr v-else>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="selectedView === 'Products'" v-for="product in products" :key="product.id">
                        <td>@{{ product.id }}</td>
                        <td>@{{ product.name }}</td>
                        <td>@{{ product.description }}</td>
                        <td>@{{ product.price }}</td>
                        <td>@{{ product.qty }}</td>
                        <td>
                            <div class="dropdown is-hoverable">
                                <div class="dropdown-trigger">
                                    <button class="button" aria-haspopup="true" aria-controls="dropdown-menu-post">
                                        <span class="icon is-small">
                                            <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                </div>
                                <div class="dropdown-menu" id="dropdown-menu-post" role="menu">
                                    <div class="dropdown-content">
                                        <a @click="openModal(product)" class="dropdown-item" >
                                            Update
                                        </a>
                                        <a @click="deleteProduct(product)" class="dropdown-item">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr v-else-if="selectedView === 'Categories'" v-for="category in categories" :key="category.id">
                        <td>@{{ category.id }}</td>
                        <td>@{{ category.name }}</td>
                        <td>@{{ category.description }}</td>
                        <td>@{{ category.logo }} &nbsp;<i class="fa-solid" :class="category.logo"></i></td>
                        <td>
                            <div class="dropdown is-hoverable">
                                <div class="dropdown-trigger">
                                    <button class="button" aria-haspopup="true" aria-controls="dropdown-menu-post">
                                        <span class="icon is-small">
                                            <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                </div>
                                <div class="dropdown-menu" id="dropdown-menu-post" role="menu">
                                    <div class="dropdown-content">
                                        <a @click="openModal(category)" class="dropdown-item">
                                            Update
                                        </a>
                                        <a  @click="deleteCategory(category)" class="dropdown-item">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr v-else v-for="user in users" :key="user.id">
                        <td>@{{ user.id }}</td>
                        <td>@{{ user.name }}</td>
                        <td>@{{ user.email }}</td>
                        <td>@{{ user.role }}</td>
                        <td>
                            <div class="dropdown is-hoverable">
                                <div class="dropdown-trigger">
                                    <button class="button" aria-haspopup="true" aria-controls="dropdown-menu-post">
                                        <span class="icon is-small">
                                            <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                </div>
                                <div class="dropdown-menu" id="dropdown-menu-post" role="menu">
                                    <div class="dropdown-content">
                                        <a @click="openModal(user)" class="dropdown-item">
                                            Update
                                        </a>
                                        <a @click="deleteUser(user)" class="dropdown-item">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    @endif
    @if(!Auth::user()->isAdminUser())
        <div class="container">
            <p>You are not authorized to view this page!</p>
        </div>
    @endif
@endif
@if(!Auth::check())
    <div class="container">
        <p>You are not authorized to view this page!</p>
    </div>
@endif

    <div class="modal" :class="{ 'is-active': userModalVisible }">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="box">
                <h2 class="title">Register</h2>
                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Enter your name" v-model="userModalInfo.name">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Username</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Enter your username to login with" v-model="userModalInfo.username">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Enter your email" v-model="userModalInfo.email">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <div class="control">
                        <input class="input" type="password" placeholder="Enter your password" v-model="userModalInfo.password">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Confirm Password</label>
                    <div class="control">
                        <input class="input" type="password" placeholder="Confirm your password" v-model="userModalInfo.confirmPassword" :class="{ 'is-danger': !passwordsMatch }">
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button class="button is-primary" @click="registerUser()" :disabled="!passwordsMatch || !userModalInfo.password || !userModalInfo.username">Register</button>
                    </div>
                </div>
            </div>
        </div>
        <button class="modal-close is-large" aria-label="close" @click="userModalVisible = false"></button>
    </div>

    <div class="modal" :class="{ 'is-active': categoryModalVisible }">
        <div class="modal-background" v-on:click="categoryModalVisible = false"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">New Category</p>
            </header>
            <section class="modal-card-body">
                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input class="input" type="text" v-model="categoryModalInfo.name">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Description</label>
                    <div class="control">
                        <input class="input" type="text" v-model="categoryModalInfo.description">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Logo -> <i class="fa-solid" :class="categoryModalInfo.logo"></i></td></label>
                    <div class="control">
                        <input class="input" type="text" v-model="categoryModalInfo.logo">
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button v-if="!categoryModalInfo.id" class="button is-success" @click="addCategory()">Save</button>
                <button v-else class="button is-success" @click="editCategory()">Update</button>
                <button class="button" @click="categoryModalVisible = false">Cancel</button>
            </footer>
        </div>
        <button class="modal-close is-large" v-on:click="categoryModalVisible = false" aria-label="close"></button>
    </div>

    <div class="modal" :class="{ 'is-active': productModalVisible }">
        <div class="modal-background" v-on:click="productModalVisible = false"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">New Product</p>
            </header>
            <section class="modal-card-body">
                <div class="field">
                    <label class="label">Main Product Image</label>
                    <div v-if="!imageUrl" class="image-preview">
                        <figure class="image is-2by1">
                            <img v-bind:src="'data:image/jpeg;base64,' + productModalInfo.mainImage" />
                        </figure>
                    </div>
                    <div v-else>
                        <img :src="imageUrl" alt="Preview" style="max-width: 300px; max-height: 300px;">
                    </div>
                    <div class="file">
                        <label class="file-label">
                            <input class="file-input" type="file" @change="handleFileUpload" accept="image/*">
                            <span class="file-cta">
                            <span class="file-icon">
                                <i class="fas fa-upload"></i>
                            </span>
                            <span class="file-label">Choose a file…</span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input class="input" type="text" v-model="productModalInfo.name">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Description</label>
                    <div class="control">
                        <input class="input" type="text" v-model="productModalInfo.description">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Price</label>
                    <div class="control">
                        <input class="input" type="number" step=".01" v-model="productModalInfo.price">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Quantity</label>
                    <div class="control">
                        <input class="input" type="text" v-model="productModalInfo.qty">
                    </div>
                </div>
                <div class="field">
                <div class="dropdown is-hoverable">
                    <div class="dropdown-trigger">
                        <button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">
                        <span v-if="!productModalInfo.category">Category</span>
                        <span v-else>@{{ chosenCategory }}</span>
                        <span class="icon is-small">
                            <i class="fas fa-angle-down" aria-hidden="true"></i>
                        </span>
                        </button>
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu4" role="menu">
                        <div class="dropdown-content">
                            <div class="dropdown-item" v-for="category in categories">
                                <a v-on:click="chooseProductCategory(category)"> @{{ category.name }} </a>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                
                
            </section>
            <footer class="modal-card-foot">
                <button v-if="!productModalInfo.id" class="button is-success" @click="addProduct()">Save</button>
                <button v-else class="button is-success" @click="editProduct()">Update</button>
                <button class="button" @click="productModalVisible = false">Cancel</button>
            </footer>
        </div>
        <button class="modal-close is-large" v-on:click="productModalVisible = false" aria-label="close"></button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://kit.fontawesome.com/312a26b640.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bulma-carousel@4.0.3/dist/js/bulma-carousel.min.js"></script>

<script>
    const { createApp, ref } = Vue;

    let products = @json($products);
    let categories = @json($categories);
    let users = @json($users);

    function arrayBufferToBase64(buffer) {
        let binary = '';
        let bytes = new Uint8Array(buffer);
        let len = bytes.byteLength;
        for (let i = 0; i < len; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return window.btoa(binary);
    }

    class User {
        constructor(userData = {}) {
            this.id = userData.id || '';
            this.name = userData.name || '';
            this.surname = userData.surname || '';
            this.email = userData.email || '';
            this.username = userData.username || '';
            this.password = userData.password || '';
            this.confirmPassword = userData.confirmPassword || '';
        }
    }

    class Product {
        constructor(productData = {}) {
            this.id = productData.id || null;
            this.name = productData.name || '';
            this.description = productData.description || '';
            this.price = productData.price || 0;
            this.qty = productData.qty || 0;
            this.mainImage = productData.main_image || productData.mainImage || '';
            this.imageCollage = productData.imageCollage || '';
            this.category = productData.category || '';
        }
    }

    class Category {
        constructor(categoryData = {}) {
            this.id = categoryData.id || '';
            this.name = categoryData.name || '';
            this.description = categoryData.description || '';
            this.logo = categoryData.logo || '';
        }
    }

    let productObjects = products.map(productData => new Product(productData));
    let categoryObjects = categories.map(categoryData => new Category(categoryData));
    let userObjects = users.map(userData => new User(userData));

    console.log(productObjects); // Array of Product objects
    console.log(categoryObjects); // Array of Category objects
    console.log(userObjects); // Array of User objects
    createApp({
        data() {
            return {
                products: productObjects,
                categories: categoryObjects,
                users: userObjects,
                breadcrumbItems: ['Categories', 'Products', 'Users'],
                selectedView: 'Products', // Default view
                userModalInfo: new User(),
                productModalInfo: new Product(),
                categoryModalInfo: new Category(),
                productModalVisible: false,
                userModalVisible: false,
                categoryModalVisible: false,
                imageUrl: null,
                chosenCategory: null,
                mainImage: 'storage/images/1718470758_cof.jpg'
            }
        },
        computed: {
            passwordsMatch() {
                return this.userModalInfo.password === this.userModalInfo.confirmPassword;
            }
        },
        mounted() {

        },
        created() {
            console.log(products);
        },
        methods: {
            handleFileUpload(event) {
                var file = event.target.files[0];
                this.productModalInfo.mainImage = file;
                this.imageUrl = URL.createObjectURL(file);
            },
            openModal(selectedInfo = null) {
                if (this.selectedView === "Categories") {
                    this.categoryModalInfo = selectedInfo ? new Category(selectedInfo) : new Category();
                    this.categoryModalVisible = true;
                } else if(this.selectedView == "Products") {
                    console.log(selectedInfo);
                    this.productModalInfo = selectedInfo ? new Product(selectedInfo) : new Product();
                    console.log("haha", this.productModalInfo);
                    this.productModalVisible = true;
                } else {
                    this.userModalInfo = selectedInfo ? new User(selectedInfo) : new User();
                    this.userModalVisible = true;
                }
            },
            changeView(view) {
                this.selectedView = view;
                // You can perform any additional actions here, such as fetching data for the selected view
            },
            logout() {
                axios.post('/logout')
                .then(response => {
                    // Handle successful login
                    console.log('Logout successful:', response.data);
                })
                .catch(error => {
                    // Handle login error
                    console.error('Logout error:', error);
                });
            },
            closeRegisterModal() {
                console.log('Password:');
                this.isRegisterModalOpen = false;
            },
            openRegisterModal() {
                console.log('Password:');
                this.isRegisterModalOpen = true;
            },
            registerUser() {
                var userInfo = this.userModalInfo;

                axios.post('/user', userInfo)

                .then(response => {
                    console.log('Regestration successful:', response.data);
                    this.closeRegisterModal();
				    alert("Registered New User");
                })
                .catch(error => {
                    console.error('Regestration error:', error);
                });
            },
            addCategory() {
                var categoryInfo = this.categoryModalInfo;
                axios.post('/category', categoryInfo)

                .then(response => {
                    console.log('Category added successfully:', response.data);
                    this.categoryModalVisible = false;
				    alert("Added New Category");
                })
                .catch(error => {
                    console.error('Category added error:', error);
                });
            },
            addProduct() {
                var productInfo = this.productModalInfo;

                axios.post('/product', productInfo, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    console.log(response.data);
                    this.productModalVisible = false;
                    this.addProductModal = false;
				    alert("Added New Product");
                })
                .catch(error => {
                    console.error('Product added error:', error);
                });
            },
            editProduct() {
                var productInfo = this.productModalInfo;

                var productId = productInfo.id;
                console.log(productInfo);

                axios.put('/product/' + productId, productInfo, {

                })
                .then(response => {
                    console.log(response.data);
                    this.productModalVisible = false;
                })
                .catch(error => {
                    console.error('Product added error:', error);
                });
            },
            openUserModal(user){
                this.userModalInfo = new User(user);
                this.userModalVisible = true;
            },
            chooseProductCategory(category){
                console.log(category);
                this.chosenCategory = category.name;
                this.productModalInfo.category = category.id;

                console.log(this.productModalInfo.category);

            },
            editCategory() {
                var categoryInfo = this.categoryModalInfo;
                axios.put('/category/' + this.categoryModalInfo.id, categoryInfo)

                .then(response => {
                    console.log('Category updated successfully:', response.data);
                    this.categoryModalVisible = false;
                })
                .catch(error => {
                    console.error('Category updating error:', error);
                });
            },
            deleteCategory(cat) {
                axios.delete('/category/' + cat.id)

                .then(response => {
                    console.log('Category deleted successfully:', response.data);
                    this.categoryModalVisible = false;
                })
                .catch(error => {
                    console.error('Category updating error:', error);
                });
            },
            deleteUser(usr) {
                axios.delete('/user/' + usr.id)

                .then(response => {
                    console.log('Category deleted successfully:', response.data);
                    this.categoryModalVisible = false;
                })
                .catch(error => {
                    console.error('User updating error:', error);
                });
            },
            deleteProduct(prd) {
                axios.delete('/product/' + prd.id)

                .then(response => {
                    console.log('Product deleted successfully:', response.data);
                    this.categoryModalVisible = false;
                })
                .catch(error => {
                    console.error('Category updating error:', error);
                });
            },
        }
    }).mount('#app');
</script>

</body>
</html>
<style>
    .is-danger input {
    border-color: #ff3860; /* Red color for the border */
}
</style>
