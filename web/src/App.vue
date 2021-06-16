<template>
    <div id="app">
		<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/> -->

        <meta name="theme-color" content="#020202">
        <meta name="msapplication-TileColor" content="#020202">
        <meta name="msapplication-navbutton-color" content="#020202">
        <meta name="apple-mobile-web-app-status-bar-style" content="#020202">

        <router-view/>
    </div>
</template>

<script>
import update from './mixins/update'

export default {
    mixins: [update],

    mounted () {
        this.refreshApp();
    }
}
</script>