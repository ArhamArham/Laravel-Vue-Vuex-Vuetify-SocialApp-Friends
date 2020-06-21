<template>
    <ContentLoader
        :height=500
        :speed=2
        :primaryColor="pC"
        :secondaryColor="sC"
    >
        <rect x="0" y="0" rx="10" ry="10" width="100%" height="400" />
        <rect x="0" y="420" rx="10" ry="10" width="100%" height="80" />
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