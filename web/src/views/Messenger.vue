<template>
    <div>
        <the-chat :interlocutor_id="interlocutor_id" @changeInterlocutor="interlocutor_id = $event"/>
    </div>
</template>

<script>
// Components
import TheChat from '@/components/TheChat'

export default {
    components: {
        TheChat
    },

    props: {
        INTERLOCUTOR_ID: {type: Number}
    },

    data: () => ({
        // Interlocutor id
        interlocutor_id: null,
    }),

    mounted () {
        // Set local variable of interlocutor id equal to the global one
        this.interlocutor_id = this.INTERLOCUTOR_ID;
    },
}
</script>

<style scoped>
    @import '../assets/css/styles-app.css';
</style>