<template>
  <div>
      <Navbar />
      <div>
        <router-view></router-view>
      </div>
  </div>
</template>

<script>
import Navbar from '../Views/Navbar'
import { mapState } from 'vuex'
export default {
    components:{
        Navbar
    },
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
  mounted() {
    if (localStorage.getItem("theme") == "false") {
      this.theme = false;
    }
    if (localStorage.getItem("theme") == "true") {
      this.theme = true;}
  },
}
</script>

<style>

</style>