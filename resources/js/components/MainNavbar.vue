<template>
<div>

    <b-navbar class="animate__animated animate__fadeIn is-primary">
        <template #brand>
            <b-navbar-item>
                <img class ="logo"
                     src="/img/logo.png">
            </b-navbar-item>
        </template>

        <template #start>
        
        </template>

        <template #end>
            <b-navbar-item href="/">
                <b-icon icon="home"></b-icon>
                Home
            </b-navbar-item>
<!--            <b-navbar-item href="#">-->
<!--                About-->
<!--            </b-navbar-item>-->
<!--            <b-navbar-item href="#">-->
<!--                Contact-->
<!--            </b-navbar-item>-->

            <b-navbar-item tag="div" v-if="user.username">
                <b-navbar-item href="/gate">
                    <b-icon icon="view-dashboard"></b-icon>
                    <strong>Dashboard</strong>
                </b-navbar-item>
                <b-navbar-item @click="open =  true">
                    <b-icon icon="menu"></b-icon>
                    <strong>MENU</strong>
                </b-navbar-item>
                <b-navbar-item @click="logout">
                    <b-icon icon="logout"></b-icon>
                    <strong>Logout</strong>
                </b-navbar-item>
            </b-navbar-item>
            <b-navbar-item tag="div" v-else>
                <b-navbar-item href="/register">
                    <strong>Register</strong>
                </b-navbar-item>
                <b-navbar-item @click="isModalActive = true">
                    <strong>Login</strong>
                </b-navbar-item>
            </b-navbar-item>

        </template>
    </b-navbar>



    <section>
        <b-sidebar
            type="is-light"
            :fullheight="fullheight"
            :fullwidth="fullwidth"
            :overlay="overlay"
            :right="right"
            v-model="open"
        >
            <div class="p-4">
                <h3 class="title is-4">BOARDER</h3>
                <b-menu>
                    <b-menu-list label="Menu">
                        <b-menu-item icon="monitor-dashboard" label="Dashboard" tag="a" href="/boarder-dashboard"></b-menu-item>
                    </b-menu-list>

                    <b-menu-list>
                        <b-menu-item label="My Reservation" icon="home-floor-a" tag="a" href="/my-reservation"></b-menu-item>
                    </b-menu-list>

                    <b-menu-list>
                        <b-menu-item label="Billing" icon="currency-php" tag="a" href="/my-billing"></b-menu-item>
                    </b-menu-list>


                    <b-menu-list label="Actions">
                        <b-menu-item @click="logout" label="Logout"></b-menu-item>
                    </b-menu-list>
                </b-menu>
            </div>
        </b-sidebar>
    </section>




    <!--modal-->
    <b-modal v-model="isModalActive" has-modal-card
             trap-focus width="640" aria-role="dialog" aria-modal>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Login </p>
                <button type="button" class="delete"
                        @click="isModalActive = false"/>
            </header>

            <div class="modal-card-body">
                <div>
                    <form @submit.prevent="submit">
                        <b-field label="Username"
                                 label-position="on-border"
                                 :type="errors.username ? 'is-danger' : ''"
                                 :message="errors.username ? errors.username[0] : ''">
                            <b-input type="text" v-model="fields.username" placeholder="Username" expanded auto-focus></b-input>
                        </b-field>

                        <b-field label="Password" label-position="on-border"
                                 :type="errors.password ? 'is-danger' : ''"
                                 :message="errors.password ? errors.password[0] : ''">
                            <b-input type="password" v-model="fields.password" password-reveal placeholder="Password" expanded auto-focus></b-input>
                        </b-field>

                        <footer class="modal-card-foot">
                            <button
                                class="button is-success"
                                label="LOGIN"
                                type="is-success">LOGIN</button>

                            <b-button
                                label="Close"
                                @click="isModalActive=false"></b-button>
                        </footer>
                    </form>
                </div>
            </div> <!--modal card body -->
        </div>
    </b-modal>







</div>
</template>

<script>
export default {
    props: {
        propUser: {
            type: String,
            default: '',
        }
    },

    data(){
        return{
            open: false,
            overlay: false,
            fullheight: true,
            fullwidth: false,
            right: true,


            isModalActive: false,
            fields: {},
            errors: {},

            user: {},
        }
    },
    methods: {
        submit: function(){
            axios.post('/login', this.fields).then(res=>{
                window.location = '/gate'
            }).catch(err => {
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            });
        },
        logout: function(){
            axios.post('/logout', this.fields).then(res=>{
                window.location = '/';
            });
        },

        initData(){
            if(this.propUser){
                this.user = JSON.parse(this.propUser);
            }

        }
    },

    mounted() {
        this.initData();
    }
}
</script>

<style scoped>
    .logo{
        height: 200px;
    }

</style>
