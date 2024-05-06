<template>
    <div>
        <div class="section">
            <div class="panel">
                <div class="columns">
                    <div class="column is-10 is-offset-1">
                        <div class="is-flex is-justify-content-center mb-2" style="font-size: 20px; font-weight: bold;">LIST OF BOARDING HOUSE</div>

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
                                                 v-model="search.bhousename" placeholder="Search Bhouse"
                                                 @keyup.native.enter="loadAsyncData"/>
                                        <p class="control">
                                            <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                        </p>
                                    </b-field>
                                </div>
                            </div>
                        </div>

                        <div class="buttons mt-3 is-right">
                            <b-button tag="a" href="/boarding-house/create" icon-right="account-arrow-up-outline" class="is-success">NEW BOARDING HOUSE</b-button>
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

                            <b-table-column field="bhouse_id" label="ID" v-slot="props">
                                {{ props.row.bhouse_id }}
                            </b-table-column>

                            <b-table-column field="bhouse_name" label="Boarding House Name" v-slot="props">
                                {{ props.row.bhouse_name }}
                            </b-table-column>

                            <b-table-column field="bhouse_desc" label="Boarding House Description" v-slot="props">
                                {{ props.row.bhouse_desc }}
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

                                    <b-dropdown-item aria-role="listitem" @click="openLink(props.row.bhouse_id)">Modify</b-dropdown-item>
                                    <b-dropdown-item aria-role="listitem" tag="a" :href="`/boarding-house-rooms/` + props.row.bhouse_id">Room</b-dropdown-item>
                                    <b-dropdown-item aria-role="listitem" @click="confirmDelete(props.row.bhouse_id)">Delete</b-dropdown-item>

                                </b-dropdown>
                                <!-- <div class="is-flex">
                                    <b-button class="button is-small is-warning mr-1" tag="a" icon-right="pencil" @click="getData(props.row.bhouse_id)"></b-button>
                                    <b-button class="button is-small is-danger mr-1" icon-right="delete" @click="confirmDelete(props.row.bhouse_id)"></b-button>
                                </div> -->
                            </b-table-column>
                        </b-table>
                    </div><!--close column-->
                </div>
            </div>
        </div><!--section div-->
    </div>
</template>

<script>

export default{
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

            search: {
                bhousename: '',
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
                `bhousename=${this.search.bhousename}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-bhouses?${params}`)
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
            axios.delete('/boarding-house/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },


        openLink(id){
            window.location = '/boarding-house/' +id + '/edit';
        }


    },

    mounted() {
        this.loadAsyncData();

    }
}
</script>


<style scoped>
.panel{
    padding: 25px;
}

</style>
