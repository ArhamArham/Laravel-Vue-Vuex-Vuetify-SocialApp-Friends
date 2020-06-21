<template>
    <v-app-bar app class="primario" elevation="0" :color="color">
        <!--------------------------- NAVBAR TITLE ---------------------------->
        <v-toolbar-title class="headline text-uppercase hover">
            <router-link class="app_name" link to="/">
                <span>FRIENDS</span>
            </router-link>
        </v-toolbar-title>
        <div class="search">
            <v-text-field
                dark
                color="white"
                placeholder="Search Here"
                v-model.lazy="searchVal"
                @input="search"
            />
        </div>
            <v-dialog v-model="searchBox" max-width="500px">

            <v-card >
                <v-container v-if="searchData.length > 0">
                <v-row v-for="(search, index) in searchData" :key="index">
                    <div class="ml-5">
                        <v-img
                            width="50"
                            height="50"
                            :src="search.searchable.profile.thumbnail"
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
                    </div>
                    <div class="">
                        <v-card-text @click="redirectProfile(search.url)">{{ search.title }}</v-card-text>
                    </div>
                </v-row>
                </v-container>
                <v-container v-else><v-row class="ml-3">No User Found</v-row></v-container>
            </v-card>
            </v-dialog>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on }">
                <!--<v-btn color="primary" dark class="mb-2 ml-4" @click="deleteAll">Delete</v-btn>-->
                <v-btn icon v-on="on">
                    <v-icon color="white">mdi-plus-box</v-icon>
                </v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="12">
                                <v-form ref="form" v-model="valid">
                                    <v-text-field
                                        :color="color"
                                        v-model="editedItem.description"
                                        label="What's on your mind?"
                                        :rules="desRule"
                                    ></v-text-field>
                                    <clipper-upload
                                        v-model="editedItem.thumbnail"
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
                                        :src="editedItem.thumbnail"
                                    >
                                    </clipper-basic>
                                </v-form>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :color="color" text @click="close">Cancel</v-btn>
                    <v-btn :color="color" text @click="save" :disabled="!valid"
                        >Save</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>
        <div>
            <v-menu
                v-model="menu"
                :close-on-content-click="false"
                :nudge-width="200"
                offset-x
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-avatar v-bind="attrs" v-on="on">
                        <v-icon dark>mdi-account-circle</v-icon>
                    </v-avatar>
                </template>

                <v-card>
                    <v-list>
                        <v-list-item>
                            <v-list-item-avatar>
                                <img
                                    :src="userData.profile.thumbnail"
                                    alt="John"
                                />
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-title
                                    >{{userData.name}}</v-list-item-title
                                >
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>

                    <v-divider></v-divider>

                    <v-list>
                        <v-list-item>
                            <v-btn icon :color="color" @click="home">
                                <v-icon>mdi-home</v-icon>
                            </v-btn>
                            <v-list-item-title @click="home" class="hover ml-3"
                                >Home</v-list-item-title
                            >
                        </v-list-item>
                        <v-list-item>
                            <v-btn icon :color="color" @click="profile">
                                <v-icon>mdi-account</v-icon>
                            </v-btn>
                            <v-list-item-title @click="profile" class="hover ml-3"
                                >Profile</v-list-item-title
                            >
                        </v-list-item>
                        <v-list-item>
                            <v-btn @click="settings" icon :color="color">
                                <v-icon>mdi-settings</v-icon>
                            </v-btn>
                            <v-list-item-title @click="settings" class="hover ml-3"
                                >Settings</v-list-item-title
                            >
                        </v-list-item>
                        <v-list-item>
                            <v-btn @click="logout" icon :color="color">
                                <v-icon>mdi-power</v-icon>
                            </v-btn>
                            <v-list-item-title @click="logout" class="hover ml-3"
                                >Logout</v-list-item-title
                            >
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-menu>
        </div>
        <!--------------------------- END NAVBAR TITLE ---------------------------->
    </v-app-bar>
</template>

<script>
import { mapState } from "vuex";
import VueAvatar from "vue-avatar-editor/src/components/VueAvatar";
import VueAvatarScale from "vue-avatar-editor/src/components/VueAvatarScale";
import { clipperBasic, clipperPreview, clipperUpload } from "vuejs-clipper";
export default {
    props: ["post"],
    components: {
        clipperUpload,
        clipperBasic,
        clipperPreview
    },
    data: () => ({
        desRule: [v => !!v || "Description is required"],
        searchVal: "",
        searchBox:false,
        dialog: false,
        fav: true,
        menu: false,
        message: false,
        hints: true,
        valid: false,
        searchData: [],
        editedIndex: -1,
        defaultItem: {
            description: "",
            thumbnail: "",
            resultURL: ""
        },
        editedItem: {
            description: "",
            thumbnail: "",
            resultURL: ""
        }
    }),
    computed: {
        ...mapState(["color",'userData']),
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
        if (localStorage.getItem("theme") == "false") {
            this.$vuetify.theme.dark = false;
        }
        if (localStorage.getItem("theme") == "true") {
            this.$vuetify.theme.dark = true;
        }
    },
    methods: {
        search() {
            if (this.searchVal.length <= 3) {
                this.searchData = [];
            } else {
                axios
                    .post("api/search-results", {
                        query: this.searchVal
                    })
                    .then(response => {
                        this.searchBox=true
                        this.searchData = response.data;
                    })
                    .catch(error => {
                        this.searchBox=false
                    });
                this.searchData = [];
            }
        },
        redirectProfile(id){
            this.$router.push("/profile/"+id).catch(() => {});
            this.searchBox=false
        },
        openSearchBox(){
            this.searchBox=true
        },
        closeSearchBox(){
            this.searchBox=false
        },
        home(){
            this.$router.push('/').catch(() => {})
        },
        profile() {
            this.$router.push("/profile").catch(() => {});
        },
        settings() {
            this.$router.push("/settings/user").catch(() => {});
        },
        logout() {
            this.$store.dispatch("user/LOGOUT_USER").then(() => {
                this.$router.push("/login").catch(() => {});
            });
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        },
        editPostFromParent(post, index) {
            this.editedIndex = index;
            this.editedItem = Object.assign({}, post);
            this.dialog = true;
        },
        close() {
            this.dialog = false;
            this.resetValidation();
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },
        getResult: function() {
            const canvas = this.$refs.clipper.clip();
            this.resultURL = canvas.toDataURL("image/jpeg", 1);
            this.$store
                .dispatch("post/SUBMIT_POST", {
                    description: this.resultURL,
                    image: i
                })
                .then(() => {
                    this.close();
                    this.thumbnail = "";
                    this.resultURL = "";
                });
        },
        save() {
            if (this.editedIndex > -1) {
                const canvas = this.$refs.clipper.clip();
                this.resultURL = canvas.toDataURL("image/jpeg", 1);
                const post = {
                    postIndex: this.editedIndex,
                    postId: this.editedItem.id,
                    image: this.resultURL,
                    description: this.editedItem.description
                };
                this.$store.dispatch("post/UPDATE_POST", post).then(() => {
                    this.close();
                    this.editedItem.thumbnail = "";
                    this.resultURL = "";
                });
            } else {
                const canvas = this.$refs.clipper.clip();
                this.resultURL = canvas.toDataURL("image/jpeg", 1);
                this.$store
                    .dispatch("post/SUBMIT_POST", {
                        description: this.editedItem.description,
                        thumbnail: this.resultURL
                    })
                    .then(() => {
                        this.close();
                        this.editedItem.thumbnail = "";
                        this.resultURL = "";
                    });
            }
        }
    }
};
</script>