<template>
    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <a class="navbar-brand" href="#">Velin Transfers</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" v-if="$auth.check(roles.USER)" v-for="(route, key) in routes.user"
                    v-bind:key="route.path">
                    <router-link :class="'nav-link'" :to="{ name : route.path }" :key="key">
                        {{route.name}}
                    </router-link>
                </li>
                <li class="nav-item" v-if="$auth.check(roles.ADMIN)" v-for="(route, key) in routes.admin"
                    v-bind:key="route.path">
                    <router-link :class="'nav-link'" :to="{ name : route.path }" :key="key">
                        {{route.name}}
                    </router-link>
                </li>
            </ul>
            <span v-if="$auth.check()" class="navbar-text">
                <a href="#" class="nav-link" @click.prevent="$auth.logout()">Logout</a>
            </span>
        </div>
    </nav>
</template>

<script>

    import roles from '../constants'

    export default {
        data() {
            return {
                roles: {},
                routes: {
                    user: [
                        {
                            name: 'Dashboard',
                            path: 'dashboard'
                        }
                    ],
                    admin: [
                        {
                            name: 'Dashboard',
                            path: 'dashboard'
                        },
                        {
                            name: 'Recent Transactions',
                            path: 'recent.transactions'
                        }
                    ]
                }
            }
        },
        mounted() {
            this.roles = roles;
        }
    }
</script>
<style>
    .nav-item:last-child {
        float: right;
    }
</style>
