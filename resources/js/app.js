/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./extensions/dropdown-keep-opened');

import Vue from 'vue';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('pie', require('./components/charts/Base/Pie').default);

Vue.component('users-amount-metric', require('./components/charts/UsersAmountMetric').default);
Vue.component('risks-amount-metric', require('./components/charts/RisksAmountMetric').default);
Vue.component('risks-factors-metric', require('./components/charts/RisksFactorsMetric').default);
Vue.component('risks-types-metric', require('./components/charts/RisksTypesMetric').default);
Vue.component('risks-statuses-metric', require('./components/charts/RisksStatusesMetric').default);
Vue.component('risks-divisions-metric', require('./components/charts/RisksDivisionsMetric').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
