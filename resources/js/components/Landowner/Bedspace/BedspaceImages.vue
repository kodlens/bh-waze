<template>
    <div>
        <div class="section">
            <div class="columns">
                <div class="column is-10 is-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            BED SPACE IMAGE(S)
                        </div>

                        <div class="panel-body">
                            <div class="buttons">
                                <b-button icon-left="chevron-left" tag="a" :href="`/boarding-house-bedspace/${this.global_bh_id}/${this.global_room_id}`">
                                    BACK
                                </b-button>
                            </div>
                            <div class="">
                                <div class="buttons mb-3 is-right">
                                    <b-button class="button is-primary" icon-left="bunk-bed-outline" @click="openModal(0)">NEW BED SPACE IMAGE</b-button>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <div class="box t-row" v-for="(item, index) in data" :key="index">
                                        <div>
                                            <img style="height: 100px;" :src="`/storage/bedspaces/${item.bedspace_img_path}`" />
                                        </div>

                                        <div class="t-control" @click="confirmDelete(`${item.bedspace_img_id}`)">DELETE</div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>




        <!--modal create-->
        <b-modal v-model="isModalCreate" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Bed Spaces</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">

                                    <b-field mb-auto label="Bedspace Image">
                                        <b-upload v-model="fields.bedspaces"
                                                  multiple
                                                  drag-drop>
                                            <section class="section">
                                                <div class="content has-text-centered">
                                                    <p>
                                                        <b-icon
                                                            icon="upload"
                                                            size="is-large">
                                                        </b-icon>
                                                    </p>
                                                    <p>Drop your files here or click to upload</p>
                                                </div>
                                            </section>
                                        </b-upload>
                                    </b-field>

                                    <div class="tags">
                                        <span v-for="(file, index) in fields.bedspaces"
                                              :key="index"
                                              class="tag is-primary" >
                                            {{file.name}}
                                            <button class="delete is-small"
                                                    type="button"
                                                    @click="deleteDropFile(index)">
                                            </button>
                                        </span>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="isModalCreate=false"/>
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


    </div>
</template>

<script>
export default {
    props: {
        propBhId: {
            type: String,
            default: '',
        },

        propBhRoomId: {
            type: String,
            default: '',
        },

        propBedspaceId: {
            type: String,
            default: '',
        }

    },
    data(){
        return{

            data: [],

            isModalCreate: false,

            fields: {
                bedspace_name: '',
                bedspace_desc: '',
                bedspaces: null,
            },

            errors: {},
            search: {
                bhousename: '',
            },


            bedspaces: [],



            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            global_bh_id: 0,
            global_room_id: 0,
            global_bedspace_id: 0,


        }
    },

    methods: {

        initData: function(){
            this.global_room_id = parseInt(this.propBhRoomId);
            this.global_bh_id = parseInt(this.propBhId);
            this.global_bedspace_id = parseInt(this.propBedspaceId);
        },

        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-bedspace-imgs/${this.global_bedspace_id}?${params}`)
                .then( data  => {
                    this.data = data.data;
                })
        },


        openModal(id){
            this.isModalCreate=true;
        },
        deleteDropFile(index) {
            this.fields.bedspaces.splice(index, 1)
        },


        submit: function(){

            //insert
            var formData = new FormData();
            if(this.fields.bedspaces){
                this.fields.bedspaces.forEach(item =>{
                    formData.append('bedspace_img_path[]', item);
                });
            }

            axios.post('/boarding-house-bedspace-store/' +this.global_bedspace_id, formData).then(res=>{

                if(res.data.status === 'saved'){
                    this.isModalCreate = false;
                    this.$buefy.dialog.alert({
                        title: 'Success!',
                        message: 'Bedspace(s) successfully saved.',
                        type: 'is-success',
                        onConfirm: ()=>{
                            this.loadAsyncData();
                        }
                    });
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            });



        },



        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this image?',
                cancelText: 'Cancel',
                confirmText: 'Delete?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },

        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/bedspace-imgs-delete/' + delete_id).then(res => {
                if(res.data.status === 'deleted'){
                    this.$buefy.toast.open({
                        message: 'Image deleted.',
                        type: 'is-success',
                    });
                    this.loadAsyncData();
                }
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },


    },//end methods

    mounted(){
        this.initData();
        //this.loadBedspaceImgs();
        this.loadAsyncData();
    }
}
</script>

<style scoped>

.t-row {
    display: flex;
}
.t-control{
    margin-left: auto;
    font-weight: bold;
    cursor: pointer;
    color: red;
}
.t-control:hover{
    text-decoration: underline;
}

</style>
