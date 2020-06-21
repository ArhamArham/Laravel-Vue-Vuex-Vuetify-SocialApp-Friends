import Vue from 'vue'
import Vuerouter from 'vue-router'
import LoginComponent from './components/LoginComponent'
import SignupComponent from './components/SignupComponent'
import PostComponent from './components/PostComponent'
import ProfileComponent from './components/ProfileComponent'
import ShowProfileComponent from './components/ShowProfileComponent'
import SettingsComponent from './components/SettingsComponent'
import ColorSetting from './components/ColorSetting'
import UserSetting from './components/UserSetting'
import store from './Vuex'
Vue.use(Vuerouter);
// let id = Vuerouter.history.current.params.id ? this.$store.state.id : this.$router.history.current.params.id;


const ifNotAuthenticated=(to, from, next) => {
    if (localStorage.getItem('token')) {
        next('/post');
    } else {
        next();
    }
}
const ifAuthenticated=(to, from, next) => {
    if (localStorage.getItem('token')) {
      axios
        .get("api/verify",)
        .then((res)=>{
            store.commit('SET_USER_DATA', res.data)
            next()
        })
        .catch((err) => {
          if (err.response.status == '401') {
            localStorage.removeItem("token");
            next('/login')
          }
        });
    } else {
      next('/login');
    }
  }
const routes=[
    {
        path:'/',
        redirect:'login',
       
    },
    {
        path:'/login',
        name:'Login',
        component:LoginComponent,
        beforeEnter: ifNotAuthenticated
    },
    {
        path:'/signup',
        component:SignupComponent,
        beforeEnter: ifNotAuthenticated
    },
    {
        path:'/post',
        component:PostComponent,
        beforeEnter:ifAuthenticated
    },
    {
        path:'/profile',
        component:ProfileComponent,
        beforeEnter:ifAuthenticated
    },
    {
        path:'/profile/:id',
        component:ShowProfileComponent,
        beforeEnter:ifAuthenticated
    },
    {
        path:'/settings',
        component:SettingsComponent,
        children:[
            {
                path: 'user',
                component: UserSetting,
                name: 'user'
            },
            {
                path: 'color',
                component: ColorSetting,
                name: 'color'
            },
        ],
        beforeEnter:ifAuthenticated
    },
]

const router = new Vuerouter({routes})

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token') || null
    window.axios.defaults.headers['Authorization'] =token;
    next();
})
export default router