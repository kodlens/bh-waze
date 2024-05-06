<template>

    <div>
        <div class="section">
            <div class="columns">
                <div class="column">
                    <h1 class="title is-4 has-text-centered">ROOMS</h1>
                    <div class="room-container">
                        <div class="room" v-for="(item, index) in rooms" :key="index">
                            <div class="room-image">
                                <img :src="'/storage/rooms/' + item.room_img_path" />
                            </div>

                            <div class="room-title">
                                {{ item.room_no }}
                            </div>
                            <div class="room-desc">
                                {{ item.room_desc }}
                            </div>

                            <div class="buttons room-button">
                                <button class="button is-link" @click="openBedSpaces(item.room_id, 0)">VIEW BED SPACES</button>
                            </div>

                        </div><!--room loop -->
                    </div><!--room-container -->

                </div>
            </div>
        </div>

        <hr>

        <div class="section" id="bedspaces" >
            <div class="columns">
                <div class="column">
                    <h1 class="title is-4 has-text-centered">BED SPACES</h1>

                    <div class="bedspace-container" v-for="(item, index) in bedspaces" :key="index">

                        <div class="bedspace-title">
                            {{ item.bedspace_name }}
                        </div>
                        <div class="bedspace-images-container">
                            <div v-for="(iImg, index) in item.imgs" :key="index" class="bedspace-imgs room-image">
                                <img :src="`/storage/bedspaces/${iImg.bedspace_img_path}`" @click="showImageModal(iImg, item)"/>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>




        <!--modal create-->
 
        <b-modal v-model="modalZoomImage" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="reserveNow(bedspaceInfo.bedspace_id)">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">{{ bedspaceInfo.name }}</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalZoomImage = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <div style="display: flex; justify-content: center;">
                                        <img :src="`/storage/bedspaces/${bedspaceInfo.img}`" style="margin: 0 auto;"/>
                                    </div>
                                    <div class="bedspace-detail">
                                        <div>
                                            <span style="font-weight: bold;">DESCRIPTION: </span> {{ bedspaceInfo.desc }}
                                        </div>
                                        <div>
                                            <span style="font-weight: bold;">PRICE: </span> {{ bedspaceInfo.price }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="modalZoomImage=false"/>
                        <button
                            label="Save"
                            class="button is-link">RESERVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->



    </div> <!-- div root-->



</template>


<script>
export default {

    props: {
        propData:{
            type: String,
            default: '',
        }
    },

    data(){
        return{
            bhouse_id: 0,
            rooms: [],

            bedspaces: [],

            modalZoomImage: false,
            bedspaceInfo: {},


            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },


        }
    },
    methods: {
        loadBhRooms: function(){
            axios.get('/get-client-bh-rooms/' + this.bhouse_id).then(res=>{
                this.rooms = res.data;
            });
        },


        openBedSpaces: function(dataId, flag){
            axios.get('/get-client-bhroom-bedspaces/' + dataId).then(res=>{
                if(flag === 0){
                    if(res.data.length === 0){
                        this.$buefy.toast.open({
                            message: 'No result found.',
                            position: 'is-bottom',
                            type: 'is-danger'
                        })
                    }
                }

                this.bedspaces = res.data;

                var element = document.getElementById("bedspaces");
                element.scrollIntoView({behavior: "smooth", block: "center"});

            });
        },

        showImageModal: function(bedspaceDataImg, bedspaceData){
           

            this.bedspaceInfo.img = bedspaceDataImg.bedspace_img_path;
            this.bedspaceInfo.name = bedspaceData.bedspace_name;
            this.bedspaceInfo.price = bedspaceData.price;
            this.bedspaceInfo.desc = bedspaceData.bedspace_desc;
            this.bedspaceInfo.bedspace_id = bedspaceData.bedspace_id;
            //console.log(bedspaceDataImg)
            this.modalZoomImage = true;

        },

        reserveNow: function(dataId){

            axios.post('/client-bhroom-bedspace-reserve/' + dataId).then(res=>{
                if(res.data.status === 'reserved'){
                    this.$buefy.dialog.alert({
                        title: 'RESERVED',
                        message: 'Thank you for choosing us. The bed space was successfully reserved and the payment will be expected before 24 hours. Please send a proof of transaction using your account. You may contact us for more information.',
                        type: 'is-success',
                        onConfirm: ()=>{
                            this.openBedSpaces(dataId, 1);
                            //set 1 to avoid checking for length of array fetching bedspaces
                            this.modalZoomImage = false;
                        }
                    });
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    if(err.response.data.errors === 'active'){
                        this.$buefy.dialog.alert({
                            message: 'You have an active reservation. Please settle it first. Thank you.',
                            type: 'is-warning',
                            title: 'ACTIVE BOOK DETECTED!'
                        })
                    }
                }
                if(err.response.status === 401){
                    this.$buefy.toast.open({
                        message: 'Sorry you can\'t proceed without an account. Please register and login first.',
                        type: 'is-danger',
                        //title: 'UNAUTHORIZED!'
                    })
                }
            });
        },

        initData: function(){
            this.rooms = JSON.parse(this.propData);
        }
    },

    mounted(){
        this.initData();
    }
}
</script>

<style scoped>
    .room-container, .bedspace-images-container{
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
        justify-content: center;
        margin:100px;
        border-radius: .5em;
    }



    .bedspace-container{
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 2px solid #c2c2c2;
        margin: 25px;
        background-color:rgb(7, 79, 151);

    }

    .room{
        margin: 80px;
        padding: 40px;
        border-radius: 5px;
        height: 400px;
        position: relative;
        background:rgb(7, 79, 151);
        transition: ease-in-out .5s;
    }

    .room:hover{
        box-shadow: 10px 6px 22px -1px black;
        -webkit-box-shadow: 01px 6px 22px -1px black;
        -moz-box-shadow: 10px 6px 22px -1px black;
    }

    .room-image > img{
        display: flex;
        justify-content: center;
        height: 260px;
        width:500px;
        transition: ease-in .2s;
        cursor: pointer;
    }



    .room-title, .bedspace-title{
        font-weight: bold;
        color:white;

    }

    .room-button{
        position: absolute;
        bottom: 10px;
    }

    .bedspace-title{
        margin-bottom: 15px;
    }
    .bedspace-imgs > img{
        margin: 15px;
    }

    .bedspace-detail{
        margin: 15px 0;
        color:  solid black;

    }
  
    .room-desc{
        color: white;
    }


</style>
