<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
        </div>

        <div class="container-fluid">
            <div class="row g-2">
                <div class="col">
                    <div class="p-3 bg-light fs-6 shadow p-3 mb-5 bg-body rounded fw-bold text-uppercase">
                        <span class="text-primary fs-1">{{ jobs.g_jobs_today }}</span>
                        <div>sent jobs to google today</div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 bg-light fs-6 shadow p-3 mb-5 bg-body rounded fw-bold text-uppercase">
                        <span class="text-primary fs-1">{{ jobs.g_jobs_week }}</span>
                        <div>sent jobs to google this week</div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 bg-light fs-6 shadow p-3 mb-5 bg-body rounded fw-bold text-uppercase">
                        <span class="text-primary fs-1">{{ jobs.g_jobs_month }}</span>
                        <div>sent jobs to google this month</div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 bg-light fs-6 shadow p-3 mb-5 bg-body rounded fw-bold text-uppercase">
                        <span class="text-primary fs-1">{{ jobs.g_jobs_year }}</span>
                        <div>sent jobs to google this year</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-start bg-light shadow p-3 mb-5 bg-body rounded">
                <jobs-per-day-line-chart class="col-8"></jobs-per-day-line-chart>
                <jobs-total-pie-chart class="col-4"></jobs-total-pie-chart>
            </div>
            <div class="row bg-light shadow p-3 mb-5 bg-body rounded">
                <jobs-per-week-line-chart class="col-11"></jobs-per-week-line-chart>
            </div>
        </div>
    </div>
</template>

<script>
import JobsPerDayLineChart from '../plotly/JobsPerDayLineChart.vue'
import JobsPerWeekLineChart from '../plotly/JobsPerWeekLineChart.vue'
import JobsTotalPieChart from '../plotly/JobsTotalPieChart.vue'
export default {
    components: {
        JobsPerDayLineChart,
        JobsPerWeekLineChart,
        JobsTotalPieChart
    },
    data(){
        return {
            jobs: {
                "g_jobs_today": 0,
                "g_jobs_week": 0,
                "g_jobs_month": 0,
                "g_jobs_year": 0,
            },
        }
    },
    mounted(){
        this.widgets()
    },
    methods: {
        widgets(){
            axios.get(`api/v1/dashboard/widgets/jobs`)
                .then(response => {
                    this.jobs = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }
}
</script>

<style>

</style>
