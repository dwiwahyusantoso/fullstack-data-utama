<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 rounded shadow">
                    <div class="card-body">
                        <h4>Edit Product</h4>
                        <hr>

                        <form @submit.prevent="update">
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">Name</label>
                                <input type="text" class="form-control" v-model="product.name" placeholder="Input Name Product">
                                <!-- validation -->
                                <div v-if="validation.name" class="mt-2 alert alert-danger">
                                    {{ validation.name[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price" class="font-weight-bold">Price</label>
                                <input type="text" class="form-control" v-model="product.price" placeholder="Input Price Product">
                                <!-- validation -->
                                <div v-if="validation.price" class="mt-2 alert alert-danger">
                                    {{ validation.price[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="stock" class="font-weight-bold">Stock</label>
                                <input type="text" class="form-control" v-model="product.stock" placeholder="Input Stock Product">
                                <!-- validation -->
                                <div v-if="validation.stock" class="mt-2 alert alert-danger">
                                    {{ validation.stock[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="font-weight-bold">Description</label>
                                <input type="text" class="form-control" v-model="product.description" placeholder="Input Description Product">
                                <!-- validation -->
                                <div v-if="validation.description" class="mt-2 alert alert-danger">
                                    {{ validation.description[0] }}
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Save</button>
                        </form>                        

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

export default {

    setup() {

        //state products
        const product = reactive({
            name: '',
            price: '',
            stock: '',
            description: ''
        })

        //state validation
        const validation = ref([])

        //vue router
        const router = useRouter()

        //vue router
        const route = useRoute()

        //mounted
        onMounted(() => {

        	if (!localStorage.getItem('token')) {
			    return router.push({ name: 'login' });
			}

            //get API from Laravel Backend
            axios.get(`http://localhost:8000/api/product/get/${route.params.id}`)
            .then(response => {
              
              //assign state product with response data
              product.name    = response.data.data.name  
              product.price  = response.data.data.price
              product.stock  = response.data.data.stock
              product.description  = response.data.data.description  

            }).catch(error => {
                console.log(error.response.data)
            })

        })

        //method update
        function update() {

            let name   = product.name
            let price = product.price
            let stock = product.stock
            let description = product.description

            axios.put(`http://localhost:8000/api/product/update/${route.params.id}`, {
                name: name,
                price: price,
                stock: stock,
                description: description
            }).then(() => {

                //redirect ke product index
                router.push({
                    name: 'product.index'
                })

            }).catch(error => {
                //assign state validation with error 
                validation.value = error.response.data
            })

        }

        //return
        return {
            product,
            validation,
            router,
            update
        }

    }

}
</script>

<style>
    body{
        background: lightgray;
    }
</style>