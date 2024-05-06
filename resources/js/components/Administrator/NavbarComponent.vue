<template>
    <div>
        <div class="mynav">
            <div class="burger-button" @click="open = true">
                <div class="burger-div"></div>
                <div class="burger-div"></div>
                <div class="burger-div"></div>
            </div>
        </div>

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
                    <h3 class="title is-4">ADMINISTRATOR</h3>
                    <b-menu>
                        <b-menu-list label="Menu">
                            <b-menu-item icon="information-outline" label="Dashboard" tag="a" href="/dashboard"></b-menu-item>
<!--                            <b-menu-item icon="settings">-->
<!--                                <template #label="props">-->
<!--                                    Administrator-->
<!--                                    <b-icon class="is-pulled-right" :icon="props.expanded ? 'menu-down' : 'menu-up'"></b-icon>-->
<!--                                </template>-->
<!--                                <b-menu-item icon="account" tag="a" href="/users" label="Users"></b-menu-item>-->
<!--                                <b-menu-item icon="cellphone-link">-->
<!--                                    <template #label>-->
<!--                                        Devices-->
<!--                                        <b-dropdown aria-role="list" class="is-pulled-right" position="is-bottom-left">-->
<!--                                            <template #trigger>-->
<!--                                                <b-icon icon="dots-vertical"></b-icon>-->
<!--                                            </template>-->
<!--                                            <b-dropdown-item aria-role="listitem">Action</b-dropdown-item>-->
<!--                                            <b-dropdown-item aria-role="listitem">Another action</b-dropdown-item>-->
<!--                                            <b-dropdown-item aria-role="listitem">Something else</b-dropdown-item>-->
<!--                                        </b-dropdown>-->
<!--                                    </template>-->
<!--                                </b-menu-item>-->
<!--                                <b-menu-item icon="cash-multiple" label="Payments" disabled></b-menu-item>-->
<!--                            </b-menu-item>-->
                            <b-menu-item icqon="account" label="User Account">
                                <b-menu-item label="All Accounts" tag="a" href="/users"></b-menu-item>
                                <b-menu-item label="Request Account" tag="a" href="/request-accounts"></b-menu-item>
                            </b-menu-item>

                        </b-menu-list>


                        <b-menu-list label="Actions">
                            <b-menu-item @click="logout" label="Logout"></b-menu-item>
                        </b-menu-list>
                    </b-menu>
                </div>
            </b-sidebar>
        </section>
    </div>

</template>

<script>
export default {
    data(){
        return{
            open: false,
            overlay: false,
            fullheight: true,
            fullwidth: false,
            right: false
        }
    },
    methods: {
        logout(){
            axios.post('/logout').then(()=>{
                window.location = '/';
            })
        }
    }
}
</script>

<style scoped>
    .logo{
        padding: 0 30px 0 30px;
        height: 90px;
    }

    .burger-div{
        width: 20px;
        height: 3px;
        background-color:black;
        margin: 0 auto 3px 0;

        border-radius: 10px;
    }

    .burger-button{
        display: flex; 
        flex-direction: column;
    }

    .mynav{
        padding: 15px;
        background: dodgerblue;
        /*border-bottom: 2px solid rgba(22, 48, 69, 0.53);*/
        box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
    }

</style>
