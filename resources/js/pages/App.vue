<template>
    <div>
        <categories></categories>
        <nav></nav>
        <div class="container">
            <router-view></router-view>
        </div>
        <footer></footer>
    </div>
</template>
<script>
    import Categories from "./Categories";
    import Nav from "./Nav";
    import Footer from "./Footer";

    export default {
        components: {
            Nav,
            Categories,
            Footer
        },

        created() {
            if (sessionStorage.getItem("store") ) {
                this.$store.replaceState(Object.assign({}, this.$store.state,JSON.parse(sessionStorage.getItem("store"))))
            }
            //在页面刷新时将vuex里的信息保存到sessionStorage里
            window.addEventListener("beforeunload",()=>{
                sessionStorage.setItem("store",JSON.stringify(this.$store.state))
            })
        }
    }
</script>

