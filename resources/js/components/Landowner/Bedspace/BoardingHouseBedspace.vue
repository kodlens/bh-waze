<template>
    <div>
        <div class="section">
            <div class="columns">
                <div class="column is-10 is-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            BED SPACE
                        </div>

                        <div class="panel-body">
                            <div class="buttons">
                                <b-button icon-left="chevron-left" tag="a" :href="`/boarding-house-rooms/${this.global_bh_id}`">
                                    BACK
                                </b-button>
                            </div>
                            <div class="">
                                <div class="buttons mb-3 is-right">
                                    <b-button class="button is-primary" icon-left="bunk-bed-outline" @click="openModal(0)">NEW BED SPACE</b-button>
                                </div>
                            </div>

                            <b-table
                                :data="data"
                                :loading="loading"
                                paginated
                                backend-pagination
                                :total="total"
                                :per-page="perPage"
                                @page-change="onPageChange"
                                aria-next-label="Next page"
                                aria-previous-label="Previous page"
                                aria-page-label="Page"
                                aria-current-label="Current page"
                                backend-sorting
                                :default-sort-direction="defaultSortDirection"
                                @sort="onSort">

                                <b-table-column field="bedspace_id" label="ID" v-slot="props">
                                    {{ props.row.bedspace_id }}
                                </b-table-column>

                                <b-table-column field="bedspace_name" label="Bed Space Name" v-slot="props">
                                    {{ props.row.bedspace_name }}
                                </b-table-column>

                                <b-table-column field="bedspace_desc" label="Description" v-slot="props">
                                    {{ props.row.bedspace_desc }}
                                </b-table-column>

                                <b-table-column field="price" label="Price" v-slot="props">
                                    {{ props.row.price }}
                                </b-table-column>

                                <b-table-column field="is_booked" label="Status" v-slot="props">
                                    <span v-if="props.row.is_booked === 1">OCCUPIED</span>
                                    <span v-else>VACANT</span>
                                </b-table-column>

                                <b-table-column label="Action" v-slot="props">
                                    <b-dropdown aria-role="list">
                                        <template #trigger="{ nactive }">
                                            <b-button
                                                label="..."
                                                type="is-primary"
                                                class="is-small"
                                                :icon-right="nactive ? 'menu-up' : 'menu-down'" />
                                        </template>

                                        <b-dropdown-item aria-role="listitem" @click="openModal(props.row.bedspace_id)">Modify</b-dropdown-item>
                                        <b-dropdown-item aria-role="listitem" tag="a" :href="`/boarding-house-bedspace/${global_bh_id}/${global_room_id}/${props.row.bedspace_id}`">Show Images</b-dropdown-item>
                                        <b-dropdown-item aria-role="listitem" @click="confirmDelete(props.row.bedspace_id)">Delete</b-dropdown-item>

                                    </b-dropdown>
                                    <!-- <div class="is-flex">
                                        <b-button class="button is-small is-warning mr-1" tag="a" icon-right="pencil" @click="getData(props.row.bhouse_id)"></b-button>
                                        <b-button class="button is-small is-danger mr-1" icon-right="delete" @click="confirmDelete(props.row.bhouse_id)"></b-button>
                                    </div> -->
                                </b-table-column>
                            </b-table>


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

                                    <b-field label="Bed Space Name"
                                             :type="this.errors.bedspace_name ? 'is-danger':''"
                                             :message="this.errors.bedspace_name ? this.errors.bedspace_name[0] : ''">
                                        <b-input v-model="fields.bedspace_name" type="text" icon="account"></b-input>
                                    </b-field>

                                    <b-field label="Bed Space Description"
                                            :type="this.errors.bedspace_desc ? 'is-danger':''"
                                            :message="this.errors.bedspace_desc ? this.errors.bedspace_desc[0] : ''">
                                        <b-input v-model="fields.bedspace_desc" type="textarea"></b-input>
                                    </b-field>

                                    <div class="is-flex">

                                    </div>

                                    <b-field label="Price"
                                            :type="this.errors.price ? 'is-danger':''"
                                            :message="this.errors.price ? this.errors.price[0] : ''">
                                        <b-numberinput v-model="fields.price" step="1" controls-alignment="right" controls-position="compact"></b-numberinput>
                                    </b-field>

                                    <b-field mb-auto v-if="global_bedspace_id < 1">
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
        }

    },
    data(){
        return{

            data: [],
            total: 0,
            loading: false,
            sortField: 'bedspace_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',


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
        },

        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `bhousename=${this.search.bhousename}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-bhouse-bedspaces/${this.global_room_id}?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                        data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },
        /*
        * Handle page-change event
        */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage(){
            this.loadAsyncData()
        },


        // loadBedspaceImgs: function(){

        //     axios.get('/get-boarding-house-bedspaces-imgs/' + this.global_bedspace_id).then(res=>{
        //         this.bedspaces = res.data;
        //     });
        // },

        openModal(id){
            this.global_bedspace_id = 0;

            if(id > 0){
                axios.get('/get-boarding-house-bedspaces/' + id).then(res=>{
                    this.fields = res.data;
                });
            }

            this.global_bedspace_id = id;
            this.isModalCreate=true;
            this.fields = {};
            this.errors = {};
        },
        deleteDropFile(index) {
            this.fields.bedspaces.splice(index, 1)
        },


        submit: function(){

            if(this.global_bedspace_id > 0){
                //update
                //var formData = new FormData();
                // formData.append('bedspace_name', this.fields.bedspace_name ? this.fields.bedspace_name : '');
                // formData.append('bedspace_desc', this.fields.bedspace_desc ? this.fields.bedspace_desc : '');
                // formData.append('price', this.fields.price);

                axios.put('/boarding-house-bedspace-update/' + this.global_bedspace_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.isModalCreate = false;
                        this.$buefy.dialog.alert({
                            title: 'Success!',
                            message: 'Bedspace(s) successfully updated.',
                            type: 'is-success',
                            onConfirm: ()=>{
                                this.global_bedspace_id = 0;
                                this.fields = {};
                                this.loadAsyncData();
                            }
                        });
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });


            }else{
                //insert
                var formData = new FormData();
                formData.append('bedspace_name', this.fields.bedspace_name ? this.fields.bedspace_name : '');
                formData.append('bedspace_desc', this.fields.bedspace_desc ? this.fields.bedspace_desc : '');

                if(this.fields.bedspaces){
                    this.fields.bedspaces.forEach(item =>{
                        formData.append('bedspace_img_path[]', item);
                    });
                }

                formData.append('price', this.fields.price);

                axios.post('/boarding-house-bedspace/' + this.global_bh_id + '/' +this.global_room_id, formData).then(res=>{

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
            }


        },



        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this bedspace?',
                cancelText: 'Cancel',
                confirmText: 'Delete?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },

        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/boarding-house-bedspace-delete/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },





    },

    mounted(){
        this.initData();
        //this.loadBedspaceImgs();
        this.loadAsyncData();
    }
}
</script>

<style scoped>

.card-container{
    display: flex;
    flex-direction: row;
}
.img-container{
    padding: 15px;
    border: 1px solid black;
    margin: 15px;
    width: 200px;
}
.img-container > img{
    width: 150px;
}
</style>
