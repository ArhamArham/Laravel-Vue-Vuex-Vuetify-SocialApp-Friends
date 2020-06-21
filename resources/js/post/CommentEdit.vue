<template>
    <span>
        <span class="ml-1" v-if="comment.edit === 0">{{
            comment.comment
        }}</span>
        <input
            :style="commentColor"
            v-on:keyup.enter="updatecomment"
            type="text"
            class="editCom "
            v-if="comment.edit === 1"
            :placeholder="comment.comment"
            v-model="commentVal"
        />
    </span>
</template>

<script>
export default {
    props: ["posts", "post", "comment"],
    data: () => ({
        commentColor:'color:black',
        commentVal: ""
    }),
    methods: {
        updatecomment() {
            let postIndex =this.posts.indexOf(this.post)
            let commentIndex =this.posts[postIndex].comments.indexOf(this.comment)
            const comment={
                postIndex:postIndex,
                commentIndex:commentIndex,
                postId:this.post.id,
                commentId:this.comment.id,
                comment : this.commentVal,
            }
            this.$store.dispatch('post/UPDATE_COMMENT',comment)
                .then(res => {
                    this.commentVal = "";
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
};
</script>

<style></style>
