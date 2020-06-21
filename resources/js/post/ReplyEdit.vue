<template>
  <span>
        <span class="ml-1" v-if="reply.edit === 0">{{
            reply.reply
        }}</span>
        <input
        :style="replyColor"
            v-on:keyup.enter="updateReply"
            type="text"
            class="editCom "
            v-if="reply.edit === 1"
            :placeholder="reply.reply"
            v-model="replyVal"
        />
    </span>
                    
</template>

<script>
export default {
    props: ["posts", "post", "comment","reply"],
    data: () => ({
        replyColor:'color:black',
        replyVal: ""
    }),
    methods: {
        updateReply() {
            let postIndex =this.posts.indexOf(this.post)
            let commentIndex =this.posts[postIndex].comments.indexOf(this.comment)
            let replyIndex =this.posts[postIndex].comments[commentIndex].replies.indexOf(this.reply)
            const reply={
                postIndex:postIndex,
                commentIndex:commentIndex,
                replyIndex:replyIndex,
                replyId:this.reply.id,
                reply : this.replyVal,
            }
            this.$store.dispatch('post/UPDATE_REPLY',reply)
                .then(() => {
                    this.replyVal = "";
                })
        }
    },
    created() {
    if (localStorage.getItem("theme") == "false") {
      this.$vuetify.theme.dark = false;
    }
    if (localStorage.getItem("theme") == "true") {
      this.$vuetify.theme.dark = true;
      this.replyColor='color:white'
    }
  },

}
</script>

<style>

</style>