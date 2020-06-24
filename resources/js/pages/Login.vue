<template>
    <div class="container login-form">

        <div class="card card-default">
            <div class="card-header text-center">
                <i class='fas fa-key' style='font-size:20px'></i>
            </div>


            <div class="card-body">
                <form autocomplete="off" @submit.prevent="login" method="post">
                    <p v-if="has_error" class="alert alert-danger">Invalid credentials.</p>
                    <div class="form-group">
                        <input type="text" id="username" class="form-control"
                               placeholder="Username" v-model="username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password"
                               class="form-control" placeholder="Password"
                               v-model="password" required>
                    </div>
                    <button type="submit" class="btn btn-block btn-login">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                username: null,
                password: null,
                has_error: false
            }
        },

        mounted() {
            //
        },

        methods: {
            login() {
                // get the redirect object
                var redirect = this.$auth.redirect()
                var app = this
                this.$auth.login({
                    params: {
                        username: app.username,
                        password: app.password
                    },
                    success: function () {
                        // handle redirection

                        const redirectTo = redirect ? redirect.from.name : this.$auth.user().account_type === 1 ? 'recent.transactions' : 'dashboard'

                        console.log("redirect", redirectTo);
                        console.log("user", this.$auth.user());

                        this.$router.push({name: redirectTo})
                    },
                    error: function () {
                        app.has_error = true
                    },
                    rememberMe: true,
                    fetchUser: true
                })
            }
        }
    }
</script>
<style>
    .login-form {
        width: 400px;
        height: 350px;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
    }

    .btn-login:hover {
        background-color: #109eb5;
    }

    .card-header, .btn-login {
        background-color: #117a8b
    }
</style>
