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
		Purchase
		</a>
		<div class="navbar-dropdown is-boxed">
			@if(Auth::check())
				<a href="/home" class="navbar-item">
					<i class="fa-solid fa-user"></i>&nbsp; Home
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
		<h3 class="title is-3">Shipping Information:<h3>
		<div class="field">
			<div class="control">
				<input class="input" type="text" placeholder="Shipping Address (street address)" v-model="shipping.address" />
			</div>
		</div>
		<div class="field">
			<div class="control">
				<input class="input" type="text" placeholder="Shipping Information (province, postal_code)" v-model="shipping.info" />
			</div>
		</div>

  		<section v-if="cart">
		  <table class="table is-fullwidth">
						<thead>
							<tr>
								<th>Name</th>
								<th>Quantity</th>
								<th>Price</th>
								<th>Image</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="product in cart.products" :key="product.id">
								<td>@{{ product.name }}</td>
								<td>@{{ product.pivot.product_quantity }}</td>
								<td>R @{{ product.price }}</td>
								<td>
									<figure class="image is-32x32">
										<img :src="'data:image/jpeg;base64,' +product.main_image" />
									</figure> 
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>Total:</td>
								<td>R @{{ cart.total_amount }}
							</tr>
						</tbody>
					</table>
					<a href="{{ redirect()->away('https://payfast.io/solutions/gateway/?gad_source=1&gclid=EAIaIQobChMIr_q-x_PzhgMVN4pQBh2B_wNNEAAYASAAEgLBMPD_BwE')->getTargetUrl() }}" class="button is-success" target="_blank" @click="payNow">Pay Now</a>

		</section>
	</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://kit.fontawesome.com/312a26b640.js" crossorigin="anonymous"></script>
<script type="module">
  import { createApp, ref } from 'vue';


  createApp({
    data() {
      return {
        greeting: 'Hello',
        name: 'Vue',
		shipping: {
			adress: '',
			info: ''
		},
		cart: null,
		
      }
    },
    created() {
		this.getUserCart();
    },
    methods: {
      getUserCart() {

		axios.get('/purchase/cart/')
            .then(response => {
                this.cart = response.data;
				console.log('Logout successful:', response.data);

            })
            .catch(error => {
                console.error('Error fetching products:', error);
            });
	  },
      payNow() {
		console.log(this.shipping);
		axios.post('/purchase/cart/pay', {shipping: this.shipping, cart: this.cart})
            .then(response => {
				alert("Succesfuly Paid");
            })
            .catch(error => {
                console.error('Error fetching products:', error);
            });
	  },
    },
  }).mount('#app')

</script>
<style lang="scss">
	@import "https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css";
</style>
