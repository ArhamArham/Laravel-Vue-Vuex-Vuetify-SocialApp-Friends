<template>
  <v-app id>
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-progress-linear
              :active="loading"
              :indeterminate="loading"
              :color='color'
              position="absolute"
            ></v-progress-linear>
            <v-card class="elevation-12">
              <v-toolbar :color='color' dark flat>
                <v-toolbar-title>Signup form</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <v-card-text>
                <v-form v-model="valid">
                  <v-text-field
                    v-model="signupData.name"
                    :color='color'
                    label="Name"
                    prepend-icon="mdi-account-outline"
                    type="text"
                    :rules="[rules.required,rules.min]"
                  ></v-text-field>
                  <v-text-field
                    v-model="signupData.email"
                    :color='color'
                    label="Email"
                    :success-messages="email_success"
                    :error-messages="email_error"
                    @blur="checkEmail"
                    prepend-icon="mdi-account-outline"
                    type="text"
                    :rules="[rules.required,rules.validEmail]"
                  ></v-text-field>

                  <v-text-field
                    v-model="signupData.password"
                    :color='color'
                    id="password"
                    label="Password"
                    name="password"
                    prepend-icon="mdi-lock-outline"
                    :rules="[rules.required,rules.min]"
                    type="password"
                  ></v-text-field>
                  <v-text-field
                    v-model="signupData.retypePassword"
                    :color='color'
                    id="password"
                    label="Retype Password"
                    name="password"
                    prepend-icon="mdi-lock-outline"
                    :rules="[rules.required,passwordMatch]"
                    type="password"
                    v-on:keyup.enter="onEnter"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer />
                 <v-btn
                  tabindex="4"
                  :color="color"
                  @click="login"
                  dark
                >login</v-btn>
                <v-btn
                  :disabled="!valid"
                  :dark="valid"
                  tabindex="3"
                  :color='color'
                  @click="signup"
                >SignUp</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
    <div class="mx-auto">
      <p >Copyrights © <strong >abrsoftwaretechnologies@gmail.com </strong>All Rights Reserved</p>
    </div>
  </v-app>
</template>

<script>
import {mapState} from 'vuex'
export default {
  props: {
    source: String
  },
  data() {
    return {
      loading: false,
      email_success: "",
      email_error: "",
      valid: true,
      snackbar: false,
      rules: {
        required: v => !!v || "This field is required",
        min: v => v.length >= 5 || "Minimun 5 characters required",
        validEmail: v => /.+@.+\..+/.test(v) || "E-mail must be valid"
      },
      signupData: {
        name: "",
        email: "",
        password: "",
        retypePassword: ""
      }
    };
  },
  computed: {
    ...mapState(['color']),
    passwordMatch() {
      return this.signupData.password != this.signupData.retypePassword
        ? "Password does not match"
        : true;
    }
  },
  created () {
    if (localStorage.getItem("theme") == "false") {
      this.$vuetify.theme.dark = false;
    }
    if (localStorage.getItem("theme") == "true") {
      this.$vuetify.theme.dark = true;
    }
  },
  methods: {
    onEnter: function() {
      this.signup();
    },
    login(){
      this.$router.push('/login');
    },
    signup() {
      this.$store.dispatch('user/signup',this.signupData)
    .then(res=>{
      this.$router.push("/post")
    })
    },
    checkEmail() {
      if (/.+@.+\..+/.test(this.signupData.email)) {
        axios
          .post("api/email/validate", {
            email: this.signupData.email,
            id: this.signupData.id
          })
          .then(res => {
            console.log(res.data.message);
            this.email_success = res.data.message;
            this.email_error = "";
          })
          .catch(err => {
            this.email_success = "";
            this.email_error = "Email already taken";
          });
      }
    }
  }
};
</script>

<style>
</style>