<template>
    <div>
        <Navbar ref="navbar" />
        <v-container>
            <v-main class="">
                <v-row>
                    <v-col cols="6" sm="3">
                        <v-img
                            :src="profile.thumbnail"
                            aspect-ratio="1"
                            :class="imgClass"
                            ><template v-slot:placeholder>
                                <v-row
                                    class="fill-height ma-0"
                                    align="center"
                                    justify="center"
                                >
                                    <v-progress-circular
                                        indeterminate
                                        :color="color"
                                    ></v-progress-circular>
                                </v-row>
                            </template>
                        </v-img>
                    </v-col>
                    <v-col
                        cols="6"
                        sm="9"
                        class="ml-0 pl-0"
                        v-if="userDataLoading"
                    >
                        <UserLoading />
                    </v-col>
                    <v-col cols="6" sm="9" class="ml-0 pl-0" v-else>
                        <v-card-title class="mt-5">
                            {{ user.name }}
                        </v-card-title>
                        <v-card-text>
                            {{ user.bio }}
                        </v-card-text>
                        <v-card-text>
                            {{ followDetails.posts }} posts
                            {{ followDetails.followers }} followers
                            {{ followDetails.following }} following
                        </v-card-text>
                        <div class="ml-2">
                            <v-btn
                                class="ml-3"
                                dark
                                @click="follow()"
                                :color="color"
                            >
                                <span v-if="followDetails.f_o_n == false">
                                    Follow
                                </span>
                                <span v-if="followDetails.f_o_n == true">
                                    Unfollow
                                </span>
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-main>
            <v-main>
                <v-card>
                     <v-toolbar :color="color" dark flat>
                        <v-toolbar-title>{{user.name}} Posts</v-toolbar-title>
                    </v-toolbar>
                    <v-row v-if="postLoading">
                        <v-col cols="6" sm="3" v-for="n in 4" :key="n">
                            <ProfilePostLoading />
                        </v-col>
                    </v-row>
                    <v-row v-else>
                        <v-col
                            v-for="(post, index) in posts"
                            :key="index"
                            cols="6"
                            sm="3"
                        >
                            <v-img
                                :src="post.thumbnail"
                                aspect-ratio="1"
                                :class="imgClass"
                            >
                                <template v-slot:placeholder>
                                    <v-row
                                        class="fill-height ma-0"
                                        align="center"
                                        justify="center"
                                    >
                                        <v-progress-circular
                                            indeterminate
                                            :color="color"
                                        ></v-progress-circular>
                                    </v-row>
                                </template>
                            </v-img>
                            <div class="mt-1">
                                <v-btn icon :color="color" class="ml-1">
                                    <v-icon>mdi-heart</v-icon>
                                </v-btn>

                                <v-btn icon color="grey darken-3" class="ml-1">
                                    <v-icon>mdi-comment-processing</v-icon>
                                </v-btn>
                                <v-btn icon color="grey darken-3" class="ml-1">
                                    <v-icon>mdi-share</v-icon>
                                </v-btn>
                            </div>
                            <v-card-text>
                                <span class="float-right">{{
                                    post.created_at | day
                                }}</span>
                                <div>
                                    {{ post.description }}
                                </div>
                            </v-card-text>
                        </v-col>
                    </v-row>
                    <infinite-loading
                        class="mx-auto"
                        spinner="spiral"
                        @infinite="infiniteHandler"
                    >
                        <div class="text-red" slot="no-more">No more Posts</div>
                        <div class="text-red" slot="no-results">
                            No more Posts
                        </div>
                    </infinite-loading>
                </v-card>
            </v-main>
        </v-container>
    </div>
</template>

<script>
import Navbar from "../Views/Navbar";
import { mapState, mapGetters, mapActions } from "vuex";
import { clipperBasic, clipperPreview, clipperUpload } from "vuejs-clipper";
import { ContentLoader } from "vue-content-loader";
import ProfilePostLoading from "../Loader/ProfilePostLoading";
import InfiniteLoading from "vue-infinite-loading";
import UserLoading from "../Loader/UserLoading";
export default {
    components: {
        Navbar,
        ContentLoader,
        clipperUpload,
        clipperPreview,
        clipperBasic,
        UserLoading,
        InfiniteLoading,
        ProfilePostLoading
    },
    data() {
        return {
            page: 2,
            lastPage: 0,
            userId: "",
            editDialog: false,
            valid: false,
            required: [v => !!v || "Filed is required"],
            ratio: 4 / 3,
            thumbnail: "",
            resultURL: "",
            dialog: false,
            imgClass: "grey rounded lighten-2",
            editDetails: {
                id: "",
                name: "",
                bio: ""
            }
        };
    },
    methods: {
        follow() {
            let id =this.$router.history.current.params.id;
            let url=this.followDetails.f_o_n ? axios.delete('api/follow/'+id) : axios.post("api/follow", { follower_id: id });
            url
                    .then(res => {
                        console.log(this.followDetails.f_o_n);
                       this.$store.dispatch("profile/FETCH_SHOW_USER_PROFILE", id);
                })
                .catch(err => {
                    console.error(err);
                });
        },
        infiniteHandler: function($state) {
            setTimeout(
                function() {
                    let id =this.$router.history.current.params.id;
                 axios.get(`api/profile/${id}?page=${this.page}`)
                    .then(response => {
                        if (response.data[2].data.length > 0) {
                            this.lastPage = response.data[2].last_page;
                            this.$store.commit(
                                "profile/PUT_IFINITE_AUTH_USER_POSTS",
                                response.data[2].data
                            );
                            if (this.page - 1 === this.lastPage) {
                                this.page = 2;
                                $state.complete();
                            } else {
                                this.page += 1;
                            }
                            $state.loaded();
                        } else {
                            this.page = 2;
                            $state.complete();
                        }
                    }).catch(e => console.log(e));
                }.bind(this),
                1000
            );
        },
    },
    computed: {
        
        ...mapState({
            color: "color",
            userDataLoading: state => state.profile.loading.user,
            postLoading: state => state.profile.loading.post,
            
        }),
        ...mapGetters({
            user: "profile/userData",
            profile: "profile/profile",
            posts: "profile/authUserPostsList",
            followDetails: "profile/followDetails",
        })
    },
    created() {
        let id = this.$router.history.current.params.id;
            if(this.$store.state.userData.id==id){
                this.$router.push('/profile')
            }
        this.$store.dispatch("profile/FETCH_SHOW_USER_PROFILE", id)
        .then(()=>{})
        .catch(err=>{
            if(err.response.status==404){
                this.$router.push('/post')
            }
            
        })
        if (localStorage.getItem("theme") == "false") {
            this.$vuetify.theme.dark = false;
        }
        if (localStorage.getItem("theme") == "true") {
            this.$vuetify.theme.dark = true;
            this.imgClass = "grey darken-4 rounded lighten-2";
        }
    }
};
</script>

<style></style>
