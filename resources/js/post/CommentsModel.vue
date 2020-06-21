<template>
    <div class="comments" v-if="post.comments.length === 0"></div>
    <div v-else>
        <v-card-title class="pt-2 pb-0">Comments</v-card-title>
                    <div class="ml-4" v-if="post.comments.length > 4">
								<small @click.prevent="commentsToShow">View more comments</small>
					</div>
                <div v-for="(iC, index) in post.comments" :key="index">
        <div v-if="index < post.comments_to_show">
            <v-card-text class="pt-2 pb-2">
                <strong>{{ iC.user.name }}</strong>
                <CommentEditCom :posts="posts" :post="post" :comment="iC" />
                    <div v-if="iC.edit === 0" class="float-right p-0">
                <div v-if="iC.user_id == userData.id">
                        <v-icon small :color="color" @click="editcom(iC)">mdi-pencil</v-icon>
                        <v-icon small :color="color" @click="deleteComment(iC)">mdi-delete</v-icon>
                    </div>
                </div>
            </v-card-text>
            <div class="ml-4" v-if="iC.replies.length > 4">
						<small @click.prevent="repliesToShow(iC)">View more replies</small>
			</div>
            <div class="replies" v-for="(iR, indexReply) in iC.replies" :key="indexReply">
                <div v-if="indexReply < iC.replies_to_show">
                <v-card-text class="pl-15 mt-0 pt-0">
                    <strong>{{ iR.user.name }}</strong>
                    <ReplyEdit
                        :posts="posts"
                        :post="post"
                        :comment="iC"
                        :reply="iR"
                    />
                    <div
                        v-if="iR.edit === 0"
                        
                        class="float-right p-0"
                    >
                    <div v-if="iR.user_id == userData.id">
                        <v-icon small :color="color" @click="editreply(iC, iR)">mdi-pencil</v-icon>
                        <v-icon small :color="color" @click="deleteReply(iC, iR)">mdi-delete</v-icon>
                    </div>
                    </div>
                </v-card-text>
            </div>
            </div>
            <Reply :posts="posts" :post="post" :comment="iC" />
        </div>
                </div>
    </div>
</template>

<script>
import Reply from "../post/ReplyForm";
import CommentEditCom from "../post/CommentEdit";
import ReplyEdit from "../post/ReplyEdit";
import { mapState } from 'vuex';
export default {
    data: () => ({}),
    props: ["posts", "post"],
    components: {
        Reply,
        CommentEditCom,
        ReplyEdit
    },
    computed: {
        ...mapState(["color",'userData']),
    },
    methods: {
        commentsToShow(){
            let postIndex = this.posts.indexOf(this.post);
			this.posts[postIndex].comments_to_show += 4 
    },
    repliesToShow(comment){
        let postIndex = this.posts.indexOf(this.post);
            let commentIndex = this.posts[postIndex].comments.indexOf(comment);
            this.posts[postIndex].comments[commentIndex].replies_to_show += 4 
    },
        editcom(comment) {
            let postIndex = this.posts.indexOf(this.post);
            let commentIndex = this.posts[postIndex].comments.indexOf(comment);
            this.posts[postIndex].comments[commentIndex].edit = 1;
        },
        editreply(comment, reply) {
            let postIndex = this.posts.indexOf(this.post);
            let commentIndex = this.posts[postIndex].comments.indexOf(comment);
            let replyIndex = this.posts[postIndex].comments[
                commentIndex
            ].replies.indexOf(reply);
            this.posts[postIndex].comments[commentIndex].replies[
                replyIndex
            ].edit = 1;
        },
        deleteComment(c) {
            let postIndex = this.posts.indexOf(this.post);
            let commentIndex = this.posts[postIndex].comments.indexOf(c);
            const comment={
                postIndex:postIndex,
                commentIndex:commentIndex,
                commentId:c.id,
            }
            this.$store.dispatch('post/DELETE_COMMENT', comment)
        },
        deleteReply(c, r) {
            let postIndex = this.posts.indexOf(this.post);
            let commentIndex = this.posts[postIndex].comments.indexOf(c);
            let replyIndex = this.posts[postIndex].comments[commentIndex].replies.indexOf(r);
            const reply={
                postIndex:postIndex,
                commentIndex:commentIndex,
                replyIndex:replyIndex,
                replyId:r.id,
            }
            this.$store.dispatch('post/DELETE_REPLY', reply)
        }
    }
};
</script>

<style></style>
