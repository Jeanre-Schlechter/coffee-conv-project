<script type="importmap">
  {
    "imports": {
      "vue": "https://unpkg.com/vue@3/dist/vue.esm-browser.js"
    }
  }
</script>

<div id="app">
    
<nav class="navbar is-transparent" role="navigation" aria-label="dropdown navigation">
  <a href="/home" class="navbar-item">
  <img src="https://worldofprintables.com/wp-content/uploads/2024/01/Free-Coffee-SVG.svg" alt="Free Coffee" width="100" height="100">

  </a>

  <div class="navbar-item has-dropdown is-hoverable">
    <a class="navbar-link">
      Home
    </a>
    <div class="navbar-dropdown is-boxed">
		@if(Auth::check())
			<a href="/purchases" class="navbar-item">
				<i class="fa-solid fa-user"></i>&nbsp; Purchases
			</a>
			<a class="navbar-item" @click="fetchUserWishlist()">
				<i class="fa-solid fa-cart-shopping"></i>&nbsp; Cart
			</a>
			@if(Auth::user()->isAdminUser())
				<a href="/admin" class="navbar-item">
					<i class="fa-solid fa-screwdriver-wrench"></i>&nbsp; Administrator Page
				</a>
			@endif
			<hr class="navbar-divider">
			<a class="navbar-item" v-on:click="logout">
				<i class="fa-solid fa-user-slash"></i>&nbsp;Log Out
			</a>
		@endif
		@if(!Auth::check())
			<a href="/login" class="navbar-item">
				<i class="fa-solid fa-user-check"></i>&nbsp;Log In
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
<div class="container">
	<nav class="breadcrumb has-bullet-separator is-centered" aria-label="breadcrumbs">
	<ul>
		<li v-for="category in categories"><a v-on:click="changeCategory(category.name)"><i class="fa-solid" :class="category.logo"></i> &nbsp; @{{ category.name }} </a></li>
	</ul>
	</nav>
</div>

<section v-if="products" class="hero is-fullheight">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-multiline">
                <div v-for="(product, index) in products.data" :key="product.id" class="column is-3">
                    <div class="card">
                        <div class="card-image">
                            <figure class="image is-4by3">
                                <img :src="'data:image/jpeg;base64,' + product.main_image" alt="Product Image" /> 
                            </figure>
                        </div>
                        <div class="card-content">
                            <div class="media">
                                <div class="media-content">
                                    <p class="title is-4">@{{ product.name }}</p>
                                    <p class="subtitle is-6">R @{{ product.price }}</p>
                                </div>
                            </div>
                            <div class="content">
                                @{{ product.description }}
                            </div>
                        </div>
						<footer class="card-footer">
							<p class="card-footer-item">
							<span><a class="button" v-on:click="openAddProductToCartModal(product)"> Add To Cart </a> </span>
							</p>
						</footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div v-if="products">
	<nav v-if="products.last_page > 1" class="pagination is-centered" role="navigation" aria-label="pagination">
		<a v-if="products.links[0].url" @click.prevent="fetchProducts(products.links[0].url)" class="pagination-previous">Prev</a>
		<a v-if="products.links[products.links.length - 1].url" @click.prevent="fetchProducts(products.links[products.links.length - 1].url)" class="pagination-next">Next</a>
		<ul class="pagination-list">
			<li v-for="(link, index) in products.links.slice(1, products.links.length - 1)" :key="index">
				<a v-if="link.url" @click.prevent="fetchProducts(link.url)" class="pagination-link">@{{ link.label }}</a>
				<span v-else class="pagination-ellipsis">&hellip;</span>
			</li>
		</ul>
	</nav>

		<div class="modal" :class="{ 'is-active': wishlistModalVisible }">
			<div class="modal-background" v-on:click="wishlistModalVisible = false"></div>
			<div class="modal-card">
				<header class="modal-card-head">
					<p class="modal-card-title">Cart</p>
				</header>
				<section class="modal-card-body">
					<table class="table is-fullwidth" v-if="wishlistData">
						<thead>
							<tr>
								<th>Name</th>
								<th>Description</th>
								<th>Quantity</th>
								<th>Price</th>
								<th>Image</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="product in wishlistData.products" :key="product.id">
								<td>@{{ product.name }}</td>
								<td>@{{ product.description }}</td>
								<td>@{{ product.pivot.product_quantity }}</td>
								<td>R @{{ product.price }}</td>
								<td>
									<figure class="image is-32x32">
										<img :src="'data:image/jpeg;base64,' +product.main_image" />
									</figure> 
								</td>
								<td><button class="button is-warning" @click="removeProductFromCart(product)">Remove</button></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>Total:</td>
								<td>R @{{ wishlistData.total_amount }}
							</tr>
						</tbody>
					</table>
				</section>
				<footer class="modal-card-foot">
					<a href="/purchase" class="button is-success">Checkout</a>
					<button class="button" @click="wishlistModalVisible = false">Cancel</button>
				</footer>
			</div>
			<button class="modal-close is-large" v-on:click="wishlistModalVisible = false" aria-label="close"></button>
		</div>

		<div class="modal" :class="{ 'is-active': addProductModal }">
			<div class="modal-background" v-on:click="addProductModal = false"></div>
			<div class="modal-card">
				<header class="modal-card-head">
					<p class="modal-card-title">Add To Cart</p>
				</header>
				<section class="modal-card-body">
					<input class="input" type="number" min="1" max="product.qty" placeholder="Enter Amount" v-model="productCount"> </input>
				</section>
				<footer class="modal-card-foot">
					<button class="button is-success" @click="addProductToCart(selectedProduct)">Confirm</button>
					<button class="button" @click="addProductModal = false">Cancel</button>
				</footer>
			</div>
			<button class="modal-close is-large" v-on:click="addProductModal = false" aria-label="close"></button>
		</div>
</div>
</div>

</div>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-GX1E068M1B"></script>
<script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-GX1E068M1B'); </script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://kit.fontawesome.com/312a26b640.js" crossorigin="anonymous"></script>
<script type="module">
  import { createApp, ref } from 'vue';


  createApp({
    data() {
      return {
        greeting: 'Hello',
        name: 'Vue',
        rows: 3,
        columns: 5,
        products: [],
        categories: [],
		wishlistModalVisible: false,
		wishlistData: [],
		addProductModal: false,
		productCount: 0,
		selectedProduct: null
      }
    },
    created() {
		this.fetchProducts('/please');
      	console.log(this.products);
    },
    methods: {
      greet() {
        return this.greeting + ' ' + this.name + '!'
      },
	  logout() {
        axios.post('/logout')
			.then(response => {
				console.log('Logout successful:', response.data);
				
			})
			.catch(error => {
				console.error('Logout error:', error);
			});
		},
		changeCategory(category) {
			console.log(category);
			axios.get('/filter-products', { params: { category: category } })
				.then(response => {
					this.products = response.data;
					console.log('Logout successful:', response.data);
				})
				.catch(error => {
					console.error('Logout error:', error);
				});
		},
		fetchProducts(url) {
        axios.get(url)
            .then(response => {
                this.products = response.data.products;
				this.categories = response.data.categories;
				console.log('Logout successful:', response.data);

            })
            .catch(error => {
                console.error('Error fetching products:', error);
            });
    	},
		fetchUserWishlist() {

			axios.get('/wishlist')
			.then(response => {
				
				this.wishlistData = response.data;
				this.wishlistModalVisible = true;
				console.log('Logout successful:', this.wishlistData);
				
			})
			.catch(error => {

				console.error('Error fetching products:', error);

			});
    	},
		openAddProductToCartModal(product) {
			this.addProductModal = true;
			this.selectedProduct = product;
    	},
		addProductToCart(product) {
			axios.post('/cart/' + product.id, { qty :this.productCount})
			.then(response => {
				console.log('Logout successful:', response);
				this.addProductModal = false;
				alert("Added Product To cart");
			})
			.catch(error => {

				console.error('Error fetching products:', error);

			});
    	},
		removeProductFromCart(product) {
			axios.delete('/cart/' + product.id)
			.then(response => {
				console.log('Logout successful:', response);
				alert("Removed Product To cart");
			})
			.catch(error => {

				console.error('Error fetching products:', error);

			});
    	}
    },
    setup() {
      const message = ref('Hello Vue!')
      return {
        message
      }
    }
  }).mount('#app')

</script>
<style lang="scss">
	@import "https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css";
</style>
