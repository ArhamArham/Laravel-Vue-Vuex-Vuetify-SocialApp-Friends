<template>
    <div>
        <Navbar ref="navbar" />
        <v-divider class="mt-7"></v-divider>
        <div v-if="loadingPost">
            <v-card
                v-for="n in 4"
                :key="n"
                class="mx-auto my-12"
                max-width="600"
            >
                <PostLoader />
            </v-card>
        </div>
        <div v-else>
            <v-card
                v-for="(post, index) in postsList"
                :key="index"
                class="mx-auto my-12"
                max-width="600"
            >
                <div >
                    <v-img
                        :class="imgClass"
                        :src="post.thumbnail"
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
                    <div v-if="post.user_id == userData.id" style="position:absolute;top:2px;right:2px">
                        <v-icon small :color="color" @click="editPost(post)"
                            >mdi-pencil</v-icon
                        >
                    </div>
                </div>
                <div class="mt-1">
                    <v-btn icon :color="color" class="ml-1">
                        <v-icon>mdi-heart</v-icon>
                    </v-btn>

                    <v-btn icon @click="commentFocus" color="grey darken-3" class="ml-1">
                        <v-icon>mdi-comment-processing</v-icon>
                    </v-btn>
                    <v-btn icon color="grey darken-3" class="ml-1">
                        <v-icon>mdi-share</v-icon>
                    </v-btn>
                </div>
                <v-card-title @click="profile(post.user.id)">{{ post.user.name }}</v-card-title>
                <v-card-text>
                    <span class="float-right">{{ post.created_at | day }}</span>
                    <div>
                        {{ post.description }}
                    </div>
                </v-card-text>

                <v-divider class="mx-4"></v-divider>
                <CommentModel :posts="postsList" :post="post" />
                <v-container class="mt-3 pt-0 mb-0 pb-0">
                <Comment ref="comment" :posts="postsList" :post="post" />
                </v-container>
            </v-card>
            <infinite-loading class="mt-16" spinner="spiral" ref="vueinfinite" @infinite="infiniteHandler">
                <div class="text-red" slot="no-more">No more Posts</div>
                <div class="text-red" slot="no-results">No more Posts</div>
            </infinite-loading>
        </div>
        
    </div>
</template>

<script>
import Navbar from "../Views/Navbar";
import Comment from "../post/CommentForm";
import CommentModel from "../post/CommentsModel";
import PostLoader from "../Loader/PostLoader";
import InfiniteLoading from "vue-infinite-loading";
import { mapState, mapGetters } from "vuex";
export default {
    components: {
        PostLoader,
        Comment,
        CommentModel,
        Navbar,
        InfiniteLoading
    },
    data: () => ({
        imgClass:'grey rounded lighten-2',
        page: 2,
        lastPage: 0,
        theme: false,
    }),
    computed: {
        ...mapState({
            color:'color',
            posts: state => state.post.posts,
            userData:'userData',
            loadingPost:state=>state.post.loadingPost
        }),
        ...mapGetters({
            postsList: "post/postsList"
        }),
        formTitle() {
            return this.editedIndex === -1 ? "New Post" : "Edit Post";
        }
    },
    watch: {
        dialog(val) {
            val || this.close();
        }
        
    },
    created() {
        console.log(this.userData);
        
        this.$store.dispatch("post/GET_POSTS");
        if (localStorage.getItem("theme") == "false") {
      this.$vuetify.theme.dark = false;
    }
    if (localStorage.getItem("theme") == "true") {
      this.$vuetify.theme.dark = true;
      this.imgClass='grey darken-4 rounded lighten-2'
    }
    },
    methods: {
        infiniteHandler: function($state) {
            
            setTimeout(
                function() {
                    axios
                        .get(`api/posts?page=${this.page}`)
                        .then(response => {
                            if (response.data.posts.data.length > 0) {
                                this.lastPage = response.data.last_page;
                                this.$store.commit(
                                    "post/PUT_IFINITE_POSTS",
                                    response.data.posts.data
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
                        })
                        .catch(e => console.log(e));
                }.bind(this),
                1000
            );
        },
        profile(id){
            this.$router.push('/profile/'+id)
        },
        commentFocus(){
            this.$refs.comment.$el.focus()
        },
        editPost(post) {
            let index = this.posts.indexOf(post);
            this.$refs.navbar.editPostFromParent(post, index);
        },
    }
};
</script>
