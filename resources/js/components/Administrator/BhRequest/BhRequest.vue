<template>
    <div>
        <div class="section">

            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <div class="panel">
                        <div class="section">

                            <div class="is-flex is-justify-content-center mb-2" style="font-size: 20px; font-weight: bold;">LIST OF REQUESTS</div>

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
                                                     v-model="search.lname" placeholder="Lastname"
                                                     @keyup.native.enter="loadAsyncData"/>
                                            <p class="control">
                                                <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                            </p>
                                        </b-field>
                                    </div>
                                </div>
                            </div>

                            <div class="buttons mt-3 is-right">
                                <b-button @click="" icon-right="account-arrow-up-outline" class="is-success">NEW</b-button>
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

                                <b-table-column field="appointment_type_id" label="ID" v-slot="props">
                                    {{ props.row.bhouse_id }}
                                </b-table-column>

                                <b-table-column field="bhouse_name" label="BH Name" v-slot="props">
                                    {{ props.row.bhouse_name }}
                                </b-table-column>

                                <b-table-column field="is_approve" label="Status" v-slot="props">
                                    <b-icon icon="check-bold" v-if="props.row.is_approve" type="is-success"></b-icon>
                                    <b-icon type="is-danger" icon="bank-off" v-else></b-icon>
                                </b-table-column>

                                <b-table-column field="created_at" label="Date Created" v-slot="props">
                                    {{ props.row.created_at }}
                                </b-table-column>

                                <b-table-column label="Action" v-slot="props">
                                    <div class="is-flex">
                                        <b-tooltip label="View permit" v-if="props.row.is_approve === 0">
                                            <b-button class="button is-small is-link mr-1" tag="a" icon-right="desktop-mac" @click="viewPermit(props.row.bhouse_id)"></b-button>
                                        </b-tooltip>

                                        <b-tooltip label="Deactivate" v-if="props.row.is_approve === 1">
                                            <b-button class="button is-small is-danger mr-1" tag="a" icon-right="laptop-off" @click="deactivateBhouse(props.row.bhouse_id)"></b-button>
                                        </b-tooltip>

                                    </div>
                                </b-table-column>
                            </b-table>

                        </div><!--section -->

                    </div><!--panel -->
                </div><!--close column-->
            </div>


        </div><!--section div-->


        <!--modal display business permit-->
        <b-modal v-model="modalPermit" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Business Permit</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalPermit = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <!--content here-->
                                    <div class="img-container">
                                        <div v-if="this.errors.bpermit_img">{{this.errors.bpermit_img}}</div>
                                        <img v-else :src="`/storage/bpermit/` + this.imgpath" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            type="is-danger"
                            icon-left="close-box-outline"
                            label="Close"
                            @click="modalPermit=false"/>
                        <b-button
                            :class="btnClass"
                            label="APPROVE"
                            @click="approvePermit"
                            icon-left="thumb-up-outline"
                            type="is-success"></b-button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


    </div>
</template>

<script>
export default {

    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'bhouse_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',

            search: {
                lname: '',
                fname: '',
            },

            modalPermit:  false,

            imgpath : '',
            bhouse_id: 0,
            errors: {},

            btnClass: {
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
                `lname=${this.search.lname}`,
                `fname=${this.search.fname}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-bh-requests?${params}`)
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




        viewPermit: function(dataId){
            this.bhouse_id = dataId;
            this.errors = {};
            this.imgpath = '';
            this.modalPermit=true;

            axios.get('/bh-request/' + dataId).then(res=>{
                if(res.data.business_permit_imgpath == null){
                    this.errors.bpermit_img = "No image found.";
                }
                this.imgpath = res.data.business_permit_imgpath;
            });
        },

        approvePermit: function(){
            axios.post('/bh-request-approved/' +this.bhouse_id).then(res=>{
                if(res.data.status === 'approved'){
                    //alert('Boarding house approved successfully.');

                    this.modalPermit = false;

                    this.$buefy.dialog.alert({
                        title: 'APPROVED!',
                        message: 'Boarding house approved successfully',
                        type: 'is-success',
                        onConfirm: ()=>{
                            this.loadAsyncData();
                        }
                    });
                }
            })
        },
        deactivateBhouse: function(dataId){
            axios.post('/bh-request-deactivate/' + dataId).then(res=>{
                if(res.data.status === 'deactivated'){
                    this.$buefy.dialog.alert({
                        title: 'DEACTIVATED!',
                        message: 'Boarding house deactivated successfully',
                        type: 'is-success',
                        onConfirm: ()=>{
                            this.loadAsyncData();
                        }
                    });
                }
            });
        },


        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/appointment-type/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;


            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/appointment-type/'+data_id).then(res=>{
                this.fields = res.data;
            });
        },

        clearFields(){
            this.fields = {
                appointment_type: '',
            };
        },


        submit: function(){
            if(this.global_id > 0){
                //update
                axios.put('/appointment-type/'+this.global_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.confirm({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                                this.isModalCreate = false;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }else{
                //INSERT HERE
                axios.post('/appointment-type', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.confirm({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                this.isModalCreate = false;
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });
            }
        }

    },

    mounted() {
        this.loadAsyncData();
    }

}
</script>

<style scoped>

</style>
