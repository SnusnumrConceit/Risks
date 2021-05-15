<template>
	<Pie :labels="headers" :datasets="datasets"></Pie>
</template>

<script>
	import Pie from "./Base/Pie";

	export default {
	  components: { Pie },
	  name: "UsersAmountMetric",
	  props: {
		headers: {
		  required: true,
		  type: Array
		},
		label: {
		  type: String,
		  default: ''
		},
		data: {
		  type: Array,
		  required: true
		},
		colors: {
		  type: Array,
		  required: true
		}
	  },
	  data() {
		return {
		  labels: this.headers,
		  datasets: [{
			label: this.label,
			data: this.data,
			backgroundColor: this.colors
		  }]
		}
	  }
	}
</script>
