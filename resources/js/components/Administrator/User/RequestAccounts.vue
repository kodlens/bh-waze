<template>
    <div>
        <div class="section">
            <div class="panel">
                <div class="columns">
                    <div class="column is-10 is-offset-1">
                        <div class="is-flex is-justify-content-center mb-2" style="font-size: 20px; font-weight: bold;">LIST OF REQUEST ACCOUNT</div>

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
                                                 v-model="search.lname" placeholder="Search Lastname"
                                                 @keyup.native.enter="loadAsyncData"/>
                                        <p class="control">
                                            <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                        </p>
                                    </b-field>
                                </div>
                            </div>
                        </div>

<!--                        <div class="buttons mt-3 is-right">-->
<!--                            <b-button @click="openModal" icon-right="account-arrow-up-outline" class="is-success">NEW</b-button>-->
<!--                        </div>-->

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

                            <b-table-column field="user_id" label="ID" v-slot="props">
                                {{ props.row.user_id }}
                            </b-table-column>

                            <b-table-column field="name" label="Name" v-slot="props">
                                {{ props.row.lname }}, {{ props.row.fname }} {{ props.row.mname }}
                            </b-table-column>

                            <b-table-column field="sex" label="Sex" v-slot="props">
                                {{ props.row.sex }}
                            </b-table-column>

                            <b-table-column field="is_approve" label="Status" v-slot="props" centered>
                                <b-icon icon="check-bold" v-if="props.row.is_approve" type="is-success"></b-icon>
                                <b-icon type="is-danger" icon="bank-off" v-else></b-icon>
<!--                                {{ props.row.is_approve }}-->
                            </b-table-column>

                            <b-table-column field="role" label="Role" v-slot="props">
                                {{ props.row.role }}
                            </b-table-column>

                            <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <b-button class="button is-small is-link mr-1" v-if="props.row.is_approve === 0" tag="a" icon-right="thumb-up" @click="approveAccount(props.row.user_id)"></b-button>
                                    <b-button class="button is-small is-danger mr-1" v-else tag="a" icon-right="thumb-down" @click="disapproveAccount(props.row.user_id)"></b-button>
                                </div>
                            </b-table-column>
                        </b-table>
                    </div><!--close column-->
                </div>

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
                                        <img v-if="this.user" :src="`/storage/bpermits/` + this.user.business_permit_img" />
                                        <div v-else>IMAGE NOT FOUND!</div>
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
                            @click="submitApprovedAccount"
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

export default{

    props: {

    },
    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'user_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',

            user: {},

            modalPermit: false,
            global_user_id: 0,

            search: {
                lname: '',
            },

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
                `lname=${this.search.lname}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-request-accounts?${params}`)
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


        approveAccount: function(dataId){
            axios.get('/users/' + dataId).then(res=>{
                this.user = res.data;
                this.modalPermit = true;
                this.global_user_id = dataId;
            });
        },

        submitApprovedAccount: function(dataId){
            axios.post('/request-account-approved/' + this.global_user_id).then(res=>{
                if(res.data.status === 'approved'){
                    this.$buefy.dialog.alert({
                        title: 'APPROVED!',
                        message: 'Account approved successfully.',
                        type: 'is-success',
                        onConfirm: ()=> {
                            this.loadAsyncData();
                            this.modalPermit = false;
                        }
                    });
                }
            });
        },


        disapproveAccount: function (dataId){
            this.$buefy.dialog.confirm({
                title: 'DISAPPROVE ACCOUNT?!',
                message: 'Disapprove account?',
                type: 'is-warning',
                onConfirm: ()=> this.submitDisapproveAccount(dataId)
            });
        },

        submitDisapproveAccount: function(dataId){
            axios.post('/request-account-disapproved/' + dataId).then(res=>{
                if(res.data.status === 'disapproved'){
                    this.$buefy.dialog.alert({
                        title: 'DISAPPROVED!',
                        message: 'Account disapproved successfully.',
                        type: 'is-success',
                        onConfirm: ()=> this.loadAsyncData()
                    });
                }
            });
        },


    },

    mounted() {
        //this.initData();
        this.loadAsyncData();

    }
}
</script>


<style scoped>
    .panel{
        padding: 25px;
    }

</style>
