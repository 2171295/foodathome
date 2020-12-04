<template>
    <div>
        <v-toolbar class="d-flex justify-center">
            <v-toolbar-title v-if="this.$store.state.user.type === 'EM'">Employee-Manager's Panel</v-toolbar-title>
            <v-toolbar-title v-if="this.$store.state.user.type === 'EC'">Cook's Panel</v-toolbar-title>
            <v-toolbar-title v-if="this.$store.state.user.type === 'ED'">-Deliveryman's Panel</v-toolbar-title>
        </v-toolbar>
        {{this.$store.state.user}}

    </div>
</template>

<script>
export default {
name: "home"
}
</script>

<style scoped>

</style>
