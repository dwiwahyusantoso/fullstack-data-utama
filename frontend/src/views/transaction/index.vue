<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 rounded shadow">
                    <div class="card-body">
                        <h4>Data Transaction</h4>
                        <hr>
                        <router-link :to="{name: 'transaction.create'}" class="btn btn-md btn-success">create</router-link>

                        <table class="table table-striped table-bordered mt-4">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Product ID</th>
                                    <th scope="col">Reference</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(transaction, index) in transactions" :key="index">
                                    <td>{{ transaction.product_id }}</td>
                                    <td>{{ transaction.reference_no }}</td>
                                    <td>{{ transaction.price }}</td>
                                    <td>{{ transaction.quantity }}</td>
                                    <td>{{ transaction.payment_amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
						    <Bootstrap5Pagination
						      :data="transactions"
						      @pagination-change-page="getResults"
						  />
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { onMounted, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {

    setup() {

    	//vue router
        const router = useRouter()

        //vue router
        const route = useRoute()

        //reactive state
        let transactions = ref([])

        //mounted
        onMounted(() => {

        	if (!localStorage.getItem('token')) {
			    return router.push({ name: 'login' });
			}

            //get API from Laravel Backend
            axios.get('http://localhost:8000/api/transaction')
            .then(response => {
              
              //assign state transactions with response data
              transactions.value = response.data.data

            }).catch(error => {
                console.log(error.response.data)
            })

        })

        const getResults = async (page = 1) => {
		    const response = await fetch(`http://localhost:8000/api/transaction?page=${page}`);
		    transactions.value = await response.json().data;
		}

		getResults();

        //return
        return {
            transactions
        }

    }

}
</script>

<style>
    body{
        background: lightgray;
    }
</style>