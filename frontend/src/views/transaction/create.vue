<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 rounded shadow">
                    <div class="card-body">
                        <h4>Add Transaction</h4>
                        <hr>

                        <form @submit.prevent="store">
                            <div class="form-group">
                                <label for="product_id" class="font-weight-bold">Product ID</label>
                                <input type="text" class="form-control" v-model="transaction.product_id" placeholder="Input product id">
                                <!-- validation -->
                                <div v-if="validation.product_id" class="mt-2 alert alert-danger">
                                    {{ validation.product_id[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="quantity" class="font-weight-bold">Quantity</label>
                                <input type="text" class="form-control" v-model="transaction.quantity" placeholder="Input quantity">
                                <!-- validation -->
                                <div v-if="validation.quantity" class="mt-2 alert alert-danger">
                                    {{ validation.quantity[0] }}
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
import { onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {

    setup() {

        //state transactions
        const transaction = reactive({
            product_id: '',
            quantity: ''
        })

        //state validation
        const validation = ref([])

        //vue router
        const router = useRouter()

        onMounted(() => {

        	if (!localStorage.getItem('token')) {
			    return router.push({ name: 'login' });
			}
		})

        //method store
        function store() {

            let product_id   = transaction.product_id
            let quantity = transaction.quantity

            axios.post('http://localhost:8000/api/transaction/add', {
                product_id: product_id,
                quantity: quantity
            }).then(() => {

                //redirect ke transaction index
                router.push({
                    name: 'transaction.index'
                })

            }).catch(error => {
                //assign state validation with error 
                validation.value = error.response.data
            })

        }

        //return
        return {
            transaction,
            validation,
            router,
            store
        }

    }

}
</script>

<style>
    body{
        background: lightgray;
    }
</style>