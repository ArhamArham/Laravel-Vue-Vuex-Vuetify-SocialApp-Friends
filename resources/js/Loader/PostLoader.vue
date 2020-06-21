<template>
  <ContentLoader
    :height=580
    :width=600
    :speed=3
    :primaryColor="pC"
    :secondaryColor="sC"
    >
    <rect x="0" y="0" rx="10" ry="10" width="100%" height="320" />
    <rect x="20" y="340" rx="10" ry="10" width="92%" height="30" />
    <rect x="20" y="380" rx="10" ry="10" width="92%" height="30" />
    <rect x="20" y="420" rx="10" ry="10" width="92%" height="30" />
    <rect x="20" y="460" rx="10" ry="10" width="92%" height="30" />
    <rect x="20" y="500" rx="10" ry="10" width="92%" height="30" />
    <rect x="20" y="540" rx="10" ry="10" width="92%" height="30" />
    </ContentLoader>
</template>

<script>
import { ContentLoader } from 'vue-content-loader'
export default {
    components: {
        ContentLoader
    },
    data () {
        return {
            pC:'#f3f3f3',
            sC:'#ecebeb',
        }
    },
    created() {
        if (localStorage.getItem("theme") == "false") {
      this.$vuetify.theme.dark = false;
    }
    if (localStorage.getItem("theme") == "true") {
      this.$vuetify.theme.dark = true;
      this.pC='#121212'
      this.sC='#1E1E1E'
    }
    },
}
</script>

<style>

</style>