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
                        <v-btn
                            icon
                            @click="dialogOpen(profile)"
                            style="position: absolute;top: 14px;"
                        >
                            <v-icon :color="color">mdi-camera</v-icon>
                        </v-btn>

                        <v-dialog v-model="dialog" max-width="500px">
                            <v-card>
                                <v-card-title>
                                    <span class="headline"
                                        >Upload New Photo</span
                                    >
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="12">
                                                <clipper-upload
                                                    v-model="thumbnail"
                                                >
                                                    <v-btn icon>
                                                        <v-icon :color="color"
                                                            >mdi-camera</v-icon
                                                        >
                                                    </v-btn>
                                                </clipper-upload>
                                                <clipper-basic
                                                    class="my-clipper"
                                                    ref="clipper"
                                                    :src="thumbnail"
                                                >
                                                </clipper-basic>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn @click="close" :color="color" text
                                        >Cancel</v-btn
                                    >
                                    <v-btn
                                        :disabled="!thumbnail"
                                        dark
                                        :color="color"
                                        @click="getResult"
                                        >Save</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
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
                                icon
                                @click="openEditDialog(user)"
                            >
                                <v-icon :color="color">mdi-settings</v-icon>
                            </v-btn>
                        </div>
                        <v-dialog v-model="editDialog" max-width="500px">
                            <v-card>
                                <v-card-title>
                                    <span class="headline">Edit Details</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="12">
                                                <v-form
                                                    ref="form"
                                                    v-model="valid"
                                                >
                                                    <v-text-field
                                                        v-model="
                                                            editDetails.name
                                                        "
                                                        :rules="required"
                                                        label="User Name"
                                                        :color="color"
                                                    ></v-text-field>
                                                    <v-text-field
                                                        v-model="
                                                            editDetails.bio
                                                        "
                                                        :rules="required"
                                                        label="Bio"
                                                        :color="color"
                                                    ></v-text-field>
                                                </v-form>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        :color="color"
                                        text
                                        @click="closeEditDialog"
                                        >Cancel</v-btn
                                    >
                                    <v-btn
                                        :color="color"
                                        text
                                        :disabled="!valid"
                                        @click="saveEditDialogDetails"
                                        >Save</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-col>
                </v-row>
            </v-main>
            <v-main>
                <v-card>
                    <v-toolbar :color="color" dark flat>
            <v-toolbar-title>Your Posts</v-toolbar-title>
          </v-toolbar>
                     <v-row v-if="postsLoading">
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
                                <div
                                    style="position:absolute;top:2px;right:2px"
                                >
                                    <v-icon
                                        small
                                        :color="color"
                                        @click="editPost(post)"
                                        >mdi-pencil</v-icon
                                    >
                                    <v-icon
                                        small
                                        :color="color"
                                        @click="deletepost(post)"
                                        >mdi-delete</v-icon
                                    >
                                </div>
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
        infiniteHandler: function($state) {
            setTimeout(
                function() { 
                    axios.get(`api/profileposts?page=${this.page}`)
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
                    }).catch(e => console.log(e));
                }.bind(this),
                1000
            );
        },
        editPost(post) {
            let index = this.posts.indexOf(post);
            this.$refs.navbar.editPostFromParent(post, index);
        },
        deletepost(post) {
            let postIndex = this.posts.indexOf(post);
            this.$store.dispatch("post/DELETE_POST", {
                postIndex: postIndex,
                postId: post.id
            });
        },
        dialogOpen(profile) {
            this.thumbnail = profile.thumbnail;
            this.dialog = true;
        },
        openEditDialog(user) {
            this.editDialog = true;
            this.editDetails.id = user.id;
            this.editDetails.name = user.name;
            this.editDetails.bio = user.bio;
        },
        closeEditDialog() {
            this.editDialog = false;
        },
        saveEditDialogDetails() {
            const userDetails = {
                id: this.editDetails.id,
                name: this.editDetails.name,
                bio: this.editDetails.bio
            };
            this.$store
                .dispatch("profile/UPDATE_USER_DETAILS", userDetails)
                .then(() => {
                    this.closeEditDialog();
                });
        },
        getResult: function() {
            const canvas = this.$refs.clipper.clip();
            this.resultURL = canvas.toDataURL("image/jpeg", 1);
            this.$store
                .dispatch("profile/CHANGE_AUTH_USER_PROFILE", {
                    thumbnail: this.resultURL
                })
                .then(() => {
                    this.close();
                    this.thumbnail = "";
                    this.resultURL = "";
                });
        },
        close() {
            this.dialog = false;
            this.thumbnail = "";
            this.resultURL = "";
        }
    },
    computed: {
        ...mapState({
            color: "color",
            userDataLoading: state => state.profile.loading.user,
            postsLoading:state=>state.post.loadingPost,
        }),
        ...mapGetters({
            user: "profile/userData",
            profile: "profile/profile",
            posts: "post/postsList",
            followDetails: "profile/followDetails"
        })
    },
    created() {
        this.$store.dispatch("profile/FETCH_USER_PROFILE");
        this.$store.dispatch("post/GET_POSTS_FOR_PROFILE");
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
