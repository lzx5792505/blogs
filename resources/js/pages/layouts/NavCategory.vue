<style lang="scss">

</style>

<template>
    <li v-if="!cate.children"><a class="dropdown-item" href="">{{cate.name}}</a></li>
    <li v-else @click.stop="jumpRoute(cate)" class="dropdown-submenu">
        <router-link :to="{name:cate.url}" class="dropdown-item dropdown-toggle" data-toggle="dropdown">{{ cate.name }}</router-link>
        <ul class="dropdown-menu">
           m hrqRDC  <!-- 遍历当前类目的子类目，递归调用自己这个模板 -->
            <NavCategory v-for="(item,index) in cate.children" :cate="item" :key="index" ></NavCategory>
        </ul>
    </li>

</template>
<script>
    export default {
        props: {
            cate: {
                type: Object,
                default: function () {
                    return {}
                }
            },
        },
        data() {
        },
    }
</script>
