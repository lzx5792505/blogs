<style lang="scss">

</style>

<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
        <div class="container">
            <router-link class="navbar-brand" :to="{ name: '/#' }">Latent</router-link>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto  ">
                    <li v-for="nav in categories" class="nav-item  dropdown">
                        <router-link :to="nav.url" v-if="!nav.children " class="nav-link " data-toggle="dropdown "
                                  data-target="#navbarSupportedContent"
            aria-haspopup="true"
                                     aria-expanded="false">{{nav.name}}
                        </router-link>
                        <router-link :to="{name:nav.url}" v-else class="nav-link dropdown-toggle "
                                     data-toggle="dropdown" aria-haspopup="true"
                                     aria-expanded="false">{{nav.name}}
                        </router-link>
                        <ul v-if="nav.children" class="dropdown-menu" aria-labelledby="categoryTree">
                            <NavCategory v-for="(item,index) in nav.children" :synClass="item"
                                         :key="index"></NavCategory>
                        </ul>
                    </li>
                </ul>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse"            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <input type="text" class="nav-search-box-input form-control ds-input" aria-label="search" placeholder="搜索"
                   value>
            <i class="el-icon-search nav-search"></i>
        </div>
    </nav>
</template>

<script>
    import NavCategory from "./layouts/NavCategory";

    export default {
        name: "Categories",
        components: {
            NavCategory
        },
        created() {
            this.$store.dispatch('loadCategories')
        },
        computed: {
            categoriesLoadStatus() {
                return this.$store.getters.getCategoriesLoadStatus;
            },
            categories() {
                return this.$store.getters.getCategories;
            },

        },
    }
</script>

