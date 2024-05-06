<template>
    <div class="section">
        <div class="columns">

            <div class="column is-8 is-offset-2">

                <div style="margin: 15px 0;">
                    <a href="/" class="mb-5"><b>BACK TO HOME</b></a>
                </div>

                <b-tabs type="is-boxed">
                    <b-tab-item>
                        <template #header>
                            <b-icon icon="account"></b-icon>
                            <span> REGISTER AS BOARDER</span>
                        </template>

                        <register-boarder></register-boarder>
                    </b-tab-item>
                    <b-tab-item>
                        <template #header>
                            <b-icon icon="source-pull"></b-icon>
                            <span> REGISTER AS LANDOWNER </span>
                        </template>
                        <register-owner></register-owner>
                    </b-tab-item>
                </b-tabs>
            </div>
        </div>
    </div>
</template>

<script>


export default {




}
</script>

<style scoped>


.column{
    background:white;
    border-radius: .6em;
    padding:4em;
}
</style>
