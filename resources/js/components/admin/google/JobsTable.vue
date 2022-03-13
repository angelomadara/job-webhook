<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Sent Jobs to Google  </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <!-- <div class="btn-group me-2">
                    <json-csv class="" :data="csv">
                        Export to CSV
                    </json-csv>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    This week
                </button> -->
            </div>
        </div>

        <div class="row justify-content-end mb-3">
            <!-- <div class="col-2">
                <div class="input-group">
                    <span class="input-group-text" id="indexed-control">Indexed Jobs?</span>
                    <select v-model="is_indexed" @change="page(1)" id="" class="form-select" aria-describedby="indexed-control">
                        <option value="" selected></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div> -->
            <div class="col-2" style="position:relative">
                <date-picker name="date" v-model="date" @dp-change="page(1)" :config="datePickerOptions" class="form-select" placeholder="YYYY-MM-DD" autocomplete="off"></date-picker>
            </div>
            <div class="col-3">
                <input v-model="search" @keyup="page(1)" class="form-control" placeholder="Search job..." type="text">
            </div>
            <div class="col-2"><p>{{ jobcount == 0 ? 'Counting ' : jobcount }} Jobs Found</p></div>
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
                                <th>Responses</th>
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

        <div class="modal fade"  id="bootstrap-modal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">{{modalTitle}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" v-html="modalContent"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import JobsTableRow from './JobsTableRow.vue'
import JsonCsv from 'vue-json-csv'
import pagination from 'laravel-vue-pagination'
import moment from 'moment'
export default {
    components: {
        JobsTableRow,
        JsonCsv,
        pagination
    },
    data(){
        return {
            jobs: {
                type:Object,
                default:null
            },
            csv: [],
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
            is_indexed: '',
        }
    },
    mounted(){
        this.page()
        // Helper.tooltip()

        /**
         * this event came from JobsTableRow.vue component
         */
        EventBus.$on('getGoogleNotification',(data)=>{
            this.getUrlStatus(data)
        })
    },
    methods:{
        // loadJobs(){
        //     if(this.jobsFrom == 'google-jobs'){
        //         this.page(1)
        //     }else{
        //         this.jobs = { type:Object, default:null }
        //         this.csv = []
        //         this.jobcount = 0
        //     }
        // },
        page(page=1){
            axios.get(`/api/v1/google-api/job?page=${page}&q=${this.search}&date=${this.date}&is_indexed=${this.is_indexed}`).then(response =>{
                if(response){
                    this.jobs = response.data
                    this.jobcount = response.data.meta.total
                    if(page == 1){  this.exportJob() }
                }
            })
            .catch(error => console.log(error.response.data))
        },

        getUrlStatus(job){
            // axios.post(`api/v1/google-api/url-status?url=${job.url}`).then(response =>{
            axios.post(`/api/v1/google-api/url-status?id=${job.id}`).then(response =>{
                this.modalTitle = job.title

                let data = response.data

                this.modalContent = `
                    <p>Date submitted to google: <b>${moment(data.response.notify_time).format("YYYY-MM-DD h:m A")}</b></p>
                    <p>Date submitted to the server: <b>${moment(data.job.date).format("YYYY-MM-DD")}</b></p>
                    <p><b>Google notification status: <span class='text-success'>${data.type}</span></b></p>
                    <p>Google Raw Response:</p>
                    <pre style='background:#333;color:#fff'><code>${JSON.stringify(JSON.parse(data.response.response),null,2)}</code></pre>`

                let myModal = new bootstrap.Modal(document.getElementById('bootstrap-modal'))
                myModal.show()
                // enabled again the button
                document.querySelector(`.btn-${job.id}`).disabled = false
            })
        },

        exportJob(){
            // disabled for now
            // axios.post(`api/v1/job/export`,{"date":this.date,"q":this.search}).then(response=>{
            //     this.csv = response.data.data
            //     this.jobcount = response.data.data.length
            // })
        }
    }
}
</script>

<style>

</style>
