<script>
    import { Pie } from 'vue-chartjs';

    export default {
        extends: Pie,
        name: "pie",
        props: ['labels', 'datasets', 'options'],
        mounted() {
            console.log({labels: this.labels, datasets: this.datasets});
            this.renderChart(
                {
                  labels: this.labels,
                  datasets: this.datasets
                },
              this.options
            );

        }
    }
</script>
