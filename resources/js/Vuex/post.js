export default {
    namespaced: true,
    state: {
        posts:[],
        loadingPost:false,
    },
    getters: {
        postsList: state => state.posts,
    },
    mutations: {
        // SCREAMS/POST
    SET_POSTS: (state, data) => {
        state.posts = data
    },
    SET_LOADING_POST:(state,data)=>{
        state.loadingPost=data
    },
    PUT_IFINITE_POSTS:(state,data)=>{
        data.forEach(post => {
            state.posts.push(post);
        });
    },
    SET_POST: (state, data) => {
        state.posts.splice(0, 0,data.post)
    },
    PUT_POST:(state,{data,postIndex})=>{
        Object.assign(state.posts[postIndex],data.post);
    },
    REMOVE_POST:(state,{postIndex})=>{
        state.posts.splice(postIndex,1)
    },
    SET_COMMENT:(state,{data,index})=>{
        state.posts[index].comments.splice(state.posts[index].comments_to_show,0,data)
        state.posts[index].comments_to_show+=1  
    },
    PUT_COMMENT:(state,{data,postIndex,commentIndex})=>{
        state.posts[postIndex].comments[commentIndex].edit=0
        state.posts[postIndex].comments[commentIndex].comment=data.comment
    },
    REMOVE_COMMENT:(state,{postIndex,commentIndex})=>{
        state.posts[postIndex].comments.splice([commentIndex], 1);
    },
    SET_REPLY:(state,{data,postIndex,commentIndex})=>{
        state.posts[postIndex].comments[commentIndex].replies.splice(state.posts[postIndex].comments[commentIndex].replies_to_show,0,data)
        state.posts[postIndex].comments[commentIndex].replies_to_show+=1  
    },
    PUT_REPLY:(state,{data,postIndex,commentIndex,replyIndex})=>{
        state.posts[postIndex].comments[commentIndex].replies[replyIndex].edit=0
        state.posts[postIndex].comments[commentIndex].replies[replyIndex].reply=data.reply
    },
    REMOVE_REPLY:(state,{postIndex,commentIndex,replyIndex})=>{
        state.posts[postIndex].comments[commentIndex].replies.splice([replyIndex], 1);
    }
    },
    actions: {
        // GET POST IN STATE
    GET_POSTS: async ({commit, dispatch}) => {
        commit('SET_LOADING_POST',true);
        const response = await axios.get('api/posts');
        const data = response.data.posts.data;
        commit('SET_LOADING_POST',false);
        commit('SET_POSTS', data)
    },
    GET_POSTS_FOR_PROFILE: async ({commit, dispatch}) => {
        commit('SET_LOADING_POST',true);
        const response = await axios.get('api/profileposts/');
        const data = response.data.posts.data;
        commit('SET_LOADING_POST',false);
        commit('SET_POSTS', data)
    },
    SUBMIT_POST:({commit},{description,thumbnail})=>new Promise((response,reject)=>{
        axios.post("api/posts", {description,thumbnail})
                    .then((res) => {
                        commit('SET_POST', res.data)
                        response()
                    })
                    .catch(error => {
                        reject(error)
                        commit('SET_LOADING', { name: 'form', value: false});
                    });
    }),
    UPDATE_POST:({commit},post)=>new Promise((response,reject)=>{
        axios.put("api/posts/" + post['postId'], {
                        description: post['description'],
                        thumbnail: post['image']
                    })
                    .then((res) => {
                        commit('PUT_POST', {data:res.data,postIndex:post['postIndex']})
                        response()
                    })
                    .catch(error => {
                        reject(error)
                        commit('SET_LOADING', { name: 'form', value: false});
                    });
    }),
    DELETE_POST: ({commit}, post) => new Promise((response, reject) => {
        axios.delete('api/posts/'+post['postId'])
        .then(() => {
            commit('REMOVE_POST', {postIndex:post['postIndex']})
            response()
        })
        .catch((error) => {
          reject(error)
          commit('SET_LOADING', { name: 'form', value: false});
        })
    }),
    POST_COMMENT: ({commit}, comment) => new Promise((response, reject) => {
        axios.post('api/post/comment',comment)
        .then((res) => {
          commit('SET_COMMENT', {data:res.data,index:comment['postIndex']})
          response()
        })
        .catch((error) => {
          reject(error)
          commit('SET_LOADING', { name: 'post', value: false});
        })
    }),
    UPDATE_COMMENT: ({commit}, comment) => new Promise((response, reject) => {
        axios.put('api/comment/'+comment['commentId'],comment)
        .then((res) => {
            commit('PUT_COMMENT', {data:res.data,postIndex:comment['postIndex'],commentIndex:comment['commentIndex']})
            response()
        })
        .catch((error) => {
          reject(error)
          commit('SET_LOADING', { name: 'form', value: false});
        })
    }),
    DELETE_COMMENT: ({commit}, comment) => new Promise((response, reject) => {
        axios.delete('api/comment/'+comment['commentId'])
        .then(() => {
            commit('REMOVE_COMMENT', {postIndex:comment['postIndex'],commentIndex:comment['commentIndex']})
            response()
        })
        .catch((error) => {
          reject(error)
          commit('SET_LOADING', { name: 'form', value: false});
        })
    }),
    POST_REPLY: ({commit}, reply) => new Promise((response, reject) => {
        axios.post('api/post/reply',reply)
        .then((res) => {
          commit('SET_REPLY', {data:res.data,postIndex:reply['postIndex'],commentIndex:reply['commentIndex']})
          response()
        })
        .catch((error) => {
          reject(error)
          commit('SET_LOADING', { name: 'form', value: false});
        })
    }),
    UPDATE_REPLY: ({commit}, reply) => new Promise((response, reject) => {
        axios.put('api/reply/'+reply['replyId'],reply)
        .then((res) => {
            commit('PUT_REPLY', {data:res.data,postIndex:reply['postIndex'],commentIndex:reply['commentIndex'],replyIndex:reply['replyIndex']})
            response()
        })
        .catch((error) => {
          reject(error)
          commit('SET_LOADING', { name: 'form', value: false});
        })
    }),
    DELETE_REPLY: ({commit}, reply) => new Promise((response, reject) => {
        axios.delete('api/reply/'+reply['replyId'])
        .then(() => {
            commit('REMOVE_REPLY', {postIndex:reply['postIndex'],commentIndex:reply['commentIndex'],replyIndex:reply['replyIndex']})
            response()
        })
        .catch((error) => {
          reject(error)
          commit('SET_LOADING', { name: 'form', value: false});
        })
    }),
    }
}