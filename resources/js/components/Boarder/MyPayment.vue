<template>
    <div>
        <div class="section">
            <div class="columns">
                <div class="column">

                    <div class="columns is-centered">
                        <div class="column is-10">
                            <div class="panel">
                                <div class="panel-heading">
                                    BILLING
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

                                    <b-field>
                                        <b-button
                                            label="Clear selected"
                                            type="is-danger"
                                            icon-left="close"
                                            :disabled="!selected"
                                            @click="clearSelected" />
                                    </b-field>

                                    <b-table
                                        :data="data"
                                        :loading="loading"
                                        paginated
                                        backend-pagination
                                        :selected.sync="selected"
                                        :total="total"
                                        :per-page="perPage"
                                        @page-change="onPageChange"
                                        aria-next-label="Next page"
                                        aria-previous-label="Previous page"
                                        aria-page-label="Page"
                                        aria-current-label="Current page"
                                        backend-sorting
                                        :default-sort-direction="defaultSortDirection"
                                        @select="loadPaymentDetail"
                                        @sort="onSort">

                                        <b-table-column field="payment_id" label="ID" v-slot="props">
                                            {{ props.row.payment_id }}
                                        </b-table-column>

                                        <b-table-column field="bhouse_name" label="Bhouse Name" v-slot="props">
                                            {{ props.row.bhouse_name }}
                                        </b-table-column>

                                        <b-table-column field="date_pay" label="Billing Date" v-slot="props">
                                            {{ new Date(props.row.date_pay).toLocaleDateString() }}
                                        </b-table-column>

                                        <b-table-column field="bedspace_name" label="Bed Space Name" v-slot="props">
                                            {{ props.row.bedspace_name }}
                                        </b-table-column>

                                        <b-table-column field="rental_price" label="Rental Price" v-slot="props">
                                            {{ props.row.rental_price }}
                                        </b-table-column>

                                        <b-table-column field="balance" label="Balance" v-slot="props">
                                            {{ props.row.balance }}
                                        </b-table-column>
                                    </b-table>

                                    <h2 class="title is-5 mt-5">PAYMENT DETAILS</h2>
                                    <!--payment details -->
                                    <div class="columns">
                                        <div class="column">

                                            <b-table
                                                :data="dataDetail"
                                                :loading="loadingDetail"
                                                paginated
                                                backend-pagination
                                                :total="totalDetail"
                                                :per-page="perPageDetail"
                                                @page-change="onPageChangeDetail"
                                                aria-next-label="Next page"
                                                aria-previous-label="Previous page"
                                                aria-page-label="Page"
                                                aria-current-label="Current page"
                                                backend-sorting
                                                :default-sort-direction="defaultSortDirectionDetail"
                                                @sort="onSortDetail">

                                                <b-table-column field="payment_detail_id" label="Detail ID" v-slot="props">
                                                    {{ props.row.payment_detail_id }}
                                                </b-table-column>

                                                <b-table-column field="rental_price" label="Rental Price" v-slot="props">
                                                    {{ props.row.rental_price }}
                                                </b-table-column>

                                                <b-table-column field="amount_paid" label="Amount Paid" v-slot="props">
                                                    {{ props.row.amount_paid }}
                                                </b-table-column>

                                                <b-table-column field="balance" label="Balance" v-slot="props">
                                                    {{ props.row.balance }}
                                                </b-table-column>

                                                <b-table-column field="payment_status" label="Status" v-slot="props">
                                                    {{ props.row.payment_status }}
                                                </b-table-column>

                                                <b-table-column field="date_paid" label="Date Paid" v-slot="props">
<!--                                                    {{ new Date(props.row.date_paid).toLocaleDateString() }}-->
                                                    {{ props.row.date_paid }}
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

                                                        <b-dropdown-item aria-role="listitem" @click="openPayBill(props.row)">Pay Bill</b-dropdown-item>
                                                        <b-dropdown-item v-if="props.row.receipt_img" aria-role="listitem" tag="a" :href="`/my-payment-receipt/${props.row.payment_detail_id}`">Show Receipt</b-dropdown-item>
                                                        <!--                                                <b-dropdown-item aria-role="listitem" @click="openQRModal(props.row)">Show QR</b-dropdown-item>-->

                                                    </b-dropdown>
                                                    <!-- <div class="is-flex">
                                                        <b-button class="button is-small is-warning mr-1" tag="a" icon-right="pencil" @click="getData(props.row.bhouse_id)"></b-button>
                                                        <b-button class="button is-small is-danger mr-1" icon-right="delete" @click="confirmDelete(props.row.bhouse_id)"></b-button>
                                                    </div> -->
                                                </b-table-column>
                                            </b-table>

                                        </div>
                                    </div>

                                </div><!--panel -body -->
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- column parent -->
        </div><!--cols parent -->



        <!--modal create-->
        <b-modal v-model="modalPayBill" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submitPayBill">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Pay Bill Transaction</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalPayBill = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns is-centered">
                                <div class="column is-8">

                                    <b-field :type="this.errors.receipt_img ? 'is-danger':''"
                                             :message="this.errors.receipt_img ? this.errors.receipt_img[0] : ''">
                                        <b-upload v-model="dropFiles"
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
                                        <span v-if="dropFiles" class="tag is-primary" >
                                            {{dropFiles.name}}
                                            <button class="delete is-small" type="button"></button>
                                        </span>
                                    </div>
                                </div><!-- column -->
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Payment"
                                             :type="this.errors.payment ? 'is-danger':''"
                                             :message="this.errors.payment ? this.errors.payment[0] : ''">
                                        <b-numberinput :controls="false" v-model="fields.payment" placeholder="Payment"></b-numberinput>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="modalPayBill=false"/>
                        <button
                            label="Save"
                            class="button is-link">Upload</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


        <!--modal create-->
        <b-modal v-model="modalQr" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">QR Code</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalQr = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns is-centered">
                                <div class="column is-8">

                                    <div class="qr-container">

                                        <div>
                                            <qrcode :value="rowData.qr_ref" :options="{ width: 200 }"></qrcode>
                                        </div>
                                        <div><b>QR CODE: {{ rowData.qr_ref }}</b></div>

                                    </div>

                                </div><!-- column -->
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="modalQr=false"/>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


    </div>
</template>


<script>

export default{

    props:{
        propData:{
            type: String,
            default: '',
        }
    },
    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'payment_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',
            selected: null,

            dataDetail: [],
            totalDetail: 0,
            loadingDetail: false,
            sortFieldDetail: 'payment_id',
            sortOrderDetail: 'desc',
            pageDetail: 1,
            perPageDetail: 5,
            defaultSortDirectionDetail: 'asc',


            search: {
                bedspace: '',
            },

            fields: {
                payment: null,
                payment_id: 0,
                receipt_img: null,
            },

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            modalPayBill: false,
            modalQr: false,

            dropFiles: null,
            errors: {},

            rowData: {},


            global_payment_detail_id: 0,
            global_payment_id: 0,

        }
    },
    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `bedspace=${this.search.bedspace}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-my-payment?${params}`)
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






        loadPaymentDetail(row) {
            if(row != null){
                this.global_payment_id = row.payment_id;
            }

            console.log(row)
            const params = [
                `sort_by=${this.sortFieldDetail}.${this.sortOrderDetail}`,
                `perpage=${this.perPageDetail}`,
                `page=${this.pageDetail}`
            ].join('&')

            this.loadingDetail = true
            axios.get(`/get-my-payment-details/${this.global_payment_id}?${params}`)
                .then(({ data }) => {
                    this.dataDetail = [];
                    let currentTotal = data.total

                    if (data.total / this.perPageDetail > 1000) {
                        currentTotal = this.perPageDetail * 1000
                    }

                    this.totalDetail = currentTotal
                    data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.dataDetail.push(item)
                    })
                    this.loadingDetail = false
                })
                .catch((error) => {
                    this.dataDetail = []
                    this.totalDetail = 0
                    this.loadingDetail = false
                    throw error
                })
        },
        onPageChangeDetail(page) {
            this.page = page
            this.loadAsyncData()
        },
        onSortDetail(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },
        setPerPageDetail(){
            this.loadAsyncData()
        },




        openPayBill: function(row){
            console.log(row);
            this.modalPayBill = true;
            this.global_payment_detail_id = row.payment_detail_id;
            this.fields.payment_id = row.payment_id
        },

        openQRModal(row){
            this.modalQr = true;
            this.rowData = row;
            console.log(row);
        },

        submitPayBill: function(){
            let formData = new FormData();
            formData.append('payment_id', this.fields.payment_id);
            formData.append('payment', this.fields.payment != null ? this.fields.payment : '');
            formData.append('receipt_img', this.dropFiles != null ? this.dropFiles : '');

            axios.post(`/submit-pay-bill/${this.global_payment_detail_id}`, formData).then(res=>{
                if(res.data.status === 'uploaded'){
                    this.$buefy.dialog.alert({
                        title: 'PAYMENT UPLOADED!',
                        message: 'Payment and receipt uploaded successfully.',
                        type: 'is-success',
                        onConfirm: ()=> {
                            this.loadAsyncData();
                            this.modalPayBill = false;
                            this.dropFiles = null;
                            this.loadPaymentDetail(null);
                            this.fields = {};
                        }
                    })
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearSelected(){
            this.selected = null;
            this.global_payment_detail_id = 0;
            this.loadPaymentDetail(null);
        }


    },

    mounted() {
        this.loadAsyncData()

    }


}
</script>
