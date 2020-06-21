<template>
  <div>
   <v-container fluid class="mt-16">
      <v-btn :color="color" dark @click="pushuser">Goto User Setting</v-btn>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="5">
        <v-card  class="elevation-12">
          <v-toolbar :color="color" dark flat>
            <v-toolbar-title>User Settings</v-toolbar-title>
            <v-spacer />
          </v-toolbar>
        <v-card-text class="mt-0 pt-0">
          <v-col cols="12" sm="4" md="6" class="mt-0 pt-0">
              <v-switch :color="color" v-model="theme" class="mx-2" label="Dark Theme"></v-switch>
            <v-radio-group  v-model="color" column class="mt-0 pt-0">
                <div v-for="(c,index) in colors" :key="index">   
              <v-radio
              class="m-2 p-1"
                :label="c"
                :color="c"
                :value="c"
              ></v-radio>
                </div>
            </v-radio-group>
          </v-col>
    </v-card-text>
        </v-card> 
        </v-col>
    </v-row>
  </v-container>
      </div>
</template>

<script>
import {mapState} from 'vuex'
export default {
data () {
        return {
            theme:false,
            colors:[
                'primary',
                'secondary',
                'warning',
                'success',
                'indigo',
                'purple',
                'pink',
                'red',
                'light-blue',
                'cyan',
                'teal',
                'lime',
                'orange',
                'amber',
                'yellow',
                'deep-orange',
                'brown',
                'blue-grey',
                'grey'
            ],
            color:localStorage.getItem('color') ||'info'
        }
    },
    computed:{
        
    },
    watch:{
        theme: function(old, newval) {
      localStorage.setItem("theme", old);
      this.$vuetify.theme.dark = old;
    },
        color:function(){
            localStorage.setItem('color',this.color)
            this.$store.state.color=this.color

        }
    },
    created() {
    this.$vuetify.theme.dark = false;
  },
  methods: {
    pushuser(){
        this.$router.push('/settings/user')
    }  
  },
  mounted() {
    if (localStorage.getItem("theme") == "false") {
      this.theme = false;
    }
    if (localStorage.getItem("theme") == "true") {
      this.theme = true;}
  },
}
</script>