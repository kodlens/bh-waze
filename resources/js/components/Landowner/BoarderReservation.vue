<template>
    <div>
        <div class="section">
            <div class="columns">
                <div class="column">

                    <div class="columns is-centered">
                        <div class="column is-8">
                            <div class="panel">
                                <div class="panel-heading">
                                    BOARDER RESERVATION
                                </div>

                                <div class="panel-body">

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
                                                             v-model="search.bedspace" placeholder="Search Bedspace"
                                                             @keyup.native.enter="loadAsyncData"/>
                                                    <p class="control">
                                                        <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                                    </p>
                                                </b-field>
                                            </div>
                                        </div>
                                    </div>

                                    <!--                                    <div class="buttons mt-3 is-right">-->
                                    <!--                                        <b-button tag="a" href="/boarding-house/create" icon-right="account-arrow-up-outline" class="is-success">NEW BOARDING HOUSE</b-button>-->
                                    <!--                                    </div>-->

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

                                        <b-table-column field="book_bedspace_id" label="ID" v-slot="props">
                                            {{ props.row.book_bedspace_id }}
                                        </b-table-column>

                                        <b-table-column field="bedspace_name" label="Bed Space Name" v-slot="props">
                                            {{ props.row.bedspace_name }}
                                        </b-table-column>

                                        <b-table-column field="boarder_name" label="Boarder Name" v-slot="props">
                                            {{ props.row.lname }}, {{ props.row.fname }} {{ props.row.mname }}
                                        </b-table-column>

                                        <b-table-column field="price" label="Rental Price" v-slot="props">
                                            {{ props.row.rental_price }}
                                        </b-table-column>

                                        <b-table-column field="is_approved" label="Status" v-slot="props">
                                            <span v-if="props.row.approval_status === 'PENDING'">PENDING</span>
                                            <span v-else-if="props.row.approval_status === 'CANCELLED'">CANCELLED</span>
                                            <span v-else-if="props.row.approval_status === 'APPROVED'">APPROVED</span>
                                            <span v-else>FAILED</span>
                                        </b-table-column>

                                        <b-table-column label="Action" v-slot="props">

                                            <b-dropdown aria-role="list" v-if="props.row.approval_status !== 'FAILED'">
                                                <template #trigger="{ active }">
                                                    <b-button
                                                        label="..."
                                                        type="is-primary"
                                                        class="is-small"
                                                        :icon-right="active ? 'menu-up' : 'menu-down'" />
                                                </template>

                                                <b-dropdown-item aria-role="listitem" @click="openProofTransactionModal(props.row)">Proof of Transaction</b-dropdown-item>
                                                <b-dropdown-item aria-role="listitem" @click="cancelReservation(props.row)">Cancel Reservation</b-dropdown-item>
                                            </b-dropdown>
                                            <!-- <div class="is-flex">
                                                <b-button class="button is-small is-warning mr-1" tag="a" icon-right="pencil" @click="getData(props.row.bhouse_id)"></b-button>
                                                <b-button class="button is-small is-danger mr-1" icon-right="delete" @click="confirmDelete(props.row.bhouse_id)"></b-button>
                                            </div> -->
                                        </b-table-column>
                                    </b-table>

                                </div><!--panel -body -->
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- column parent -->
        </div><!--cols parent -->



        <!--modal create-->
        <b-modal v-model="modalProofTransaction" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submitApproved">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Proof of Transaction</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalProofTransaction = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns is-centered">
                                <div class="column is-8">
                                    <b-field label="Set Start Date"
                                             :type="this.errors.nstart_date ? 'is-danger':''"
                                             :message="this.errors.nstart_date ? this.errors.nstart_date[0] : ''">
                                        <b-datepicker v-model="fields.start_date" requried></b-datepicker>
                                    </b-field>
                                    <b-field label="Proof of Transaction">
                                        <img :src="`/storage/prooftrans/${proofTransURL}`" />
                                    </b-field>


                                </div><!-- column -->
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="modalProofTransaction=false"/>
                        <button v-if="editRowData.approval_status !== 'APPROVED'"
                            label="Save"
                            class="button is-link">APPROVED</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->

    </div>
</template>


<script>

export default{
    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'book_bedspace_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',

            search: {
                bedspace: '',
            },

            fields: {
                start_date: null,
                nstart_date: null,
            },
            errors: {},

            modalProofTransaction: false,

            global_bookbedspace_id: 0,

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            dropFiles: null,

            proofTransURL: '',

            editRowData: {},

        }
    },
    methods: {
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
            axios.get(`/get-boarder-reservation?${params}`)
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

        openProofTransactionModal(rowData){
            this.editRowData = rowData;
            this.global_bookbedspace_id = rowData.book_bedspace_id;
            this.modalProofTransaction = true;
            this.proofTransURL = rowData.proof_transaction;
        },


        submitUpload: function(){
            var formData = new FormData();
            formData.append('proof_transaction', this.dropFiles);

            axios.post('/upload-proof-transaction/' + this.global_bookbedspace_id, formData).then(res=>{
                if(res.data.status === 'uploaded'){
                    this.$buefy.dialog.alert({
                        title: 'UPLOADED!',
                        message: 'Uploaded successfully.',
                        type: 'is-success',
                        onConfirm: ()=> {
                            this.loadAsyncData();
                            this.modalProofTransaction = false;
                            this.dropFiles = null;
                        }
                    });
                }
            })
        },

        submitApproved: function(){
            if(!this.fields.start_date){
                alert('Please select start data.');
                return false;
            }

            this.fields.nstart_date = this.fields.start_date.toLocaleDateString();

            axios.post('/boarder-reservation-approved/' + this.global_bookbedspace_id, this.fields).then(res=>{
                if(res.data.status==='approved'){
                    this.$buefy.dialog.alert({
                        title: "APPROVED!",
                        message: 'Boarder successfully approved.',
                        type: 'is-success',
                        onConfirm: ()=> {
                            this.loadAsyncData();
                            this.modalProofTransaction = false;
                        }
                    });
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            })
        },

        cancelReservation(row){
            this.$buefy.dialog.confirm({
                title: 'CANCEL?',
                type: 'is-danger',
                message: 'Are you sure you want to cancel this reservation?',
                cancelText: 'Cancel',
                confirmText: 'Delete?',
                onConfirm: () => this.submitCancellReservation(row.book_bedspace_id)
            });
        },
        submitCancellReservation(dataId){
            axios.post('/boarder-reservation-cancel/' + dataId).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },


    },

    mounted() {
        this.loadAsyncData();
    }


}
</script>


<style scoped>
.modal .animation-content .modal-card {
    overflow: visible !important;
}

.modal-card-body {
    overflow: visible !important;
}
</style>
