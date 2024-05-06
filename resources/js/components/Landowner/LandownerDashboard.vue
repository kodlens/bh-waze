<template>
    <div>
        <div class="section">

            <div class="columns" v-if="is_approve < 1">
                <div class="column is-8 is-offset-2">
                    <b-notification
                            type="is-danger is-light"
                            aria-close-label="Close notification"
                            role="alert">
                        Account status is not yet approved. You cannot add boarding house at this moment.
                    </b-notification>
                </div>
            </div>
            <h1 class="title is-flex is-justify-content-center">LANDOWNER DASHBOARD</h1>

        </div>

        <div class="section">
            <div class="columns">
                <div class="column">
                    <div class="bhouses">

                        <div class="card" v-for="(item, index) in bhouses" :key="index">
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <img :src="`/storage/bhouses/${item.bhouse_img_path}`" />
                                </figure>
                            </div>

                            <div class="card-content">
                                <div class="media">
                                    <div class="media-left">
                                        <figure class="image is-48x48">
                                        <img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
                                        </figure>
                                    </div>
                                    <div class="media-content">
                                        <p class="title is-4">{{ item.bhouse_name }}</p>
                                        <p class="subtitle is-6">{{ item.lname }}, {{ item.fname }} {{ item.mname }}</p>
                                    </div>
                                </div>

                                <div class="content">
                                    {{ item.bhouse_desc }}
                                </div>
                            </div>
                            
                        </div><!--card-->
                    </div><!--bhouses-->
                </div>
            </div>
        </div>
           
    </div>
</template>

<script>
export default {
    props: ['propIsApprove'],

    data() {
        return{
            bhouses: [],
            is_approve: 0,
        }
    },

    methods: {
        loadBhouses(){
            axios.get('/get-landowner-bhouses').then(res=>{
                this.bhouses = res.data;
            })
        },

        initData: function(){
            this.is_approve = parseInt(this.propIsApprove);
        }
    },

    mounted() {
        this.initData();
        this.loadBhouses();
    }
}
</script>


<style scoped>
.card{
    width: 500px;
    margin: auto;
}

</style>