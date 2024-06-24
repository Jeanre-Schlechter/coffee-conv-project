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
  		<div class="container" v-if="purchases">
		  <table class="table is-fullwidth">
				<thead>
					<tr>
						<th>Purchase ID</th>
						<th>Purchase Total</th>
						<th>Paid</th>
						<th>Shipping Status</th>
						<th>Shipping Address</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="purchase in purchases" :key="purchase.id">
						<td>@{{ purchase.id }}</td>
						<td>@{{ purchase.total_amount }}</td>
						<td>
							<button class="button is-rounded is-small" disabled
									:class="{'is-success': purchase.is_paid, 'is-danger': !purchase.is_paid}">
								@{{ purchase.is_paid ? 'Yes' : 'No' }}
							</button>
						</td>
						<td>@{{ purchase.shipping_status }}</td>
						<td>@{{ purchase.shipping_address }}</td>
  
					</tr>
				</tbody>
			</table>
		</div>
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
		purchases: null,
		
      }
    },
    created() {
		this.getUserPurchases();
    },
    methods: {
		getUserPurchases() {

		axios.get('/purchases/user')
            .then(response => {
                this.purchases = response.data;
				console.log('Logout successful:', response.data);

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
