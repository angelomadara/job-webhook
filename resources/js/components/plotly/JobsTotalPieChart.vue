<template>
    <div>
        <div id="jobs-total-pie-chart"></div>
        <div>Note: Valid jobs are jobs that are not expired yet as of today.</div>
    </div>
</template>

<script>
import ModeBar from './ModeBar'
export default {
    mounted(){
        axios.get('/api/v1/g-plotly/jobs-total-pie-chart').then(response => {
            var data = response.data;
            Plotly.newPlot('jobs-total-pie-chart', [data.pie], data.layout, ModeBar);
        })
    }
}
</script>

<style>

</style>
