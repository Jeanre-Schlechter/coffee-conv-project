<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Add Bulma CSS file -->
    <link rel="stylesheet" href="{{ mix('css/bulma.css') }}">
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
                    <a class="navbar-item">
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
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div v-if="notifyError">
                    <div class="notification is-warning" :class="{ 'is-active': notifyError }">
                        <button class="delete" @click="notifyError = false"></button>
                        <p>@{{ notifyErrorMessage }} </p>
                    </div>
                </div>
                @if(!Auth::check())
                <h1 class="title">Login</h1>
                <div class="field">
                    <label class="label">Username</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Enter your username" v-model="username">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <div class="control">
                        <input class="input" type="password" placeholder="Enter your password" v-model="password">
                    </div>
                </div>
                
                <div class="field">
                    <div class="control">
                        <button class="button is-primary" @click="login">Login</button>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button class="button" v-on:click="openRegisterModal">Register</button>
                    </div>
                </div>
                @endif
                @if(Auth::check())
                <div class="field">
                    <div class="control">
                        <button class="button is-danger" v-on:click="logout">Logout</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Modal for registration -->
    <div class="modal" :class="{ 'is-active': isRegisterModalOpen }">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="box">
                <h2 class="title">Register</h2>
                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Enter your name" v-model="regName">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Username</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Enter your username to login with" v-model="regUsername">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Enter your email" v-model="regEmail">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <div class="control">
                        <input class="input" type="password" placeholder="Enter your password" v-model="regPassword">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Confirm Password</label>
                    <div class="control">
                        <input class="input" type="password" placeholder="Confirm your password" v-model="confirmPassword" :class="{ 'is-danger': !passwordsMatch }">
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button class="button is-primary" @click="register" :disabled="!passwordsMatch">Register</button>
                    </div>
                </div>
            </div>
        </div>
        <button class="modal-close is-large" aria-label="close" @click="closeRegisterModal"></button>
    </div>
</div>

<!-- Include Vue and other scripts here -->
<script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const { createApp, ref } = Vue;

    createApp({
        data() {
            return {
                username: '',
                password: '',
                regName: '',
                regEmail: '',
                regUsername: '',
                regPassword: '',
                confirmPassword: '',
                isRegisterModalOpen: false,
                notifyError: false,
                notifyErrorMessage: ''
            }
        },
        computed: {
            passwordsMatch() {
                return this.regPassword === this.confirmPassword;
            }
        },
        methods: {
            login() {
                axios.post('/login', {
                    username: this.username,
                    password: this.password
                })
                .then(response => {
                    // Handle successful login
                    console.log('Login successful:', response.data);
                    window.location.href = '/home';
                })
                .catch(error => {
                    // Handle login error
                    this.notifyError = true;
                    if (error.response && error.response.data && error.response.data.message) {
                        this.notifyErrorMessage = Object.values(error.response.data.errors).join(', ');
                    } else {
                        this.notifyErrorMessage = "An error occurred during login. Please try again";
                    }
                    console.error('Login error:', this.notifyErrorMessage);
                });
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
            register() {
                axios.post('/register', {
                    name: this.regName,
                    email: this.regEmail,
                    username: this.regUsername,
                    password: this.regPassword
                })
                .then(response => {
                    // Handle successful login
                    console.log('Regestration successful:', response.data);
                })
                .catch(error => {
                    // Handle login error
                    console.error('Regestration error:', error);
                });
            }
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
