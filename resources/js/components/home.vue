<template>
    <div v-if="this.$store.state.user != null">
        <v-toolbar class="d-flex justify-center">
            <v-toolbar-title v-if="this.$store.state.user.type === 'EM'">Employee-Manager's Panel</v-toolbar-title>
            <v-toolbar-title v-if="this.$store.state.user.type === 'EC'">Cook's Panel</v-toolbar-title>
            <v-toolbar-title v-if="this.$store.state.user.type === 'ED'">Deliveryman's Panel</v-toolbar-title>
            <v-toolbar-title v-if="this.$store.state.user.type === 'C'">Customer's Panel</v-toolbar-title>
        </v-toolbar>
        <template v-if="this.$store.state.user.type === 'EM'">
            <aux_home_manager></aux_home_manager>
        </template>
        <template v-if="this.$store.state.user.type === 'EC'">
            <aux_home_cook></aux_home_cook>
        </template>
        <template v-if="this.$store.state.user.type === 'ED'">
            <aux_home_deliveryman></aux_home_deliveryman>
        </template>
        <template v-if="this.$store.state.user.type === 'C'">
            <aux_home_customer/>
        </template>

    </div>
</template>

<script>
import aux_home_customer from "./auxiliares/aux_home_customer";
import Aux_home_manager from "./auxiliares/aux_home_manager";
import Aux_home_cook from "./auxiliares/aux_home_cook";
import Aux_home_deliveryman from "./auxiliares/aux_home_deliveryman";
export default {
    name: "home",
    components: {
        Aux_home_deliveryman,
        Aux_home_cook,
        Aux_home_manager,
        aux_home_customer
    },

    data: () => {
        return {

        }
    },
    methods: {

    },
    created() {
        this.$store.dispatch("loadLoggedUser")
    },
}
</script>

<style scoped>

</style>
