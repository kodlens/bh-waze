<template>
    <div>
        <div class="section">
            <div class="columns">
                <div class="column">

                    <div class="columns is-centered">
                        <div class="column is-10">
                            <div class="panel">
                                <div class="panel-heading">
                                    BOARDER PAYMENT LIST
                                </div>

                                <div class="panel-body">

                                    <div class="level">
                                        <div class="level-left">
                                            <b-field label="Page" label-position="on-border">
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
                                                <b-field label="Search" label-position="on-border">
                                                    <b-input type="text"
                                                             v-model="search.bedspace" placeholder="Search Bedspace"
                                                             @keyup.native.enter="loadAsyncData"/>
                                                    <b-input type="text"
                                                             v-model="search.lname" placeholder="Search Last Name"
                                                             @keyup.native.enter="loadAsyncData"/>
                                                    <p class="control">
                                                        <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                                    </p>
                                                </b-field>
                                            </div>
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

                                        <b-table-column field="payment_id" label="ID" v-slot="props">
                                            {{ props.row.payment_id }}
                                        </b-table-column>

                                        <b-table-column field="name" label="Name" v-slot="props">
                                            {{ props.row.lname }}, {{ props.row.fname }} {{ props.row.mname }}
                                        </b-table-column>

                                        <b-table-column field="bedspace_name" label="Bed Space House" v-slot="props">
                                            {{ props.row.bedspace_name }}
                                        </b-table-column>

                                        <b-table-column field="rental_price" label="Rental Price" v-slot="props">
                                            {{ props.row.rental_price }}
                                        </b-table-column>

                                        <b-table-column field="payment" label="Boarder Paid" v-slot="props">
                                            {{ props.row.payment }}
                                        </b-table-column>

                                        <b-table-column field=balance label="Balance" v-slot="props">
                                            {{ props.row.balance }}
                                        </b-table-column>

                                        <b-table-column field="date_pay" label="Date Pay" v-slot="props">
                                            {{ props.row.date_pay }}
                                        </b-table-column>

                                        <b-table-column field="payment_status" label="Payment Status" v-slot="props">
                                            {{ props.row.payment_status }}
                                        </b-table-column>

                                        <b-table-column field="receipt_img" label="Receipt" v-slot="props" centered>
                                            <span v-if="props.row.receipt_img">
                                                 <b-icon icon="check" type="is-success"></b-icon>
                                            </span>
                                            <span v-else>
                                                <b-icon icon="minus-circle-outline" type="is-danger"></b-icon>
                                            </span>
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


                                                <b-dropdown-item aria-role="listitem" @click="showModalTransaction(props.row)">Show Proof of Transaction</b-dropdown-item>
                                                <!-- <b-dropdown-item aria-role="listitem" @click="maskAsPaid(props.row)">Mark as Paid</b-dropdown-item> -->
                                                <b-dropdown-item v-if="props.row.receipt_img" aria-role="listitem" tag="a" :href="`boarder-payment-receipt/${props.row.payment_id}`">Show Receipt</b-dropdown-item>


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






        <!--modal show bill-->
        <b-modal v-model="modalProofTransaction" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submitSendBill">
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
                            <h1 class="title is-6">RECEIPT</h1>
                            <img :src="`/storage/payment_receipt/${rawData.receipt_img}`" />
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="modalProofTransaction=false"/>
                        <b-button v-if="rawData.receipt_img"
                            label="Mark As Paid"
                            @click="submitMarkPaid(rawData.payment_detail_id)"/>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close show modal bill-->


    </div>
</template>


<script>

export default{

    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'boarder_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',

            search: {
                bedspace: '',
                lname: '',
            },

            fields: {
                payment_date: new Date(),
                npayment_date: null,
            },

            modalProofTransaction: false,

            rawData: {},

            bhouses: [],
            bhouse: '',


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
                `perpage=${this.perPage}`,
                `bedspace=${this.search.bedspace}`,
                `lname=${this.search.lname}`,
                `bhousename=${this.bhouse}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-boarder-payment?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.forEach((item) => {
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


        showModalTransaction(row){
            this.modalProofTransaction = true;
            this.rawData = row;
        },

        submitSendBill: function(){
            this.fields.boarder_id = this.rawData.boarder_id;
            let d = new Date();
            this.fields.npayment_date = new Date(d.getFullYear(), d.getMonth(), this.fields.payment_date.getDate()).toLocaleDateString();

            axios.post('/boarder-submit-bill', this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'SAVED!',
                        message: 'Save successfully.',
                        type: 'is-success',
                        onConfirm: ()=> {
                            this.loadAsyncData();
                            this.modalSendBill = false;
                            this.rawData = {};
                        }
                    });
                }
            })
        },



        maskAsPaid(row){
            if(!row.receipt_img){
                this.$buefy.toast.open({
                    message: 'No uploaded receipt.',
                    type: 'is-danger'
                });
                return;
            }
            this.$buefy.dialog.confirm({
                title: 'MARK AS PAID?',
                type: 'is-warning',
                message: 'Are you sure you want to mark this as paid?',
                cancelText: 'Cancel',
                confirmText: 'MARK PAID?',
                onConfirm: () => this.submitMarkPaid(row.payment_detail_id)
            });
        },

        submitMarkPaid: function(dataid){
            axios.post('/boarder-payment-mark-paid/' + dataid).then(res => {
                if(res.data.status === 'paid'){
                    this.$buefy.toast.open({
                        message: 'Successfully mark as paid.',
                        type: 'is-success'
                    });
                    this.loadAsyncData();
                }
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
