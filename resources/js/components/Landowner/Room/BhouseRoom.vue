<template>
    <div>
        <div class="section">
            <div class="columns">
                <div class="column is-8 is-offset-2 mt-3">

                    <div class="panel">
                        <div class="panel-heading">
                            LIST OF ROOMS
                        </div>

                        <div class="panel-body">
                            <div class="buttons">
                                <b-button icon-left="chevron-left" tag="a" href="/boarding-house">
                                    BACK
                                </b-button>
                            </div>
                            <div class="level">
                                <div class="level-left">
                                    <b-field label="Page">
                                        <b-select v-model="perPage" @input="setPerPage">
                                            <option value="5">5 per page</option>
                                            <option value="10">10 per page</option>
                                            <option value="15">15 per page</option>
                                            <option value="20">20 per page</option>
                                        </b-select>
                                        <b-select v-model="sortOrder" @input="loadAsyncData">
                                            <option value="asc">ASC</option>
                                            <option value="desc">DESC</option>

                                        </b-select>
                                    </b-field>
                                </div>

                                <div class="level-right">
                                    <div class="level-item">
                                        <b-field label="Search">
                                            <b-input type="text"
                                                     v-model="search.room_no" placeholder="Search Room No"
                                                     @keyup.native.enter="loadAsyncData"/>
                                            <p class="control">
                                                <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                            </p>
                                        </b-field>
                                    </div>
                                </div>
                            </div>

                            <div class="buttons mt-3 is-right">
                                <b-button @click="openModal(0)" icon-right="room-service-outline" class="is-success">NEW ROOM</b-button>
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

                                <b-table-column field="room_id" label="ID" v-slot="props">
                                    {{ props.row.room_id }}
                                </b-table-column>

                                <b-table-column field="room_no" label="Room No" v-slot="props">
                                    {{ props.row.room_no }}
                                </b-table-column>

                                <b-table-column field="room_desc" label="Description" v-slot="props">
                                    {{ props.row.room_desc }}
                                </b-table-column>


                                <b-table-column label="Action" v-slot="props">
                                    <b-dropdown aria-role="list">
                                        <template #trigger="{ active }">
                                            <b-button
                                                label="..."
                                                type="is-primary"
                                                class="is-small"
                                                :icon-right="active ? 'menu-up' : 'menu-down'" />
                                        </template>

                                        <b-dropdown-item aria-role="listitem" @click="openModal(props.row.room_id)">Modify</b-dropdown-item>
                                        <b-dropdown-item aria-role="listitem" tag="a" :href="`/boarding-house-bedspace/` + global_bhouse_id + `/` + props.row.room_id">Bed Space</b-dropdown-item>
                                        <b-dropdown-item aria-role="listitem" @click="confirmDelete(props.row.room_id)">Delete</b-dropdown-item>


                                    </b-dropdown>
                                    <!-- <div class="is-flex">
                                        <b-button class="button is-small is-warning mr-1" tag="a" icon-right="pencil" @click="getData(props.row.bhouse_id)"></b-button>
                                        <b-button class="button is-small is-danger mr-1" icon-right="delete" @click="confirmDelete(props.row.bhouse_id)"></b-button>
                                    </div> -->
                                </b-table-column>
                            </b-table>

                        </div> <!-- panel body -->
                    </div>
                </div><!--column-->

            </div><!--columns div-->

        </div><!-- section -->



        <!--modal create-->
        <b-modal v-model="modalForm" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Room Information</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalForm = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Room Number"
                                             :type="this.errors.room_no ? 'is-danger':''"
                                             :message="this.errors.room_no ? this.errors.room_no[0] : ''">
                                        <b-input v-model="fields.room_no" type="text" icon="home-outline"></b-input>
                                    </b-field>

                                    <b-field label="Room Description"
                                             :type="this.errors.room_desc ? 'is-danger':''"
                                             :message="this.errors.room_desc ? this.errors.room_desc[0] : ''">
                                        <b-input v-model="fields.room_desc" type="textarea"></b-input>
                                    </b-field>

                                    <div v-if="global_room_id < 1">
                                        <b-field label="ROOM IMAGE"
                                                 :type="this.errors.room_img_path ? 'is-danger':''"
                                                 :message="this.errors.room_img_path ? this.errors.room_img_path[0] : ''">
                                            <b-upload v-model="fields.room_img" class="file-label">
                                                <span class="file-cta">
                                                    <b-icon class="file-icon" icon="upload"></b-icon>
                                                    <span class="file-label">Click to upload</span>
                                                </span>
                                                    <span class="file-name" v-if="fields.room_img">
                                                    {{ fields.room_img.name }}
                                                </span>
                                            </b-upload>
                                        </b-field>
                                    </div>

                                    <div v-if="global_room_id > 0">

                                        <img v-if="fields.room_img_path" :src="`/storage/rooms/${fields.room_img_path}`" />

                                        <b-field label="ROOM IMAGE"
                                                 :type="this.errors.room_img_path ? 'is-danger':''"
                                                 :message="this.errors.room_img_path ? this.errors.room_img_path[0] : ''">
                                            <b-upload v-model="fields.room_img" class="file-label">
                                                <span class="file-cta">
                                                    <b-icon class="file-icon" icon="upload"></b-icon>
                                                    <span class="file-label">Click to update image</span>
                                                </span>
                                                <span class="file-name" v-if="fields.room_img">
                                                    {{ fields.room_img.name }}
                                                </span>
                                            </b-upload>
                                        </b-field>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="modalForm=false"/>
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->






        <!--modal show room-->
        <b-modal v-model="modalShowRoom" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Room Information</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalShowRoom = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">


                                </div>
                            </div>

                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="modalShowRoom=false"/>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->




    </div><!--root-->
</template>

<script>

export default {
    props: {
        propDataId: {
            type: String,
            default: '',
        },
        propRouteback: {
            type: String,
            default: '',
        }
    },
    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'room_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',

            global_bhouse_id: 0,
            global_room_id: 0,

            modalForm: false,
            modalShowRoom: false,

            fields: {},
            errors: {},
            search: {
                room_no: '',
            },

            room: {},

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },
        }
    },

    methods: {

        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `roomno=${this.search.room_no}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-boarding-house-rooms/${this.global_bhouse_id}?${params}`)
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

        openModal(id){

            if(id > 0){
                this.getData(id);
            }
            this.global_room_id = id;
            this.fields = {};
            this.errors = {};
            this.modalForm = true;
        },

        getData(id){
            axios.get('/get-boarding-house-room-edit/' + id).then(res=>{
                this.fields = res.data;
            })
        },

        submit: function(){
            var formData = new FormData();
            formData.append('room_no', this.fields.room_no ? this.fields.room_no : '');
            formData.append('room_desc', this.fields.room_desc ? this.fields.room_desc : '');
            formData.append('room_img_path', this.fields.room_img ? this.fields.room_img : '');

            if(this.global_room_id > 0){
                //update
                axios.post('/boarding-house-rooms-update/' + this.global_room_id, formData).then(res=>{
                    if(res.data.status === 'updated'){
                        this.modalForm =false;
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: ()=>{
                                this.loadAsyncData();
                                this.fields = {};
                                this.errors = {}
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

                axios.post('/boarding-house-rooms/' + this.global_bhouse_id, formData).then(res=>{
                    if(res.data.status === 'saved'){
                        this.modalForm =false;
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            onConfirm: ()=>{
                                this.loadAsyncData();
                                this.fields = {};
                                this.errors = {}
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

        initData: function(){
            this.global_bhouse_id = parseInt(this.propDataId);
        },

        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },

        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/boarding-house-room-delete/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },


        showRoom(row){
            this.modalShowRoom = true;
            this.room = row;
            console.log(this.room)
        },


    },

    mounted(){
        this.initData();
        this.loadAsyncData();

    }
}
</script>

<style>

</style>
