<template>
  <v-container class="mt-1 pt-0 mb-0 pb-0">
                    <v-text-field
                    v-on:keyup.enter='postReply'
                    v-model="replyVal"
                        class="mt-0 pt-0 pl-12"
                        label="Reply"
                        :color="color"
                    />
                </v-container>

</template>

<script>
import { mapState } from 'vuex'
export default {
 props:['posts','post','comment'],
 data:()=>({
     replyVal:"",
 }),
 computed: {
        ...mapState(["color"]),
    },
 methods:{
     postReply(){
            let postIndex =this.posts.indexOf(this.post)
            let commentIndex =this.posts[postIndex].comments.indexOf(this.comment)
            const reply={
                postIndex:postIndex,
                commentIndex:commentIndex,
                postId:this.post.id,
                commentId:this.comment.id,
                reply : this.replyVal,
            }
            this.$store.dispatch('post/POST_REPLY',reply)
            .then(()=>{
                this.replyVal=''
            })
        },
 }
}
</script>

<style>

</style>