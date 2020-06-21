import Vue from 'vue'
import Vuex from 'vuex'
import user from './user';
import post from './post';
import profile from './profile';
Vue.use(Vuex);
const store = new Vuex.Store({
    modules: {
        profile,
        user,
        post
        
    },
    state: {
        color:localStorage.getItem('color') || 'info',
        userData:[],
    },
    mutations: {
        SET_USER_DATA:(state,data)=>{
            state.userData=data
    }
    }
})
export default store;