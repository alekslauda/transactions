import VueRouter from 'vue-router'

// Pages
import Login from './pages/Login'
import Dashboard from './pages/user/Dashboard'
import PageNotFound from "./components/PageNotFound";
import RecentTransactions from "./pages/admin/RecentTransactions";
import TransactionDetails from "./pages/admin/TransactionDetails";

// Routes
const routes = [
    {
        path: '/',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    // USER ROUTES
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            auth: true
        }
    },
    // ADMIN ROUTES
    {
        path: '/recent-transactions',
        name: 'recent.transactions',
        component: RecentTransactions,
        meta: {
            auth: {
                roles: 1,
                redirect: {name: 'login'},
                forbiddenRedirect: '/403'
            }
        }
    },
    {
        path: '/transactions/:id',
        name:'transaction.details',
        component: TransactionDetails,
        meta: {
            auth: {
                roles: 1,
                redirect: {name: 'login'},
                forbiddenRedirect: '/403'
            }
        }
    },
    {
        path: '*',
        component: PageNotFound,
    }
]

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
})

export default router
