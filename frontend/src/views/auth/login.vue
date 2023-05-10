<template>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div v-if="loginFailed" class="alert alert-danger">
                        Username or Password Invalid.
                    </div>
                <div class="card border-0 rounded shadow">
                    <div class="card-body">
                        <h4>Login</h4>
                        <hr>
                        <form @submit.prevent="login">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" v-model="user.username" class="form-control"
                                    placeholder="Username">
                            </div>
                            <div v-if="validation.username" class="mt-2 alert alert-danger">
                                {{ validation.username[0] }}
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" v-model="user.password" class="form-control"
                                    placeholder="Password">
                            </div>
                            <div v-if="validation.password" class="mt-2 alert alert-danger">
                                {{ validation.password[0] }}
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-2">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import { reactive, ref } from 'vue'
    import { useRouter } from 'vue-router'
    import axios from 'axios'

    export default {

        setup() {

            //inisialisasi vue router on Composition API
            const router = useRouter()

            //state user
            const user = reactive({
                username: '',
                password: '',
            })

            //state validation
            const validation = ref([])

            //state loginFailed
            const loginFailed = ref(null)

            //method login
            function login() {

                //define variable 
                let username = user.username
                let password = user.password

                //send server with axios
                axios.post('http://localhost:8000/api/auth/login', {
                        username,
                        password,
                })
                .then((response) => {

                    if(response.data.status) {

                        //set token
                        localStorage.setItem('token', response.data.token)

                        //redirect ke halaman transaction
                        return router.push({
                            name: 'transaction.index'
                        })

                        //set state loggedIn to true
                    	loginFailed.value = true
                    }


                }).catch((error) => {
                    //set validation dari error response
                    console.log(error.response)
                    validation.value = error.response.data
                })

            }

            return {
                user,           // <-- state user
                validation,     // <-- state validation 
                loginFailed,    // <-- state loggedIn
                login           // <-- method login
            }

        }

    }
</script>