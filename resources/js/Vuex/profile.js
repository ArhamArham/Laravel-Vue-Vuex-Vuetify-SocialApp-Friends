import store from './index'
export default {
        namespaced:true,
        state: {
            authUser:[],
            profile:[],
            authUserPosts:[],
            followDetails:{
                posts:0,
                following:0,
                followers:0,
                f_o_n:false,
            },
            loading: {
                user: false,
                form: false,
                ui: false,
                post:false,
            },
        },
        getters: {
            loadingUser: state => state.loading.user,
            userData: state => state.authUser,
            authUserPostsList:state => state.authUserPosts,
            profile: state => state.profile,
            followDetails: state => state.followDetails,
        },
        mutations: {
            SET_LOADING: (state, { name, value }) => state.loading[name] = value,
            SET_AUTH_USER: (state, data) => {
                state.authUser = data;
            },
            SET_AUTH_USER_PROFILE:(state,data)=>{
                state.profile=data
            },
            PUT_PROFILE_PHOTO:(state,data)=>{
                state.profile=data
            },
            PUT_IFINITE_AUTH_USER_POSTS:(state,data)=>{
                data.forEach(post=>{
                    state.authUserPosts.push(post)
                })
            },
            SET_AUTH_USER_POSTS:(state,data)=>{
                state.authUserPosts=data
            },
            SET_FOLLOWDETAILS:(state,data)=>{
                state.followDetails.posts=data.posts
                state.followDetails.followers=data.followers
                state.followDetails.following=data.following
                state.followDetails.f_o_n=data.f_o_n
            },
        },
        actions: {
            FETCH_USER_PROFILE: ({commit},id) =>  {
                commit('SET_LOADING',{name:'user',value:true})
                    axios.get('api/profile')
                    .then((res) => {
                        commit('SET_AUTH_USER', res.data[0])
                        commit('SET_AUTH_USER_PROFILE', res.data[1])
                        commit('SET_FOLLOWDETAILS',res.data[2])
                        commit('SET_LOADING',{name:'user',value:false})
                    })
                    .catch((error) => {
                        commit('SET_LOADING',{name:'user',value:false})
                    })
                
                
                    
                    
                    
                  },
                  FETCH_SHOW_USER_PROFILE: ({commit}, id) => new Promise((response, reject) => {
                  
                            axios.get('api/profile/'+id)
                        .then((res) => {
                            commit('SET_AUTH_USER', res.data[0])
                            commit('SET_AUTH_USER_PROFILE', res.data[1])
                            commit('SET_AUTH_USER_POSTS', res.data[2].data)
                            commit('SET_FOLLOWDETAILS',res.data[3])
                            commit('SET_LOADING',{name:'user',value:false})
                            response()
                        })
                            .catch((error) => {
                              reject(error)
                              commit('SET_LOADING', { name: 'user', value: false});
                            })
                        }),
              CHANGE_AUTH_USER_PROFILE: ({commit}, thumbnail) => new Promise((response, reject) => {
                  
                axios
                .post("api/profile/updatethumbnail", thumbnail)
                .then((res) => {
                  commit('PUT_PROFILE_PHOTO',res.data.profile)
                  response()
                })
                .catch((error) => {
                  reject(error)
                  commit('SET_LOADING', { name: 'post', value: false});
                })
            }),
            UPDATE_USER_DETAILS:({commit}, userDetails) => new Promise((response, reject) => {
                axios
                .put("api/users/"+userDetails['id'], userDetails)
                .then((res) => {
                    commit('SET_AUTH_USER', res.data)
                  response()
                })
                .catch((error) => {
                  reject(error)
                  commit('SET_LOADING', { name: 'post', value: false});
                })
            }),
        }
    }
