<template>
    <div>
        <!-- Form -->
        <form @submit.prevent="submit" class="form">
            <!-- Email -->
            <input placeholder="Email" type="email" v-model="form.email">

            <!-- Password -->
            <input placeholder="Password" type="password" v-model="form.password">

            <!-- Submit -->
            <button type="submit">Log In</button>
        </form>
    </div>
</template>

<script>
// Modules
import {mapActions} from 'vuex'

export default {
    data: () => ({
        // Auth data
        form: {
            email: '',
            password: ''
        },
    }),

    methods: {
        ...mapActions({
            attempt: 'auth/attempt',
            logIn: 'auth/login'
        }),

        isEmptyOrSpaces (str) {
            return str === null || str.match(/^ *$/) !== null;
        },

        submit() {
            // If any input is empty
            if (this.isEmptyOrSpaces(this.form.email) || this.isEmptyOrSpaces(this.form.password)) {
                alert('Fill in all the fields');

                return;
            }

            // Attempt to log in
            this.logIn(this.form)
            .then(() => {
                this.$router.push('messenger');

            }).catch((error) => {
                console.log(error);
            });
        },
    },
}

</script>

<style scoped>
    @import '../assets/css/styles-auth.css';
</style>