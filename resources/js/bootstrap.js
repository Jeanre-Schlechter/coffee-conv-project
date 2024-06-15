window.axios = require('axios');

// Set default headers for Axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
