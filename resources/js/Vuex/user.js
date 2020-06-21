export default {
    namespaced: true,
    state: {
        loading: {
            user: false,
            form: false,
            ui: false,
            post:false,
        },
        snackbar: false,
        error: null,
        path: '',
        token: localStorage.getItem('token') || '',
    },
    getters: {
        errors: state => state.error,
    loadingUI: state => state.loading.ui,
    loadingForm: state => state.loading.form,
    loadingPost: state => state.loading.post,
    snackbarTrue: state => state.snackbar,
    isAuthenticated: state => !!state.token,
    },
    mutations: {
        SET_AUTHORIZATION: (state, token) => {
            state.token = token
        },
        
        SET_USER_UNAUTHENTICATED: (state, emptyData) => {
            state.authUser = emptyData;
            state.token = '';
        },
        SET_LOADING: (state, { name, value }) => state.loading[name] = value,
        SET_ERROR: (state, error) => state.error = error,
        SET_SNACKBAR: (state) => state.snackbar = true,
        SET_LAND: (state, pathName) => state.path = pathName,
        SET_CLEAR_ERROR: (state) => state.error = '',
    },
    actions: {
        login: ({ dispatch, commit }, loginData) => new Promise((resolve, reject) => {
            commit('SET_LOADING', { name: 'form', value: true });
            axios.post('api/login', loginData)
                .then((res) => {
                    const token = `Bearer ${res.data.token}`
                    dispatch('AUTH_SUCCESS', token);
                    commit('SET_LOADING', { name: 'form', value: false });
                    resolve();
                })
                .catch((error) => {
                    commit('SET_SNACKBAR')
                    commit('SET_ERROR', error.response.data);
                    commit('SET_LOADING', { name: 'form', value: false });
                })
        }),
        signup: ({ dispatch, commit }, signupData) => new Promise((resolve, reject) => {
            commit('SET_LOADING', { name: 'form', value: true });
            axios.post('api/signup', signupData)
                .then((res) => {
                    const token = `Bearer ${res.data.token}`
                    dispatch('AUTH_SUCCESS', token);
                    commit('SET_LOADING', { name: 'form', value: false });
                    resolve();
                })
                .catch((error) => {
                    commit('SET_SNACKBAR')
                    commit('SET_ERROR', error.response.data);
                    commit('SET_LOADING', { name: 'form', value: false });
                })
        }),
        LOGOUT_USER: ({ commit }) => new Promise((response) => {
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
            commit('SET_USER_UNAUTHENTICATED', {})
            response()
        }),
        AUTH_SUCCESS: ({ commit }, token) => {
            localStorage.setItem('token', token)
            axios.defaults.headers.common['Authorization'] = token
            commit('SET_AUTHORIZATION', token);
        },
        AUTH_USER: ({ commit }, token) => { commit('SET_AUTHORIZATION', token) },
        CLEAR_ERROR: ({ commit }) => commit('SET_CLEAR_ERROR'),
    }
}