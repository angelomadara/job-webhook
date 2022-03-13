<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Sent Jobs to Arbeitsagentur  </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <!-- controls -->
            </div>
        </div>

        <div class="row justify-content-end mb-3">
            <div class="col-2" style="position:relative">
                <date-picker name="date" v-model="date" @dp-change="page(1)" :config="datePickerOptions" class="form-select" placeholder="YYYY-MM-DD" autocomplete="off"></date-picker>
            </div>
            <div class="col-3">
                <input v-model="search" @keyup="page(1)" class="form-control" placeholder="Search job..." type="text">
            </div>
            <!-- <div class="col-2"><p>{{ jobcount == 0 ? 'Counting ' : jobcount }} Jobs Found</p></div> -->
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-hover table-sm">
                        <thead class=" table-dark">
                            <tr>
                                <th scope="col" class="align-middle text-center">Job Title</th>
                                <th scope="col" class="align-middle text-center">Date Submitted</th>
                                <!-- <th scope="col" class="align-middle text-center">Is Indexed</th> -->
                                <!-- <th>Responses</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="jobs < 1"><td colspan="4" class="align-middle text-center">No jobs found...</td></tr>
                            <jobs-table-row v-else v-for="job in jobs.data" :key="job.id" :job="job"></jobs-table-row>
                        </tbody>
                    </table>

                    <pagination align="center" :data="jobs" @pagination-change-page="page" :limit='10' size="small" :show-disabled="true">
                        <span slot="prev-nav">&lt; Previous</span>
                        <span slot="next-nav">Next &gt;</span>
                    </pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import JobsTableRow from './JobsTableRow.vue'
import pagination from 'laravel-vue-pagination'
import moment from 'moment'
export default {
    components: {
        JobsTableRow,
        pagination
    },
    data(){
        return {
            jobs: {
                type:Object,
                default:null
            },
            // csv: [],
            datePickerOptions: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
                showClear: true,
                showClose: true,
            },
            // jobsFrom: 'google-jobs',
            date: '',
            search: '',
            modalTitle: '',
            modalContent: '',
            jobcount: 0,
            // is_indexed: '',
        }
    },
    mounted(){
        this.page(1)
    },
    methods:{
        page(page){
            axios.get(`/api/v1/arbeitsagentur/job?page=${page}&q=${this.search}&date=${this.date}&is_indexed=${this.is_indexed}`).then(response =>{
                if(response){
                    this.jobs = response.data
                    this.jobcount = response.data.meta.total
                    // if(page == 1){  this.exportJob() }
                }
            })
            .catch(error => console.log(error.response.data))
        }
    },
}
</script>

<style>

</style>
