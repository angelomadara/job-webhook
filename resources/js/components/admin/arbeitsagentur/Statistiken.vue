<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Arbeitsagentur Statistiken</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <!-- controls -->
            </div>
        </div>

        <div class="row justify-content">
            <div class="col-lg-12">
                <b>Downloadable statistical reports from Budesagentur fur Arbeit.</b>
            </div>
        </div>

        <div class="row justify-content">
            <div class="col-md-7">
                <ul id="xlsx-statistics">
                    <li v-for="file in files" :key="file" @click="download(file,$event)">
                        {{ file }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            files : '',
            clicked_file : '',
            btnStyle: '',
        }
    },
    mounted(){
        axios.get("/api/v1/arbeitsagentur/statistics")
            .then(response => {
                this.files = response.data;
            })
            .catch(error => {
                console.log(error);
            });
    },
    methods: {
        download(file,event){
            event.path[0].innerHTML = `${file} - Downloading file please wait...`
            axios.get("/api/v1/arbeitsagentur/statistics/download?file=Statistiken/"+file)
                .then(response => {
                    if(response.status == 200){
                        location.href = `${location.origin}/api/v1/download?file=${file}&path=arbeitsagentur/Statistiken`
                        event.path[0].innerHTML = file
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}
</script>

<style>
    ul#xlsx-statistics{
        list-style-type:decimal-leading-zero;
        margin-top: 1em;
    }

    ul#xlsx-statistics li:hover{
        color: blue;
        cursor: pointer;
    }
</style>
