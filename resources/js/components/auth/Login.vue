<template>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Login</div>

                <div class="card-body">
                    <form method="POST" action="">

                        <div class="form-group row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-mail Address</label>

                            <div class="col-md-6">
                                <input v-model="form.email" id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" autofocus>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input v-model="form.password" id="password" type="password" class="form-control " name="password" required autocomplete="current-password">
                            </div>
                        </div>

                        <!-- <div class="form-group row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                        </div> -->

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="button" class="btn btn-primary" @click="attemptLogin()">
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data(){
        return{
            form : {
                email : null,
                password : null,
            }
        }
    },
    created(){
        /**
         * if user is already logged in go to home route
         */
        if(User.isLoggedIn()){
            this.$router.push({name:'dashboard'})
        }
    },
    methods:{
        attemptLogin(){
            axios.post('/api/v1/login',this.form)
            .then(response => {
                User.responseAfterLogin(response)
                if(User.isLoggedIn()){ location.reload() }
                // this.$router.push({name:'home'})
            })
            .catch(error =>
                console.log(error)
            )
        }
    }
}
</script>

<style>

</style>
