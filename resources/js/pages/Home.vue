<style lang="scss">

</style>

<template>
    <div class="row home-content">
        <div class="col-md-1"></div>
        <div class="col-md-10 col-md-offset-1">
            <div v-for="article in articles.data" class="media animated pulse">
                <router-link :to="`/article/${article.id}`" class="media-left">
                    <img :src="article.cover_img" data-holder-rendered="true">
                </router-link>
                <div class="media-body">
                    <h6 class="media-heading">
                        <a href="">{{article.article_name}}</a>
                    </h6>
                    <div class="meta">
                        <span class="cinema">{{article.describe}}</span>
                    </div>
                </div>
            </div>
            <div class="panel-footer  remove-padding-horizontal pager-footer">
                <Pagination :currentPage="currentPage" :total="total" :pageSize="parseInt(pageSize)" :onPageChange="changePage"/>
            </div>
        </div>
    </div>
</template>
<script>
    import Pagination from '../components/global/Pagination';
    export default {
        created() {
            this.$store.dispatch('loadArticles',this.$route.query.page);
        },
        
        components: {
            Pagination
        },

        watch: {
            '$route'(to) {
              this.getArticles('watch');
            }
        },

        computed: {
            articlesLoadStatus(){
                return this.$store.getters.getArticlesLoadStatus;
            },
            articles(){
                 return   this.getArticles('computed');

            },
            currentPage() {
                return parseInt(this.$route.query.page) || 1
            },
            total()
            {
                return this.$store.getters.getTotal;
            },
            pageSize()
            {
                return this.$store.getters.getPageSize;
            }
        },

        methods: {
            changePage(page) {
                this.$router.push({query: {...this.$route.query, page}})
            },
            getArticles(method)
            {
                if(method === 'watch') {
                   const page = parseInt(this.$route.query.page) || 1;
                    this.$store.dispatch('loadArticles',page);
                    return this.$store.getters.getArticles;
                }else {
                    return this.$store.getters.getArticles;
                }
            }
        }
    }
</script>


