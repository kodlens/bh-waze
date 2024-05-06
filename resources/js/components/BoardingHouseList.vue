<template>

    <div class="section">
        <div class="columns is-centered">
            <div class="column is-6">
                <b-field>
                    <b-input type="text" v-model="search.bhousename" expanded placeholder="Search Boarding House"></b-input>
                    <p class="control">
                        <button class="button is-link" icon-left="lens" @click="loadBoardingHouses"></button>
                    </p>
                </b-field>
            </div>
        </div>

        <b-carousel-list v-model="test" :data="items" :items-to-show="itemShow">
            <template #item="list">
                <div class="card">
                    <div class="card-image">
                        <figure class="image 5">
                            <a @click="info(list.index)"><img class="bh-image" :src="`/storage/bhouses/${list.bhouse_img_path}`"></a>
                        </figure>
                        <!--                        <b-tag type="is-danger" rounded style="position: absolute; top: 0;"><b>50%</b></b-tag>-->
                    </div>
                    <div class="card-content">
                        <div class="content">
                            <p class="title is-6">{{ list.bhouse_name }}</p>
                            <p class="subtitle is-7 mt-4">
                                {{ list.bhouse_desc}}
                            </p>

                            <p class="subtitle is-7 mt-4">
                                Located at: {{ list.street }}
                            </p>
                        </div>

                        <div class="buttons" style="position: absolute; bottom: 0;">
                            <b-button type="is-link" icon-right="chevron-right" tag="a" :href="`/client-bhouse-detail/${list.bhouse_id}`">SEE MORE...</b-button>
                        </div>
                    </div>
                </div>
            </template>
        </b-carousel-list>

        <div class="columns">
            <div class="column">



            </div>
        </div>

    </div><!--root div section -->

</template>

<script>
export default {

    data() {
        return {


            test: 0,
            items: [],
            itemShow: 3,

            search: {
                bhousename: '',
            }


        }
    },


    methods: {

        info(value) {
            this.test = value
        },

        loadBoardingHouses: function(){
            console.log('test')
            const params = [
                `bhousename=${this.search.bhousename}`
            ].join('&')
            axios.get(`/get-client-bhouses?${params}`).then(res=>{
                this.items = res.data;
            }).catch(err => {

            });
        },

        onResize(){
            if(window.innerWidth < 600){
                this.itemShow = 2;
            }
            if(window.innerWidth < 400){
                this.itemShow = 1;
            }
        },
    },

    mounted(){
        this.onResize();
        window.addEventListener('resize', this.onResize);

        this.loadBoardingHouses();

    },
    beforeDestroy () {
        if (typeof window === 'undefined') return
        window.removeEventListener('resize', this.onResize)
    },
}
</script>

<style scoped>
    .card{
        margin: 15px;
    }
    .carousel-list{
        box-shadow: none;
    }

    .card{
        height: 500px;
    }
    .card .card-image{
        height: 200px;
    }

    .card-content{
        position: relative;
    }
    .content{
        height: 100%;

    }

    .bh-image{
        height: 200px;
        object-fit: cover;
    }
</style>
