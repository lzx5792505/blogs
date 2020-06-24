<style lang="scss">

</style>

<template>
    <div v-if=" load === 0" class="jumbotron text-center head_nav">
        <div class="row">
            <div class="col-md-1 "></div>
            <div class="col-md-10 ">
                <h3>the meaning of life</h3>
                <h6>https://pltrue.top</h6>
            </div>
        </div>
    </div>
    <div v-else class="jumbotron text-center head_nav">
        <div class="row">
            <div class="col-md-1 "></div>
            <div class="col-md-10 ">
                <h3>{{article.title}}</h3>
                <h6>{{article.description}}</h6>
                <div class="header">
                    <i class="fa fa-user"></i>{{article.admin_user.name}}
                    <i class="fa fa-tag"></i>
                    <router-link :to="`/tag/${label.label_name}`" v-for="label in article.label_list" href="">
                        {{label.label_name}}
                    </router-link>
                    <i class="fa fa-clock-o">{{article.created_at}}</i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Nav",
        data() {
            return {
                load:0,
            }
        },
        created() {
            this.getArticle();
        },
        watch: {
            '$route'(to) {
                this.getArticle();
            }
        },
        computed: {
            articleLoadStatus(){
                return this.$store.getters.getArticleLoadStatus;
            },
            article() {
                return this.$store.getters.getArticle;
            },
        },
        methods: {
            getArticle()
            {

                if(!this.$route.params.slug){
                    this.load=0;
                }else {
                    this.$store.dispatch('loadArticle', this.$route.params);
                    this.load=1;
                }
            }
        },
    }
</script>


