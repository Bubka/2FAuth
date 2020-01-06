<template>
    <div>
        <nav class="navbar is-black level is-mobile has-text-grey-lighter">
            <div class="level-left">
                <router-link :to="{ name: 'accounts' }" class="is-size-4 has-text-weight-light">2FAccount</router-link>
            </div>
            <div class="level-right">
                <p class="level-item" v-if="isLoggedIn">
                    <router-link :to="{ name: 'create' }" class="button is-black">
                        <span class="icon has-text-grey-light">
                            <font-awesome-icon :icon="['fas', 'plus-circle']" />
                        </span>
                    </router-link>
                </p>
                <p class="level-item" v-if="!isLoggedIn">
                    <router-link :to="{ name: 'login' }" class="button is-black">
                        Sign in
                    </router-link>
                </p>
                <p class="level-item" v-if="isLoggedIn">
                    <router-link :to="{ name: 'sign out' }" class="button is-black">
                        Sign out
                    </router-link>
                </p>
                <p class="level-item" v-if="!isLoggedIn">
                    <router-link :to="{ name: 'register' }" class="button is-black">
                        Register
                    </router-link>
                </p>
                <p class="level-item" v-if="isLoggedIn">
                    Hi, {{username}}
                </p>
            </div>
        </nav>

        <main class="py-4">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
    export default {
        name: 'App',
        data(){
            return {
                isLoggedIn : null,
                username : null
            }
        },
        mounted(){
            this.isLoggedIn = localStorage.getItem('jwt')
            this.username = localStorage.getItem('user')
        }
    }
</script>