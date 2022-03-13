<template>
    <div id="jobs-per-week-line"></div>
</template>

<script>
import ModeBar from './ModeBar'
export default {
    mounted(){
        axios.get('/api/v1/g-plotly/past-seven-weeks-jobs-sent')
        .then(response => {
            var data = response.data;
            Plotly.newPlot('jobs-per-week-line',
                [
                    data.trace1,
                    data.trace2,
                    // data.trace3,
                    data.trace4,
                    // data.trace5,
                    data.trace6
                ],
                data.layout,
                ModeBar
            );
        })
    }
}
</script>

<style>

</style>
