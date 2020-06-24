<template>
  <v-app id="inspire">
    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-progress-linear
              :active="loadingForm"
              :indeterminate="loadingForm"
              :color="color"
              position="absolute"
            ></v-progress-linear>
            <v-card class="elevation-12">
              <v-toolbar :color="color" dark flat>
                <v-toolbar-title>Login form</v-toolbar-title>
                <v-spacer />
              </v-toolbar>
              <v-card-text>
                <v-form v-model="valid">
                  <v-text-field
                    :color="color"
                    label="E-mail"
                    v-model="loginData.email"
                    name="login"
                    tabindex="1"
                    :rules="emailRules"
                    required
                    prepend-icon="mdi-account-outline"
                    type="text"
                  />

                  <v-text-field
                    :color="color"
                    id="password"
                    label="Password"
                    v-model="loginData.password"
                    :rules="passwordRules"
                    tabindex="2"
                    name="password"
                    prepend-icon="mdi-lock-outline"
                    type="password"
                    required
                    v-on:keyup.enter="onEnter"
                  />
                </v-form>
              </v-card-text>

              <v-card-actions>
                <v-spacer />
                  <v-btn
                    tabindex="4"
                    :color="color"
                    @click="signup"
                    dark
                  >Signup</v-btn>
                <v-btn
                :disabled="!valid"
                  :dark='valid'
                  tabindex="3"
                  :color="color"
                  @click="login"
                >Login</v-btn>
              </v-card-actions>
            </v-card>
           
            <v-snackbar :value="snackbarTrue">
               <div v-if="errors">
            <span v-for="(errorMessage, i) in errors" :key="i">{{errorMessage}}</span>
              <v-btn color="pink" text @click="snackbarTrue = false">Close</v-btn>
            </div>
            </v-snackbar>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
    <div class="mx-auto">
      <p >Copyrights © <strong >abrsoftwaretechnologies@gmail.com </strong>All Rights Reserved</p>
    </div>
  </v-app>
</template>

<script>
import {mapGetters,mapState} from 'vuex'
export default {
  data() {
    return {
      valid: true,
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+\..+/.test(v) || "E-mail must be valid"
      ],

      passwordRules: [v => !!v || "Password is required"],

      loginData: {
        email: "",
        password: "",
        loading: false,
        text: ""
      }
    };
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
    onEnter: function() {
      this.login();
    },
    signup(){
      this.$router.push('/signup');
    },
    login() {
    this.$store.dispatch('user/login',this.loginData)
    .then(res=>{
      this.$router.push("/post")
    })
    }
  },
  computed: {
        ...mapState(['color']),
        ...mapGetters({
          errors:'user/errors', 
          loadingForm:'user/loadingForm',
          snackbarTrue:'user/snackbarTrue'})
    },
  props: {
    source: String
  }
};
</script>

<style>
</style>