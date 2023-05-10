import { createRouter, createWebHistory } from 'vue-router'
import auth from '../middleware/auth';

//define a routes
const routes = [
    {
        path: '/product/',
        name: 'product.index',
        component: () => import('@/views/product/index.vue'),
        meta: {
        	middleware: auth,
        },
    },
    {
        path: '/product/create',
        name: 'product.create',
        component: () => import('@/views/product/create.vue')
    },
    {
        path: '/product/edit/:id',
        name: 'product.edit',
        component: () => import('@/views/product/edit.vue')
    },
    {
        path: '/transaction/',
        name: 'transaction.index',
        component: () => import('@/views/transaction/index.vue')
    },
    {
        path: '/transaction/create',
        name: 'transaction.create',
        component: () => import('@/views/transaction/create.vue')
    },
    {
        path: '/register/',
        name: 'register',
        component: () => import('@/views/auth/register.vue')
    },
    {
        path: '/login/',
        name: 'login',
        component: () => import('@/views/auth/login.vue')
    }
]

//create router
const router = createRouter({
    history: createWebHistory(),
    routes  // config routes
})

export default router