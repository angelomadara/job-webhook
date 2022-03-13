<template>
    <tr>
        <td class="align-middle">
            <a :href="job.url" class="text-dark text-decoration-none" target="_blank">
                {{ jobTitle  }}
            </a>
        </td>
        <td class="align-middle text-center">
            <span data-bs-toggle="tooltip" data-bs-placement="right" :title="job.date_from_data">{{ job.date_submitted }}</span>
        </td>
        <!-- <td class="align-middle text-center">{{ job.isIndexed }}</td> -->
        <td class="align-middle text-center">
            <button
                class="btn btn-sm btn-primary"
                :class="'btn-'+job.id"
                @click="showNotification(job)"
            >Show Response</button>
        </td>
    </tr>
</template>

<script>
export default {
    props: ['job'],
    data(){
        return {
            jobTitle: this.job.title,
            words: this.$parent.search
        }
    },
    computed:{
        keywords() {
            return this.words.split(' ')
        }
    },
    mounted(){
        // tooltip
        Helper.tooltip()
    },
    methods:{
        showNotification(data){
            // disabled the button to prevent double clicks
            document.querySelector(`.btn-${data.id}`).disabled = true
            /**
             * this event will jump to JobsTable.vue component
             */
            EventBus.$emit('getGoogleNotification',data)
        }
    }
}
</script>

<style>

</style>
