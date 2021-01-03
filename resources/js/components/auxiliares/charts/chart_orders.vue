
<script>
import { Bar } from 'vue-chartjs'
export default {
    name: "chart_orders",
    extends: Bar,
    data: () => ({
        num_delivered:0,
        num_canceled:0,
        num_ready:0,
        num_transit:0,
        num_preparing:0,
        num_holding:0,
    }),
    methods:{
        getOrders() {
            axios.get('/api/orders').then((response)=>{
                this.orders = response.data.data
                this.orders.forEach(value =>
                {
                    switch (value.status){
                        case 'D':
                            value.status = "Delivered";
                            this.num_delivered ++;
                            break;
                        case 'H':
                            value.status = "Holding";
                            this.num_holding ++;
                            break;
                        case 'C':
                            value.status = "Canceled";
                            this.num_canceled ++;
                            break;
                        case 'T':
                            value.status = "Transit";
                            this.num_transit ++;
                            break;
                        case 'P':
                            value.status = "Preparing";
                            this.num_preparing ++;
                            break;
                        case 'R':
                            value.status = "Ready";
                            this.num_ready ++;
                            break;
                    }

                })
            }).then(()=>{
                this.renderChart({
                    labels: ["Delivered", "Canceled", "Ready", "Transit", "Preparing", "Holding"],
                    datasets: [{
                        label:'# of orders',
                        data: [this.num_delivered, this.num_canceled, this.num_ready, this.num_transit, this.num_preparing, this.num_holding],
                        backgroundColor: 'rgba(255, 206, 86, 0.6)',
                    }],
                    options: {
                        scales: {
                            yAxes: [
                                {
                                    ticks: {
                                        beginAtZero: true,
                                    }
                                }]
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                })
            });
        },
    },created() {
        this.getOrders()
    },
    mounted() {
        this.getOrders()

    },
    watch:{
        data: function() {
            //this.renderChart(this.data, this.options);
            this.renderChart();
        }
    }
}
</script>

<style scoped>

</style>
