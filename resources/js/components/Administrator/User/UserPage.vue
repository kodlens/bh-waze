<template>
    <div>
        <div class="section">
            <div class="panel">
                <div class="columns">
                    <div class="column is-10 is-offset-1">
                        <div class="is-flex is-justify-content-center mb-2" style="font-size: 20px; font-weight: bold;">LIST OF USERS</div>

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

                        <div class="buttons mt-3 is-right">
                            <b-button @click="openModal" icon-right="account-arrow-up-outline" class="is-success">NEW</b-button>
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

                            <b-table-column field="user_id" label="ID" v-slot="props">
                                {{ props.row.user_id }}
                            </b-table-column>

                            <b-table-column field="username" label="Username" v-slot="props">
                                {{ props.row.username }}
                            </b-table-column>

                            <b-table-column field="name" label="Name" v-slot="props">
                                {{ props.row.lname }}, {{ props.row.fname }} {{ props.row.mname }}
                            </b-table-column>

                            <b-table-column field="sex" label="Sex" v-slot="props">
                                {{ props.row.sex }}
                            </b-table-column>

                            <b-table-column field="email" label="Email" v-slot="props">
                                {{ props.row.email }}
                            </b-table-column>

                            <b-table-column field="role" label="Role" v-slot="props">
                                {{ props.row.role }}
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

                                    <b-dropdown-item aria-role="listitem" @click="getData(props.row.user_id)">Modify</b-dropdown-item>
                                    <b-dropdown-item aria-role="listitem" @click="confirmDelete(props.row.user_id)">Delete</b-dropdown-item>
                                    <b-dropdown-item aria-role="listitem" @click="openModalResetPassword(props.row.user_id)">Reset Password</b-dropdown-item>

                                </b-dropdown>


<!--                                <div class="is-flex">-->
<!--                                    <b-button class="button is-small is-warning mr-1" tag="a" icon-right="pencil" @click="getData(props.row.user_id)"></b-button>-->
<!--                                    <b-button class="button is-small is-danger mr-1" icon-right="delete" @click="confirmDelete(props.row.user_id)"></b-button>-->
<!--                                    <b-button class="button is-small is-link mr-1" icon-right="lock-reset" @click="openModalResetPassword(props.row.user_id)"></b-button>-->
<!--                                </div>-->
                            </b-table-column>
                        </b-table>
                    </div><!--close column-->
                </div>

            </div>
        </div><!--section div-->



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
                        <p class="modal-card-title">User Information</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Username"
                                             :type="this.errors.username ? 'is-danger':''"
                                             :message="this.errors.username ? this.errors.username[0] : ''">
                                        <b-input v-model="fields.username" type="text" icon="account"></b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns" v-if="global_id < 1">
                                <div class="column">
                                    <b-field label="Password"
                                             :type="this.errors.password ? 'is-danger':''"
                                             :message="this.errors.password ? this.errors.password[0] : ''">
                                        <b-input type="password" v-model="fields.password" icon="lock" password-reveal></b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Re-type Password">
                                        <b-input type="password" v-model="fields.password_confirmation" password-reveal></b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Email"
                                             :type="this.errors.email ? 'is-danger':''"
                                             :message="this.errors.email ? this.errors.email[0] : ''">
                                        <b-input v-model="fields.email" placeholder="E-mail" type="email"></b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Contact No"
                                             :type="this.errors.contact_no ? 'is-danger':''"
                                             :message="this.errors.contact_no ? this.errors.contact_no[0] : ''">
                                        <b-input type="text" placeholder="Contact No" v-model="fields.contact_no" @input="checkMobileNo"></b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Last Name"
                                             :type="this.errors.lname ? 'is-danger':''"
                                             :message="this.errors.lname ? this.errors.lname[0] : ''">
                                        <b-input v-model="fields.lname" placeholder="Last Name" type="text"></b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="First Name"
                                             :type="this.errors.fname ? 'is-danger':''"
                                             :message="this.errors.fname ? this.errors.fname[0] : ''">
                                        <b-input v-model="fields.fname" placeholder="First Name" type="text"></b-input>
                                    </b-field>
                                </div>
                            </div>


                            <div class="columns">
                                <div class="column">
                                    <b-field label="Middle Name">
                                        <b-input placeholder="Middle Name" v-model="fields.mname" type="text"></b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Suffix">
                                        <b-input placeholder="Middle Name" v-model="fields.suffix" type="text"></b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Sex" expanded
                                             :type="this.errors.sex ? 'is-danger':''"
                                             :message="this.errors.sex ? this.errors.sex[0] : ''">
                                        <b-select placeholder="Sex" v-model="fields.sex" icon="account" expanded>
                                            <option value="MALE">MALE</option>
                                            <option value="FEMALE">FEMALE</option>
                                        </b-select>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Role" expanded
                                             :type="this.errors.role ? 'is-danger':''"
                                             :message="this.errors.role ? this.errors.role[0] : ''">
                                        <b-select placeholder="Role" v-model="fields.role" icon="account" expanded>
                                            <option value="BOARDER">BOARDER</option>
                                            <option value="LANDOWNER">LANDOWNER</option>
                                        </b-select>
                                    </b-field>
                                </div>
                            </div>

                            <div v-if="fields.role === 'BOARDER'">
                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Guardian Name" expanded
                                                 :type="this.errors.guardian_name ? 'is-danger':''"
                                                 :message="this.errors.guardian_name ? this.errors.guardian_name[0] : ''">
                                            <b-input type="text" v-model="fields.guardian_name" placeholder="Guardian Name"></b-input>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Guardian Contact No" expanded
                                                 :type="this.errors.guardian_contact_no ? 'is-danger':''"
                                                 :message="this.errors.guardian_contact_no ? this.errors.guardian_contact_no[0] : ''">
                                            <b-input type="text" v-model="fields.guardian_contact_no" placeholder="Guardian Contact No"></b-input>
                                        </b-field>
                                    </div>
                                </div>
                            </div>
                            <div v-if="fields.role === 'BOARDER'">
                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Guardian Address" expanded
                                                 :type="this.errors.guardian_address ? 'is-danger':''"
                                                 :message="this.errors.guardian_address ? this.errors.guardian_address[0] : ''">
                                            <b-input type="text" v-model="fields.guardian_address" placeholder="Guardian Address"></b-input>
                                        </b-field>
                                    </div>
                                </div>
                            </div>


                            <hr>
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Province" expanded
                                             :type="this.errors.province ? 'is-danger':''"
                                             :message="this.errors.province ? this.errors.province[0] : ''">
                                        <b-select v-model="fields.province" @input="loadCity" expanded>
                                            <option v-for="(item, index) in provinces" :key="index" :value="item.provCode">{{ item.provDesc }}</option>
                                        </b-select>
                                    </b-field>
                                </div>

                                <div class="column">
                                    <b-field label="City" expanded
                                             :type="this.errors.city ? 'is-danger':''"
                                             :message="this.errors.city ? this.errors.city[0] : ''">
                                        <b-select v-model="fields.city" @input="loadBarangay" expanded>
                                            <option v-for="(item, index) in cities" :key="index" :value="item.citymunCode">{{ item.citymunDesc }}</option>
                                        </b-select>
                                    </b-field>
                                </div>
                            </div>


                            <div class="columns">
                                <div class="column">
                                    <b-field label="Barangay" expanded
                                             :type="this.errors.barangay ? 'is-danger':''"
                                             :message="this.errors.barangay ? this.errors.barangay[0] : ''">
                                        <b-select v-model="fields.barangay" expanded>
                                            <option v-for="(item, index) in barangays" :key="index" :value="item.brgyCode">{{ item.brgyDesc }}</option>
                                        </b-select>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Street">
                                        <b-input v-model="fields.street"
                                                 placeholder="Street">
                                        </b-input>
                                    </b-field>
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



        <!--modal reset password-->
        <b-modal v-model="isModalResetPassword" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submitResetPassword">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">RESET PASSWORD</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalResetPassword = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">

                            <b-field label="Password" label-position="on-border"
                                     :type="this.errors.password ? 'is-danger':''"
                                     :message="this.errors.password ? this.errors.password[0] : ''">
                                <b-input type="password" password-reveal v-model="fields.password" placeholder="Password">
                                </b-input>
                            </b-field>
                            <b-field label="Re-type Password" label-position="on-border"
                                     :type="this.errors.password_confirmation ? 'is-danger':''"
                                     :message="this.errors.password_confirmation ? this.errors.password_confirmation[0] : ''">
                                <b-input type="password" password-reveal v-model="fields.password_confirmation"
                                         placeholder="Re-type Password">
                                </b-input>
                            </b-field>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="isModalResetPassword=false"/>
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">RESET PASSWORD</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal reset password-->


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


            global_id : 0,

            search: {
                lname: '',
            },

            isModalCreate: false,
            isModalResetPassword : false,

            fields: {
                username: '',
                lname: '', fname: '', mname: '',
                password: '', password_confirmation : '',
                sex : '', role: '',  email : '', contact_no : '',
                province: '', city: '', barangay: '', street: ''
            },
            errors: {},

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            provinces: [],
            cities: [],
            barangays: [],
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
            axios.get(`/get-users?${params}`)
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

        openModal(){
            this.isModalCreate=true;
            this.fields = {};
            this.errors = {};
        },

        loadProvince: function(){
            axios.get('/load-provinces').then(res=>{
                this.provinces = res.data;
            })
        },

        loadCity: function(){
            axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                this.cities = res.data;
            })
        },

        loadBarangay: function(){
            axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                this.barangays = res.data;
            })
        },


        submit: function(){

            if(this.global_id > 0){
                //update
                axios.put('/users/'+this.global_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
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
                axios.post('/users', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
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
        },


        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete user account?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/users/' + delete_id).then(res => {
                if(res.data.status === 'deleted'){
                    this.loadAsyncData();
                }

            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearFields(){
            this.fields = {
                    username: '',
                    lname: '', fname: '', mname: '',
                    password: '', password_confirmation : '',
                    sex : '', role: '',  email : '', contact_no : '',
                    province: '', city: '', barangay: '', street: ''
            };
        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;


            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/users/'+data_id).then(res=>{
                this.fields = res.data;
                let tempData = res.data;
                //load city first
                axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                    //load barangay
                    this.cities = res.data;
                    axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                        this.barangays = res.data;
                        this.fields = tempData;
                    });
                });
            });
        },

        openModalResetPassword(dataId){
            this.fields = {};
            this.isModalResetPassword = true;
            this.global_id = dataId;
        },
        submitResetPassword: function(){
            axios.post('/user-reset-password/' +this.global_id, this.fields).then(res=>{
                if(res.data.status === 'reseted'){
                    this.$buefy.dialog.alert({
                        title: 'RESET SUCCESSFULLY!',
                        message: 'Password reset successfully',
                        type: 'is-success',
                        onConfirm: ()=>{
                            this.fields = {};
                            this.global_id = 0;
                            this.loadAsyncData();
                            this.isModalResetPassword = false;
                        }
                    });
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            })
        },


        checkMobileNo(evt){
            var phoneno = /^(09|\+639)\d{9}$/;
            if(evt.match(phoneno)){
                this.errors.contact_no = false;
            }else{
                this.errors.contact_no = true;
                this.errors.contact_no = ['Invalid mobile number format. Valid format sample is (+639161234123)'];

            }
        }



    },

    mounted() {
        this.loadAsyncData();
        this.loadProvince();
    }
}
</script>


<style scoped>
    .panel{
        padding: 25px;
    }

</style>
