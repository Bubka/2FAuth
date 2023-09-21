<template>
    <div>
        <h1>On About view</h1>
        <div>
            {{ infos }}
        </div>
        <br />
        <div>
            {{ toto }}
        </div>
        <router-link :to="{ name: 'accounts' }">Go to About</router-link>
    </div>
</template>

<script>
    import SystemService from "@/services/systemService";

    export default {

        name: 'About',

        data() {
            return {
                infos : null,
                toto : '',
            }
        },

        mounted() {
            this.getInfos().then()
            this.toto = 'jknlkjnlkjnlkjnlkjn'
        },

        methods: {
            
            async getInfos()  {
                await SystemService.getSystemInfos().then(response => {
                    this.infos = response.data
                })
            },
        }
    }
</script>