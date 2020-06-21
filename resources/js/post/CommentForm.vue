<template>
  <v-container class="mt-3 pt-0 mb-0 pb-0">
                <v-text-field
                    :color="color"
                    v-on:keyup.enter='postComment'
                    v-model="commentVal"
                    class="mt-0 pt-0"
                    label="Comment"
                />
            </v-container>
</template>

<script>
import { mapState } from 'vuex'
export default {
    props:['post','posts'],
    data: ()=>({
        commentVal:'',
    }),
    computed: {
        ...mapState(["color"]),
    },
    methods:{
        postComment(){
            let postIndex =this.posts.indexOf(this.post)
            const comment={
                postIndex:postIndex,
                postId:this.post.id,
                comment : this.commentVal,
            }
            this.$store.dispatch('post/POST_COMMENT',comment)
            .then(()=>{
                this.commentVal=''
            })
        },
    }
}
</script>

<style>

</style>