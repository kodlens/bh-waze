<template>
    <div>
          
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6">
                    
                    <div class="box">
                        <b-field label="Name">
                            {{ user.fname }} {{ user.mname }} {{ user.lname }}
                        </b-field>

                        <b-field label="Email">
                            {{ user.email }}
                        </b-field>

                        <b-field label="Contact No">
                            {{ user.contact_no }}
                        </b-field>

                        <b-field label="Sex">
                            {{ user.sex }}
                        </b-field>

                        <b-field label="Address">
                            {{ user.brgyDesc }}, {{ user.citymunDesc }} {{ user.provDesc }}
                        </b-field>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>


<script>

export default{
    data(){
        return{
            user: {},
        }
    },
    methods: {
        loadUser(){
            axios.get('/get-bhouse-user').then(res=>{
                this.user = res.data;
            })
        }
    },

    mounted() {
        this.loadUser();
    }
}
</script>

