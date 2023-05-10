<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 rounded shadow">
                    <div class="card-body">
                        <h4>Data Product</h4>
                        <hr>
                        <router-link :to="{name: 'product.create'}" class="btn btn-md btn-success">create</router-link>

                        <table class="table table-striped table-bordered mt-4">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Stock</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in products" :key="index">
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.price }}</td>
                                    <td>{{ product.stock }}</td>
                                    <td>{{ product.description }}</td>
                                    <td class="text-center">
                                        <router-link :to="{name: 'product.edit', params:{id: product.id }}" class="btn btn-sm btn-primary mx-1">EDIT</router-link>
                                        <button @click.prevent="productDelete(product.id)" class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
						    <Bootstrap5Pagination
						      :data="products"
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
        let products = ref([])

        //mounted
        onMounted(() => {

        	if (!localStorage.getItem('token')) {
			    return router.push({ name: 'login' });
			}

            //get API from Laravel Backend
            axios.get('http://localhost:8000/api/product')
            .then(response => {
              
              //assign state products with response data
              products.value = response.data.data

            }).catch(error => {
                console.log(error.response.data)
            })

        })

        function productDelete(id) {
            
		   //delete data post by ID
		   axios.delete(`http://localhost:8000/api/product/delete/${id}`)
		   .then(() => {
		              
		       //splice products 
		       products.value.splice(products.value.indexOf(id), 1);

		    }).catch(error => {
		        console.log(error.response.data)
		    })

		}

		const getResults = async (page = 1) => {
		    const response = await fetch(`http://localhost:8000/api/product?page=${page}`);
		    products.value = await response.json().data;
		}

		getResults();

        //return
        return {
            products,
            productDelete
        }

    }

}
</script>

<style>
    body{
        background: lightgray;
    }
</style>